<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Mail class.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/phpOMS#34
 *  Implement!!!
 */
class EmailAbstract
{
    /**
     * Host.
     *
     * @var string
     * @since 1.0.0
     */
    protected $host = '';

    /**
     * Port.
     *
     * @var int
     * @since 1.0.0
     */
    protected $port = 25;

    /**
     * Use ssl.
     *
     * @var bool
     * @since 1.0.0
     */
    protected $ssl = false;

    /**
     * Mailbox base.
     *
     * @var string
     * @since 1.0.0
     */
    protected $mailbox = '';

    /**
     * Timeout.
     *
     * @var int
     * @since 1.0.0
     */
    protected $timeout = 30;

    /**
     * Connection.
     *
     * @var mixed
     * @since 1.0.0
     */
    protected $con = null;

    /**
     * Construct
     *
     * @param string $host    Host
     * @param int    $port    Host port
     * @param int    $timeout Timeout
     * @param bool   $ssl     Use ssl
     *
     * @since 1.0.0
     */
    public function __construct(string $host = 'localhost', int $port = 25, int $timeout = 30, bool $ssl = false)
    {
        $this->host    = $host;
        $this->port    = $port;
        $this->timeout = $timeout;
        $this->ssl     = $ssl;

        \imap_timeout(\IMAP_OPENTIMEOUT, $timeout);
        \imap_timeout(\IMAP_READTIMEOUT, $timeout);
        \imap_timeout(\IMAP_WRITETIMEOUT, $timeout);
        \imap_timeout(\IMAP_CLOSETIMEOUT, $timeout);
    }

    /**
     * Decode
     *
     * @param string $content  Content to decode
     * @param int    $encoding Encoding type
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function decode(string $content, int $encoding)
    {
        if ($encoding === ContentEncoding::BASE64) {
            return \imap_base64($content);
        } elseif ($encoding === ContentEncoding::EIGHTBIT) {
            return \imap_8bit($content);
        }

        return \imap_qprint($content);
    }

    /**
     * Descrutor
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Disconnect server
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function disconnect() : void
    {
        if ($this->con !== null) {
            \imap_close($this->con);
            $this->con = null;
        }
    }

    /**
     * Connect to server
     *
     * @param string $user Username
     * @param string $pass Password
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function connect(string $user = '', string $pass = '') : bool
    {
        $this->mailbox = \substr($this->mailbox, 0, -1) . ($this->ssl ? '/ssl/validate-cert' : '/novalidate-cert') . '}';

        try {
            $this->con = \imap_open($this->mailbox . 'INBOX', $user, $pass);

            return true;
        } catch (\Throwable $t) {
            $this->con = null;

            return false;
        }
    }

    /**
     * Test connection
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isConnected() : bool
    {
        return $this->con === null ? false : \imap_ping($this->con);
    }

    /**
     * Get boxes.
     *
     * @param string $pattern Pattern for boxes
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getBoxes(string $pattern = '*') : array
    {
        $list = \imap_list($this->con, $this->host, $pattern);

        return $list === false ? [] : $list;
    }

    /**
     * Get inbox quota.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getQuota() : array
    {
        $quota = [];

        try {
            $quota = \imap_get_quotaroot($this->con, "INBOX");
        } finally {
            return $quota === false ? [] : $quota;
        }
    }

    /**
     * Get email.
     *
     * @param string $id mail id
     *
     * @return Mail
     *
     * @since 1.0.0
     */
    public function getEmail(string $id) : Mail
    {
        $mail = new Mail($id);

        if ((int) $id > $this->countMessages()) {
            return $mail;
        }

        $mail->setOverview(\imap_fetch_overview($this->con, $id));
        $mail->setBody(\imap_fetchbody($this->con, (int) $id, '1.1'));
        //$mail->setEncoding(\imap_fetchstructure($this->con, (int) $id));

        return $mail;
    }

    /**
     * Get all inbox messages.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxAll() : array
    {
        return $this->getInboxOverview('ALL');
    }

    /**
     * Get inbox overview.
     *
     * @param string $option Inbox option (imap_search creterias)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxOverview(string $option = 'ALL') : array
    {
        $ids = \imap_search($this->con, $option, \SE_FREE, 'UTF-8');

        return \is_array($ids) ? \imap_fetch_overview($this->con, \implode(',', $ids)) : [];
    }

    /**
     * Get all new inbox messages.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxNew() : array
    {
        return $this->getInboxOverview('NEW');
    }

    /**
     * Get all inbox messages from a person.
     *
     * @param string $from Messages from
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxFrom(string $from) : array
    {
        return $this->getInboxOverview('FROM "' . $from . '"');
    }

    /**
     * Get all inbox messages to a person.
     *
     * @param string $to Messages to
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxTo(string $to) : array
    {
        return $this->getInboxOverview('TO "' . $to . '"');
    }

    /**
     * Get all inbox messages cc a person.
     *
     * @param string $cc Messages cc
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxCc(string $cc) : array
    {
        return $this->getInboxOverview('CC "' . $cc . '"');
    }

    /**
     * Get all inbox messages bcc a person.
     *
     * @param string $bcc Messages bcc
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxBcc(string $bcc) : array
    {
        return $this->getInboxOverview('BCC "' . $bcc . '"');
    }

    /**
     * Get all answered inbox messages.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxAnswered() : array
    {
        return $this->getInboxOverview('ANSWERED');
    }

    /**
     * Get all inbox messages with a certain subject.
     *
     * @param string $subject Subject
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxSubject(string $subject) : array
    {
        return $this->getInboxOverview('SUBJECT "' . $subject . '"');
    }

    /**
     * Get all inbox messages from a certain date onwards.
     *
     * @param \DateTime $since Messages since
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxSince(\DateTime $since) : array
    {
        return $this->getInboxOverview('SINCE "' . $since->format('d-M-Y') . '"');
    }

    /**
     * Get all unseen inbox messages.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxUnseen() : array
    {
        return $this->getInboxOverview('UNSEEN');
    }

    /**
     * Get all seen inbox messages.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxSeen() : array
    {
        return $this->getInboxOverview('SEEN');
    }

    /**
     * Get all deleted inbox messages.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxDeleted() : array
    {
        return $this->getInboxOverview('DELETED');
    }

    /**
     * Get all inbox messages with text.
     *
     * @param string $text Text in message body
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInboxText(string $text) : array
    {
        return $this->getInboxOverview('TEXT "' . $text . '"');
    }

    /**
     * Create mailbox
     *
     * @param string $mailbox Mailbox to create
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function createMailbox(string $mailbox) : bool
    {
        return \imap_createmailbox($this->con, $mailbox);
    }

    /**
     * Rename mailbox
     *
     * @param string $old Old mailbox name
     * @param string $new New mailbox name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function renameMailbox(string $old, string $new) : bool
    {
        return \imap_renamemailbox($this->con, $old, $new);
    }

    /**
     * Delete mailbox
     *
     * @param string $mailbox Mailbox to delete
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function deleteMailbox(string $mailbox) : bool
    {
        return \imap_deletemailbox($this->con, $mailbox);
    }

    /**
     * Check message to delete
     *
     * @param int $id Message id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function deleteMessage(int $id) : bool
    {
        return \imap_delete($this->con, $id);
    }

    /**
     * Delete all marked messages
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function deleteMarkedMessages() : bool
    {
        return \imap_expunge($this->con);
    }

    /**
     * Check message to delete
     *
     * @param int $length Amount of message overview
     * @param int $start  Start index of the overview for pagination
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getMessageOverview(int $length = 0, int $start = 1) : array
    {
        if ($length === 0) {
            $info   = \imap_check($this->con);
            $length = $info->Nmsgs;
        }

        return \imap_fetch_overview($this->con, $start . ':' . ($length - 1 + $start), 0);
    }

    /**
     * Count messages
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function countMessages() : int
    {
        return \imap_num_msg($this->con);
    }

    /**
     * Get message header
     *
     * @param int $id Message id
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getMessageHeader(int $id) : string
    {
        if ($id > $this->countMessages()) {
            return '';
        }

        return \imap_fetchheader($this->con, $id);
    }
}
