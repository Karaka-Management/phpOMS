<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Mail
 * @license   GLGPL 2.1 License
 * @version   1.0.0
 * @link      https://orange-management.org
 *
 * Extended based on:
 * GLGPL 2.1 License
 * (c) 2012 - 2015 Marcus Bointon, 2010 - 2012 Jim Jagielski, 2004 - 2009 Andy Prevost
 * (c) PHPMailer
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\System\SystemUtils;
use phpOMS\Utils\StringUtils;
use phpOMS\Validation\Network\Email as EmailValidator;
use phpOMS\Validation\Network\Hostname;

/**
 * Mail class.
 *
 * @package phpOMS\Message\Mail
 * @license GLGPL 2.1 License
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class MailHandler
{
    /**
     * The maximum line length allowed by RFC 2822 section 2.1.1.
     *
     * @var int
     * @since 1.0.0
     */
    const MAX_LINE_LENGTH = 998;

    /**
     * Mailer for sending message
     *
     * @var string
     * @since 1.0.0
     */
    public string $mailer = SubmitType::MAIL;

    /**
     * The path to the sendmail program.
     *
     * @var string
     * @since 1.0.0
     */
    public string $mailerTool = '';

    /**
     * Use sendmail MTA
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $useMailOptions = true;

    /**
     * Hostname for Message-ID and HELO string.
     *
     * If empty this is automatically generated.
     *
     * @var string
     * @since 1.0.0
     */
    public string $hostname = '';

    /**
     * SMTP hosts.
     * (e.g. "smtp1.example.com:25;smtp2.example.com").
     *
     * @var string
     * @since 1.0.0
     */
    public string $host = 'localhost';

    /**
     * The default port.
     *
     * @var int
     * @since 1.0.0
     */
    public int $port = 25;

    /**
     * The SMTP HELO/EHLO name
     *
     * @var string
     * @since 1.0.0
     */
    public string $helo = '';

    /**
     * SMTP encryption
     *
     * @var string
     * @since 1.0.0
     */
    public string $encryption = EncryptionType::NONE;

    /**
     * Use TLS automatically if the server supports it.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $useAutoTLS = true;

    /**
     * Use smtp auth
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $useSMTPAuth = false;

    /**
     * Options passed when connecting via SMTP.
     *
     * @var array
     * @since 1.0.0
     */
    public array $smtpOptions = [];

    /**
     * SMTP username.
     *
     * @var string
     * @since 1.0.0
     */
    public string $username = '';

    /**
     * SMTP password.
     *
     * @var string
     * @since 1.0.0
     */
    public string $password = '';

    /**
     * SMTP auth type.
     *
     * @var string
     * @since 1.0.0
     */
    public string $authType = SMTPAuthType::NONE;

    /**
     * OAuth class.
     *
     * @var OAuth
     * @since 1.0.0
     */
    public mixed $oauth;

    /**
     * Server timeout
     *
     * @var int
     * @since 1.0.0
     */
    public int $timeout = 300;

    /**
     * Comma separated list of DSN notifications
     *
     * @var string
     * @since 1.0.0
     */
    public string $dsn = DsnNotificationType::NONE;

    /**
     * Keep connection alive.
     *
     * This requires a close call.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $keepAlive = false;

    /**
     * Use VERP
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $useVerp = false;

    /**
     * An instance of the SMTP sender class.
     *
     * @var null|Smtp
     * @since 1.0.0
     */
    public ?Smtp $smtp = null;

    /**
     * An instance of the SMTP sender class.
     *
     * @var resource
     * @since 1.0.0
     */
    public mixed $mailbox = null;

    /**
     * SMTP RFC standard line ending
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $LE = "\r\n";

    /**
     * Constructor.
     *
     * @param string $user       Username
     * @param string $pass       Password
     * @param int    $port       Port
     * @param string $encryption Encryption type
     *
     * @since 1.0.0
     */
    public function __construct(string $user = '', string $pass = '', int $port = 25, string $encryption = EncryptionType::NONE)
    {
        $this->username   = $user;
        $this->password   = $pass;
        $this->port       = $port;
        $this->encryption = $encryption;
    }

    /**
     * Destructor.
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->smtpClose();
    }

    /**
     * Set the mailer and the mailer tool
     *
     * @param string $mailer Mailer
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMailer(string $mailer) : void
    {
        $this->mailer = $mailer;

        switch ($mailer) {
            case SubmitType::MAIL:
            case SubmitType::SMTP:
                return;
            case SubmitType::SENDMAIL:
                $this->mailerTool = \stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false
                    ? '/usr/sbin/sendmail'
                    : $sendmailPath;
                return;
            case SubmitType::QMAIL:
                $this->mailerTool = \stripos($sendmailPath = \ini_get('sendmail_path'), 'qmail') === false
                    ? '/var/qmail/bin/qmail-inject'
                    : $sendmailPath;
                return;
            default:
                return;
        }
    }

    /**
     * Send mail
     *
     * @param $mail Mail
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function send(Email $mail) : bool
    {
        if (!$mail->preSend($this->mailer)) {
            return false;
        }

        return $this->postSend($mail);
    }

    /**
     * Send the mail
     *
     * @param Email $mail Mail
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function postSend(Email $mail) : bool
    {
        switch ($this->mailer) {
            case SubmitType::SENDMAIL:
            case SubmitType::QMAIL:
                return $this->sendmailSend($mail);
            case SubmitType::SMTP:
                return $this->smtpSend($mail);
            case SubmitType::MAIL:
                return $this->mailSend($mail);
            default:
                return false;
        }
    }

    /**
     * Send mail
     *
     * @param Email $mail Mail
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected function sendmailSend(Email $mail) : bool
    {
        $header = \rtrim($mail->headerMime, " \r\n\t") . self::$LE . self::$LE;

        // CVE-2016-10033, CVE-2016-10045: Don't pass -f if characters will be escaped.
        if (!empty($mail->sender) && StringUtils::isShellSafe($mail->sender)) {
            $mailerToolFmt = $this->mailer === SubmitType::QMAIL
                ? '%s -f%s'
                : '%s -oi -f%s -t';
        } elseif ($this->mailer === SubmitType::QMAIL) {
            $mailerToolFmt = '%s';
        } else {
            $mailerToolFmt = '%s -oi -t';
        }

        $mailerTool = \sprintf($mailerToolFmt, \escapeshellcmd($this->mailerTool), $mail->sender);

        $con = \popen($mailerTool, 'w');
        if ($con === false) {
            return false;
        }

        \fwrite($con, $header);
        \fwrite($con, $mail->bodyMime);

        $result = \pclose($con);

        return $result === 0;
    }

    /**
     * Send mail
     *
     * @param Email $mail Mail
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected function mailSend(Email $mail) : bool
    {
        $header = \rtrim($mail->headerMime, " \r\n\t") . self::$LE . self::$LE;

        $toArr = [];
        foreach ($mail->to as $toaddr) {
            $toArr[] = $mail->addrFormat($toaddr);
        }

        $to = \implode(', ', $toArr);

        //This sets the SMTP envelope sender which gets turned into a return-path header by the receiver
        // CVE-2016-10033, CVE-2016-10045: Don't pass -f if characters will be escaped.
        $params = null;
        if (!empty($mail->sender)
            && EmailValidator::isValid($mail->sender)
            && StringUtils::isShellSafe($mail->sender)
        ) {
            $params = \sprintf('-f%s', $mail->sender);
        }

        if (!empty($mail->sender) && EmailValidator::isValid($mail->sender)) {
            $oldFrom = \ini_get('sendmail_from');
            \ini_set('sendmail_from', $mail->sender);
        }

        $result = $this->mailPassthru($to, $mail, $header, $params);

        if (isset($oldFrom)) {
            \ini_set('sendmail_from', $oldFrom);
        }

        return $result;
    }

    /**
     * Call mail() in a safe_mode-aware fashion.
     *
     * @param string      $to     To
     * @param Email       $mail   Mail
     * @param string      $body   Message Body
     * @param string      $header Additional Header(s)
     * @param null|string $params Params
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function mailPassthru(string $to, Email $mail, string $header, string $params = null) : bool
    {
        $subject = $mail->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $mail->subject)));

        return !$this->useMailOptions || $params === null
            ? \mail($to, $subject, $mail->body, $header)
            : \mail($to, $subject, $mail->body, $header, $params);
    }

    /**
     * Send mail
     *
     * @param Email $mail Mail
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected function smtpSend(Email $mail) : bool
    {
        $header = \rtrim($mail->headerMime, " \r\n\t") . self::$LE . self::$LE;

        if (!$this->smtpConnect($this->smtpOptions)) {
            return false;
        }

        $smtpFrom = $mail->sender === '' ? $mail->from : $mail->sender;

        if (!$this->smtp->mail($smtpFrom)) {
            return false;
        }

        $badRcpt = [];
        foreach ([$mail->to, $mail->cc, $mail->bcc] as $togroup) {
            foreach ($togroup as $to) {
                $badRcpt = $this->smtp->recipient($to[0], $this->dsn) ? $badRcpt + 1 : $badRcpt;
            }
        }

        // Only send the DATA command if we have viable recipients
        if ((\count($this->to) + \count($this->cc) + \count($this->bcc) > $badRcpt)
            && !$this->smtp->data($header . $mail->body, self::MAX_LINE_LENGTH)
        ) {
            return false;
        }

        //$transactinoId = $this->smtp->getLastTransactionId();

        if ($this->keepAlive) {
            $this->smtp->reset();
        } else {
            $this->smtp->quit();
            $this->smtp->close();
        }

        return empty($badRcpt);
    }

    /**
     * Initiate a connection to an SMTP server.
     *
     * @param array $options An array of options compatible with stream_context_create()
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function smtpConnect(array $options = null) : bool
    {
        if ($this->smtp === null) {
            $this->smtp = new Smtp();
        }

        if ($this->smtp->isConnected()) {
            return true;
        }

        if ($options === null) {
            $options = $this->smtpOptions;
        }

        $this->smtp->timeout = $this->timeout;
        $this->smtp->doVerp  = $this->useVerp;

        $hosts = \explode(';', $this->host);
        foreach ($hosts as $hostentry) {
            $hostinfo = [];
            if (!\preg_match(
                    '/^(?:(ssl|tls):\/\/)?(.+?)(?::(\d+))?$/',
                    \trim($hostentry),
                    $hostinfo
                )
            ) {
                // Not a valid host entry
                continue;
            }

            // $hostinfo[1]: optional ssl or tls prefix
            // $hostinfo[2]: the hostname
            // $hostinfo[3]: optional port number

            //Check the host name is a valid name or IP address
            if (!Hostname::isValid($hostinfo[2])) {
                continue;
            }

            $prefix = '';
            $secure = $this->encryption;
            $tls    = ($this->encryption === EncryptionType::TLS);

            if ($hostinfo[1] === 'ssl' || ($hostinfo[1] === '' && $this->encryption === EncryptionType::SMTPS)) {
                $prefix = 'ssl://';
                $tls    = false;
                $secure = EncryptionType::SMTPS;
            } elseif ('tls' === $hostinfo[1]) {
                $tls    = true;
                $secure = EncryptionType::TLS;
            }

            //Do we need the OpenSSL extension?
            $sslExt = \defined('OPENSSL_ALGO_SHA256');
            if (($secure === EncryptionType::TLS || $secure === EncryptionType::SMTPS)
                && !$sslExt
            ) {
                return false;
            }

            $host = $hostinfo[2];
            $port = $this->port;

            if (isset($hostinfo[3])
                && \is_numeric($hostinfo[3])
                && $hostinfo[3] > 0 && $hostinfo[3] < 65536
            ) {
                $port = (int) $hostinfo[3];
            }

            if ($this->smtp->connect($prefix . $host, $port, $this->timeout, $options)) {
                $hello = !empty($this->helo) ? $this->helo : SystemUtils::getHostname();

                $this->smtp->hello($hello);
                $tls = $this->useAutoTLS && $sslExt && 'ssl' !== $secure && $this->smtp->getServerExt('STARTTLS')
                    ? true : $tls;

                $this->smtp->hello($hello);

                //Automatically enable TLS encryption
                $tls = $this->useAutoTLS && $sslExt && $secure !== EncryptionType::SMTPS && $this->smtp->getServerExt('STARTTLS')
                    ? true : $tls;

                if ($tls) {
                    if (!$this->smtp->startTLS()) {
                        return false;
                    }

                    // Resend EHLO
                    $this->smtp->hello($hello);
                }

                return !($this->useSMTPAuth
                        && !$this->smtp->authenticate($this->username, $this->password, $this->authType, $this->oauth)
                    );
            }
        }

        // If we get here, all connection attempts have failed
        $this->smtp->close();

        return false;
    }

    /**
     * Close SMTP
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function smtpClose() : void
    {
        if ($this->smtp !== null && $this->smtp->isConnected()) {
            $this->smtp->quit();
            $this->smtp->close();
        }
    }
}
