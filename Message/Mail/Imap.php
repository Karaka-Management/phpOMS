<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message\Mail;

class Imap extends Mail
{
    private $inbox = null;
    private $host = '';

    public function __construct()
    {
        parent::__construct(MailType::IMAP);
    }

    public function __destruct()
    {
        if (isset($this->inbox)) {
            imap_close($this->inbox);
        }
    }

    public function connect($host, $user, $password)
    {
        $this->host = $host;
        $this->inbox = imap_open($host, $user, $password);

        return !($this->inbox === false);
    }

    public function getBoxes() {
        return imap_list($this->inbox, $this->host, '*');
    }

    public function getQuota() {
        return imap_get_quotaroot($this->inbox, "INBOX");
    }

    public function getInbox(string $option = 'ALL') : array
    {
        $ids = imap_search($this->inbox, $option, SE_FREE, 'UTF-8');

        return is_array($ids) ? imap_fetch_overview($this->inbox, implode(',', $ids)) : [];
    }

    public function getEmail($id)
    {
        return [
            'overview' => imap_fetch_overview($this->inbox, $id),
            'body'     => imap_fetchbody($this->inbox, $id, 2),
            'encoding' => imap_fetchstructure($this->inbox, $id),
        ];
    }

    public function getInboxAll()
    {
        return $this->getInbox('ALL');
    }

    public function getInboxNew()
    {
        return $this->getInbox('NEW');
    }

    public function getInboxFrom(string $from)
    {
        return $this->getInbox('FROM "' . $from . '"');
    }

    public function getInboxTo(string $to)
    {
        return $this->getInbox('TO "' . $to . '"');
    }

    public function getInboxCc(string $cc)
    {
        return $this->getInbox('CC "' . $cc . '"');
    }

    public function getInboxBcc(string $bcc)
    {
        return $this->getInbox('BCC "' . $bcc . '"');
    }

    public function getInboxAnswered()
    {
        return $this->getInbox('ANSWERED');
    }

    public function getInboxSubject(string $subject)
    {
        return $this->getInbox('SUBJECT "' . $subject . '"');
    }

    public function getInboxSince(\DateTime $since)
    {
        return $this->getInbox('SINCE "' . $since->format('d-M-Y') . '"');
    }

    public function getInboxUnseen()
    {
        return $this->getInbox('UNSEEN');
    }

    public function getInboxSeen()
    {
        return $this->getInbox('SEEN');
    }

    public function getInboxDeleted()
    {
        return $this->getInbox('DELETED');
    }

    public function getInboxText(string $text)
    {
        return $this->getInbox('TEXT "' . $text . '"');
    }

    public static function decode($content, $encoding)
    {
        if ($encoding == 3) {
            return imap_base64($content);
        } else if ($encoding == 1) {
            return imap_8bit($content);
        } else {
            return imap_qprint($content);
        }
    }
}
