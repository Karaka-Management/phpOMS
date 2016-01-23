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

use phpOMS\Datatypes\Exception\InvalidEnumValue;

abstract class Mail
{
    protected $from = '';
    protected $to = [];
    protected $subject = '';
    protected $cc = [];
    protected $replyTo = [];
    protected $bcc = [];
    protected $attachment = [];
    protected $body = '';
    protected $bodyAlt = '';
    protected $bodyMime = '';
    protected $headerMail = '';
    protected $wordWrap = 78;
    protected $type = MailType::MAIL;
    protected $hostname = '';
    protected $messageId = '';
    protected $messageType = '';
    protected $messageDate = null;

    protected $mailer = null;

    public function __construct(int $type)
    {
        $this->type = $type;

        switch ($type) {
            case MailType::MAIL:
                break;
            case MailType::SMTP:
                break;
            case MailType::IMAP:
                break;
            case MailType::POP3:
                break;
            case MailType::SENDMAIL:
                break;
            default:
                throw new InvalidEnumValue($type);
        }
    }

}
