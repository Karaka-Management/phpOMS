<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message\Mail;

/**
 * Imap mail class.
 *
 * @category   Framework
 * @package    phpOMS\Message\Mail
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Imap extends Mail
{
    /**
     * Mail inbox.
     *
     * @var resource
     * @since 1.0.0
     */
    private $inbox = null;

    /**
     * Host.
     *
     * @var string
     * @since 1.0.0
     */
    private $host = '';

    /**
     * User.
     *
     * @var string
     * @since 1.0.0
     */
    private $user = '';

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        parent::__construct(MailType::IMAP);
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

    /**
     * Destructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __destruct()
    {
        if (isset($this->inbox)) {
            imap_close($this->inbox);
        }
    }

    /**
     * Connect to inbox.
     *
     * @param string $host     Host
     * @param string $user     User
     * @param string $password Password
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function connect($host, $user, $password)
    {
        $this->host  = $host;
        $this->user  = $user;
        $this->inbox = imap_open($host, $user, $password);

        return !($this->inbox === false);
    }

    /**
     * Get boxes.
     *
     * @param string $pattern Pattern for boxes
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBoxes(string $pattern = '*') : array
    {
        return imap_list($this->inbox, $this->host, $pattern);
    }

    /**
     * Get inbox quota.
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getQuota()
    {
        return imap_get_quotaroot($this->inbox, "INBOX");
    }

    /**
     * Get email.
     *
     * @param mixed $id mail id
     *
     * @return Mail
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getEmail($id) : Mail
    {
        $mail = new Mail($id);
        $mail->setOverview(imap_fetch_overview($this->inbox, $id));
        $mail->setBody(imap_fetchbody($this->inbox, $id, 2));
        $mail->setEncoding(imap_fetchstructure($this->inbox, $id));

        return $mail;
    }

    /**
     * Get all inbox messages.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getInboxOverview(string $option = 'ALL') : array
    {
        $ids = imap_search($this->inbox, $option, SE_FREE, 'UTF-8');

        return is_array($ids) ? imap_fetch_overview($this->inbox, implode(',', $ids)) : [];
    }

    /**
     * Get all new inbox messages.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getInboxText(string $text) : array
    {
        return $this->getInboxOverview('TEXT "' . $text . '"');
    }
}
