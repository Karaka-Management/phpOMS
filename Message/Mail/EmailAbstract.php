<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Mail class.
 *
 * @category   Framework
 * @package    phpOMS\Message\Mail
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MailAbstract
{
    private $host = '';

    private $port = 25;

    private $ssl = false;

    private $mailbox = '';

    private $timeout = 30;

    public function __construct(string $host = 'localhost', int $port = 25, int $timeout = 30, bool $ssl = false)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->ssl = $ssl;

        imap_timeout(IMAP_OPENTIMEOUT, $timeout);
        imap_timeout(IMAP_READTIMEOUT, $timeout);
        imap_timeout(IMAP_WRITETIMEOUT, $timeout);
        imap_timeout(IMAP_CLOSETIMEOUT, $timeout);
    }

    public static function decode($content, $encoding)
    {
        if ($encoding == 3) {
            return imap_base64($content);
        } else {
            if ($encoding == 1) {
                return imap_8bit($content);
            } else {
                return imap_qprint($content);
            }
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect()
    {
        if(!isset($this->con)) {
            imap_close($this->con);
            $this->con = null;
        }
    }

    public function connect(string $user = '', string $pass = '')
    {
        $this->mailbox = substr($this->mailbox, 0, -1) . ($this->ssl ? '/ssl/validate-cert' : '/novalidate-cert') . '}';

        // /novalidate-cert
        if(!isset($this->con)) {
            $this->con = imap_open($this->mailbox . 'INBOX', $user, $pass);
        }
    }

    public function isConnected() : bool
    {
        return imap_ping($this->con);
    }

    /**
     * Get boxes.
     *
     * @param string $pattern Pattern for boxes
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getBoxes(string $pattern = '*') : array
    {
        return imap_list($this->con, $this->host, $pattern);
    }

    /**
     * Get inbox quota.
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function getQuota()
    {
        return imap_get_quotaroot($this->con, "INBOX");
    }

    /**
     * Get email.
     *
     * @param mixed $id mail id
     *
     * @return Mail
     *
     * @since  1.0.0
     */
    public function getEmail($id) : Mail
    {
        $mail = new Mail($id);
        $mail->setOverview(imap_fetch_overview($this->con, $id));
        $mail->setBody(imap_fetchbody($this->con, $id, 2));
        $mail->setEncoding(imap_fetchstructure($this->con, $id));

        return $mail;
    }

    /**
     * Get all inbox messages.
     *
     * @return array
     *
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getInboxOverview(string $option = 'ALL') : array
    {
        $ids = imap_search($this->con, $option, SE_FREE, 'UTF-8');

        return is_array($ids) ? imap_fetch_overview($this->con, implode(',', $ids)) : [];
    }

    /**
     * Get all new inbox messages.
     *
     * @return array
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getInboxText(string $text) : array
    {
        return $this->getInboxOverview('TEXT "' . $text . '"');
    }

    public function listMailbox() : array
    {
        return imap_listmailbox($this->con, $this->mailbox, '*');
    }

    public function createMailbox(string $mailbox) : bool
    {
        return imap_createmailbox($this->con, $mailbox);
    }

    public function renameMailbox(string $old, string $new) : bool
    {
        return imap_renamemailbox($this->con, $old, $new);
    }

    public function deleteMailbox(string $mailbox) : bool
    {
        return imap_deletemailbox($this->con, $mailbox);
    }

    public function deleteMessage(int $id) : bool
    {
        return imap_delete($this->con, $id);
    }

    public function deleteMarkedMessages() : bool
    {
        return imap_expunge($this->con);
    }

    public function getMessageOverview(int $length = 0, int $start = 1) : array
    {
        if($length === 0) {
            $info = imap_check($this->con);
            $length = $info->Nmsgs;
        }

        return imap_fetch_overview($mbox, $start . ':' . ($length + $start), 0);
    }

    public function countMessages() : int
    {
        return imap_num_msg($this->con);
    }

    public function getMessageHeader(int $id) : string
    {
        return imap_fetchheader($this->con, $id);
    }
}