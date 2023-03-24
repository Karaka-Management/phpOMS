<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 *
 * Extended based on:
 * GLGPL 2.1 License
 * (c) 2012 - 2015 Marcus Bointon, 2010 - 2012 Jim Jagielski, 2004 - 2009 Andy Prevost
 * (c) PHPMailer
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\System\CharsetType;
use phpOMS\System\File\FileUtils;
use phpOMS\System\MimeType;
use phpOMS\System\SystemUtils;
use phpOMS\Utils\MbStringUtils;
use phpOMS\Validation\Network\Email as EmailValidator;

/**
 * Mail class.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Email implements MessageInterface
{
    /**
     * Mailer name.
     *
     * @var string
     * @since 1.0.0
     */
    public const XMAILER = 'phpOMS';

    /**
     * The maximum line length supported by mail().
     *
     * @var int
     * @since 1.0.0
     */
    public const MAIL_MAX_LINE_LENGTH = 63;

    /**
     * The maximum line length allowed by RFC 2822 section 2.1.1.
     *
     * @var int
     * @since 1.0.0
     */
    public const MAX_LINE_LENGTH = 998;

    /**
     * The lower maximum line length allowed by RFC 2822 section 2.1.1.
     *
     * @var int
     * @since 1.0.0
     */
    public const STD_LINE_LENGTH = 76;

    /**
     * Folding White Space.
     *
     * @var string
     * @since 1.0.0
     */
    public const FWS = ' ';

    /**
     * SMTP RFC standard line ending
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $LE = "\r\n";

    /**
     * Message id.
     *
     * Format <id@domain>. If empty this is automatically generated.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $messageId = '';

    /**
     * Unique ID used for message ID and boundaries.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $uniqueid = '';

    /**
     * Hostname coming from the mail handler.
     *
     * @var string
     * @since 1.0.0
     */
    public string $hostname = '';

    /**
     * Mailer for sending message
     *
     * @var string
     * @since 1.0.0
     */
    protected string $mailer = SubmitType::MAIL;

    /**
     * Mail from.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $from = [];

    /**
     * Return path/bounce address
     *
     * @var string
     * @since 1.0.0
     */
    public string $sender = '';

    /**
     * Confirm address.
     *
     * @var string
     */
    public string $confirmationAddress = '';

    /**
     * Mail to.
     *
     * @var array
     * @since 1.0.0
     */
    public array $to = [];

    /**
     * Mail subject.
     *
     * @var string
     * @since 1.0.0
     */
    public string $subject = '';

    /**
     * Mail cc.
     *
     * @var array
     * @since 1.0.0
     */
    public array $cc = [];

    /**
     * Mail bcc.
     *
     * @var array
     * @since 1.0.0
     */
    public array $bcc = [];

    /**
     * The array of reply-to names and addresses.
     *
     * @var array
     * @since 1.0.0
     */
    public array $replyTo = [];

    /**
     * Mail attachments.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $attachment = [];

    /**
     * Mail body.
     *
     * @var string
     * @since 1.0.0
     */
    public string $body = '';

    /**
     * Mail alt.
     *
     * @var string
     * @since 1.0.0
     */
    public string $bodyAlt = '';

    /**
     * Ical body.
     *
     * @var string
     * @since 1.0.0
     */
    public string $ical = '';

    /**
     * Mail mime.
     *
     * @var string
     * @since 1.0.0
     */
    public string $bodyMime = '';

    /**
     * The array of MIME boundary strings.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $boundary = [];

    /**
     * Mail header.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $header = '';

    /**
     * Mail header.
     *
     * @var string
     * @since 1.0.0
     */
    public string $headerMime = '';

    /**
     * The array of custom headers.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $customHeader = [];

    /**
     * Word wrap.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $wordWrap = 72;

    /**
     * Encoding.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $encoding = EncodingType::E_8BIT;

    /**
     * Mail content type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $contentType = MimeType::M_TXT;

    /**
     * Character set
     *
     * @var string
     * @since 1.0.0
     */
    public string $charset = CharsetType::ISO_8859_1;

    /**
     * Mail message type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $messageType = '';

    /**
     * Mail from.
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    public ?\DateTimeImmutable $messageDate = null;

    /**
     * Priority
     *
     * @var int
     * @since 1.0.0
     */
    public int $priority = 0;

    /**
     * The S/MIME certificate file path.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $signCertFile = '';

    /**
     * The S/MIME key file path.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $signKeyFile = '';

    /**
     * The optional S/MIME extra certificates ("CA Chain") file path.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $signExtracertFiles = '';

    /**
     * The S/MIME password for the key.
     * Used only if the key is encrypted.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $signKeyPass = '';

    /**
     * DKIM selector.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dkimSelector = '';

    /**
     * DKIM Identity.
     * Usually the email address used as the source of the email.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dkimIdentity = '';

    /**
     * DKIM passphrase.
     * Used if your key is encrypted.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dkimPass = '';

    /**
     * DKIM signing domain name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dkimDomain = '';

    /**
     * DKIM Copy header field values for diagnostic use.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $dkimCopyHeader = true;

    /**
     * DKIM Extra signing headers.
     *
     * @example ['List-Unsubscribe', 'List-Help']
     *
     * @var array
     * @since 1.0.0
     */
    public array $dkimHeaders = [];

    /**
     * DKIM private key file path.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dkimPrivatePath = '';

    /**
     * DKIM private key string.
     *
     * If set, takes precedence over `$dkimPrivatePath`.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dkimPrivateKey = '';

    /**
     * Set the From and FromName.
     *
     * @param string $address Email address
     * @param string $name    Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setFrom(string $address, string $name = '') : bool
    {
        $address = \trim($address);
        $name    = \trim(\preg_replace('/[\r\n]+/', '', $name));

        if (!EmailValidator::isValid($address)) {
            return false;
        }

        $this->from = [$address, $name];

        if (empty($this->sender)) {
            $this->sender = $address;
        }

        return true;
    }

    public function getFrom() : array
    {
        return $this->from;
    }

    /**
     * Sets message type to html or plain.
     *
     * @param bool $isHtml Html mode
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setHtml(bool $isHtml = true) : void
    {
        $this->contentType = $isHtml ? MimeType::M_HTML : MimeType::M_TEXT;
    }

    public function getContentType() : string
    {
        return $this->contentType;
    }

    public function isHtml() : bool
    {
        return $this->contentType === MimeType::M_HTML;
    }

    /**
     * Add a "To" address.
     *
     * @param string $address Email address
     * @param string $name    Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addTo(string $address, string $name = '') : bool
    {
        if (!EmailValidator::isValid($address)) {
            return false;
        }

        $this->to[$address] = [$address, $name];

        return true;
    }

    /**
     * Add a "CC" address.
     *
     * @param string $address Email address
     * @param string $name    Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addCC(string $address, string $name = '') : bool
    {
        if (!EmailValidator::isValid($address)) {
            return false;
        }

        $this->cc[$address] = [$address, $name];

        return true;
    }

    /**
     * Add a "BCC" address.
     *
     * @param string $address Email address
     * @param string $name    Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addBCC(string $address, string $name = '') : bool
    {
        if (!EmailValidator::isValid($address)) {
            return false;
        }

        $this->bcc[$address] = [$address, $name];

        return true;
    }

    /**
     * Add a "Reply-To" address.
     *
     * @param string $address Email address
     * @param string $name    Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addReplyTo(string $address, string $name = '') : bool
    {
        if (!EmailValidator::isValid($address)) {
            return false;
        }

        $this->replyTo[$address] = [$address, $name];

        return true;
    }

    /**
     * Parse and validate a string containing one or more RFC822-style comma-separated email addresses
     * of the form "display name <address>" into an array of name/address pairs.
     *
     * @param string $addrstr Address line
     * @param bool   $useImap Use imap for parsing
     * @param string $charset Charset for email
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function parseAddresses(string $addrstr, bool $useimap = true, string $charset = CharsetType::ISO_8859_1) : array
    {
        $addresses = [];
        if ($useimap && \function_exists('imap_rfc822_parse_adrlist')) {
            $list = \imap_rfc822_parse_adrlist($addrstr, '');
            foreach ($list as $address) {
                if (($address->host !== '.SYNTAX-ERROR.')
                    && EmailValidator::isValid($address->mailbox . '@' . $address->host)
                ) {
                    if (\property_exists($address, 'personal')
                        && \preg_match('/^=\?.*\?=$/s', $address->personal)
                    ) {
                        $origCharset = \mb_internal_encoding();
                        \mb_internal_encoding($charset);
                        $address->personal = \str_replace('_', '=20', $address->personal);
                        $address->personal = \mb_decode_mimeheader($address->personal);
                        \mb_internal_encoding($origCharset);
                    }

                    $addresses[] = [
                        'name'    => (\property_exists($address, 'personal') ? $address->personal : ''),
                        'address' => $address->mailbox . '@' . $address->host,
                    ];
                }
            }

            return $addresses;
        }

        $list = \explode(',', $addrstr);
        foreach ($list as $address) {
            $address = \trim($address);
            if (\strpos($address, '<') === false) {
                if (EmailValidator::isValid($address)) {
                    $addresses[] = [
                        'name'    => '',
                        'address' => $address,
                    ];
                }
            } else {
                $addr  = \explode('<', $address);
                $email = \trim(\str_replace('>', '', $addr[1]));

                if (EmailValidator::isValid($email)) {
                    $addresses[] = [
                        'name'    => \trim($addr[0], '\'" '),
                        'address' => $email,
                    ];
                }
            }
        }

        return $addresses;
    }

    /**
     * Pre-send preparations
     *
     * @param string $mailer Mailer tool
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function preSend(string $mailer) : bool
    {
        $this->header = '';
        $this->mailer = $mailer;

        if (\count($this->to) + \count($this->cc) + \count($this->bcc) < 1) {
            return false;
        }

        if (!empty($this->bodyAlt)) {
            $this->contentType = MimeType::M_ALT;
        }

        $this->setMessageType();

        $this->headerMime = '';
        $this->bodyMime   = $this->createBody();

        $tempheaders       = $this->headerMime;
        $this->headerMime  = $this->createHeader();
        $this->headerMime .= $tempheaders;

        if ($this->mailer === SubmitType::MAIL) {
            $this->header .= \count($this->to) > 0
                ? $this->createAddressList('To', $this->to)
                : 'Subject: undisclosed-recipients:;' . self::$LE;

            $this->header .= 'Subject: ' . $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $this->subject))) . self::$LE;
        }

        // Sign with DKIM if enabled
        if (!empty($this->dkimDomain)
            && !empty($this->dkimSelector)
            && (!empty($this->dkimPrivateKey)
                || (!empty($this->dkimPrivatePath)
                    && FileUtils::isPermittedPath($this->dkimPrivatePath)
                    && \is_file($this->dkimPrivatePath)
                )
            )
        ) {
            $headerDkim = $this->dkimAdd(
                $this->headerMime . $this->header,
                $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $this->subject))),
                $this->bodyMime
            );

            $this->headerMime = \rtrim($this->headerMime, " \r\n\t") . self::$LE .
                self::normalizeBreaks($headerDkim, self::$LE) . self::$LE;
        }

        return true;
    }

    /**
     * Assemble message headers.
     *
     * @return string The assembled headers
     *
     * @since 1.0.0
     */
    private function createHeader() : string
    {
        $result  = '';
        $result .= 'Date : ' . ($this->messageDate === null
                ? (new \DateTime('now'))->format('D, j M Y H:i:s O')
                : $this->messageDate->format('D, j M Y H:i:s O'))
            . self::$LE;

        if ($this->mailer !== SubmitType::MAIL) {
            $result .= \count($this->to) > 0
                ? $this->addrAppend('To', $this->to)
                : 'To: undisclosed-recipients:;' . self::$LE;
        }

        $result .= $this->addrAppend('From', [$this->from]);

        // sendmail and mail() extract Cc from the header before sending
        if (\count($this->cc) > 0) {
            $result .= $this->addrAppend('Cc', $this->cc);
        }

        // sendmail and mail() extract Bcc from the header before sending
        if (($this->mailer === SubmitType::MAIL
                || $this->mailer === SubmitType::SENDMAIL
                || $this->mailer === SubmitType::QMAIL)
            && \count($this->bcc) > 0
        ) {
            $result .= $this->addrAppend('Bcc', $this->bcc);
        }

        if (\count($this->replyTo) > 0) {
            $result .= $this->addrAppend('Reply-To', $this->replyTo);
        }

        // mail() sets the subject itself
        if ($this->mailer !== SubmitType::MAIL) {
            $result .= 'Subject: ' . $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $this->subject))) . self::$LE;
        }

        $this->hostname = empty($this->hostname) ? SystemUtils::getHostname() : $this->hostname;

        // Only allow a custom message Id if it conforms to RFC 5322 section 3.6.4
        // https://tools.ietf.org/html/rfc5322#section-3.6.4
        $this->messageId = $this->messageId !== ''
            && \preg_match('/^<((([a-z\d!#$%&\'*+\/=?^_`{|}~-]+(\.[a-z\d!#$%&\'*+\/=?^_`{|}~-]+)*)' .
        '|("(([\x01-\x08\x0B\x0C\x0E-\x1F\x7F]|[\x21\x23-\x5B\x5D-\x7E])' .
        '|(\\[\x01-\x09\x0B\x0C\x0E-\x7F]))*"))@(([a-z\d!#$%&\'*+\/=?^_`{|}~-]+' .
        '(\.[a-z\d!#$%&\'*+\/=?^_`{|}~-]+)*)|(\[(([\x01-\x08\x0B\x0C\x0E-\x1F\x7F]' .
        '|[\x21-\x5A\x5E-\x7E])|(\\[\x01-\x09\x0B\x0C\x0E-\x7F]))*\])))>$/Di', $this->messageId)
            ? $this->messageId
            : \sprintf('<%s@%s>', $this->uniqueid, $this->hostname);

        $result .= 'Message-ID: ' . $this->messageId . self::$LE;

        if ($this->priority > 0) {
            $result .= 'X-Priority: ' . $this->priority . self::$LE;
        }

        $result .= 'X-Mailer: ' . self::XMAILER . self::$LE;

        if ($this->confirmationAddress !== '') {
            $result .= 'Disposition-Notification-To: ' . '<' . $this->confirmationAddress . '>' . self::$LE;
        }

        // Add custom headers
        foreach ($this->customHeader as $header) {
            $result .= \trim($header[0]) . ': ' . $this->encodeHeader(\trim($header[1])) . self::$LE;
        }

        if (empty($this->signKeyFile)) {
            $result .= 'MIME-Version: 1.0' . self::$LE;
            $result .= $this->getMailMime();
        }

        return $result;
    }

    /**
     * Create recipient headers.
     *
     * @param string $type Address type
     * @param array  $addr Address 0 = address, 1 = name ['joe@example.com', 'Joe User']
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function addrAppend(string $type, array $addr) : string
    {
        $addresses = [];
        foreach ($addr as $address) {
            $addresses[] = $this->addrFormat($address);
        }

        return $type . ': ' . \implode(', ', $addresses) . self::$LE;
    }

    /**
     * Format an address for use in a message header.
     *
     * @param array $addr Address 0 = address, 1 = name ['joe@example.com', 'Joe User']
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function addrFormat(array $addr) : string
    {
        if (empty($addr[1])) {
            return \trim(\str_replace(["\r", "\n"], '', $addr[0]));
        }

        return $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $addr[1])), 'phrase') .
            ' <' . \trim(\str_replace(["\r", "\n"], '', $addr[0])) . '>';
    }

    /**
     * Get the message MIME type headers.
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function getMailMime() : string
    {
        $result      = '';
        $isMultipart = true;

        switch ($this->messageType) {
            case 'inline':
                $result .= 'Content-Type: ' . MimeType::M_RELATED . ';' . self::$LE;
                $result .= ' boundary="' . $this->boundary[1] . '"' . self::$LE;
                break;
            case 'attach':
            case 'inline_attach':
            case 'alt_attach':
            case 'alt_inline_attach':
                $result .= 'Content-Type: ' . MimeType::M_MIXED . ';' . self::$LE;
                $result .= ' boundary="' . $this->boundary[1] . '"' . self::$LE;
                break;
            case 'alt':
            case 'alt_inline':
                $result .= 'Content-Type: ' . MimeType::M_ALT . ';' . self::$LE;
                $result .= ' boundary="' . $this->boundary[1] . '"' . self::$LE;
                break;
            default:
                // Catches case 'plain': and case '':
                $result     .= 'Content-Type: ' . $this->contentType . '; charset=' . $this->charset . self::$LE;
                $isMultipart = false;
                break;
        }

        // RFC1341 part 5 says 7bit is assumed if not specified
        if ($this->encoding === EncodingType::E_7BIT) {
            return $result;
        }

        // RFC 2045 section 6.4 says multipart MIME parts may only use 7bit, 8bit or binary CTE
        if ($isMultipart) {
            if ($this->encoding === EncodingType::E_8BIT) {
                $result .= 'Content-Transfer-Encoding: ' . EncodingType::E_8BIT . self::$LE;
            }

            // quoted-printable and base64 are 7bit compatible
        } else {
            $result .= 'Content-Transfer-Encoding: ' . $this->encoding . self::$LE;
        }

        return $result;
    }

    /**
     * Converts IDN in given email address to its ASCII form
     *
     * @param string $charset Charset
     * @param string $address Email address
     *
     * @return string The encoded address in ASCII form
     *
     * @since 1.0.0
     */
    private function punyencodeAddress(string $charset, string $address) : string
    {
        if (empty($charset) || !EmailValidator::isValid($address)) {
            return $address;
        }

        $pos    = \strrpos($address, '@');
        $domain = \substr($address, ++$pos);

        if (!((bool) \preg_match('/[\x80-\xFF]/', $domain)) || !\mb_check_encoding($domain, $charset)) {
            return $address;
        }

        $domain = \mb_convert_encoding($domain, 'UTF-8', $charset);

        $errorcode = 0;
        if (\defined('INTL_IDNA_VARIANT_UTS46')) {
            $punycode = \idn_to_ascii(
                $domain,
                \IDNA_DEFAULT | \IDNA_USE_STD3_RULES | \IDNA_CHECK_BIDI | \IDNA_CHECK_CONTEXTJ | \IDNA_NONTRANSITIONAL_TO_ASCII,
                \INTL_IDNA_VARIANT_UTS46);
        } else {
            $punycode = \idn_to_ascii($domain, $errorcode);
        }

        if ($punycode !== false) {
            return \substr($address, 0, $pos) . $punycode;
        }

        return $address;
    }

    /**
     * Create a unique ID to use for boundaries.
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function generateId() : string
    {
        $len   = 32; //32 bytes = 256 bits
        $bytes = '';
        $bytes = \random_bytes($len);

        if ($bytes === '') {
            $bytes = \hash('sha256', \uniqid((string) \mt_rand(), true), true); // @codeCoverageIgnore
        }

        return \str_replace(['=', '+', '/'], '', \base64_encode(\hash('sha256', $bytes, true)));
    }

    /**
     * Assemble the message body.
     *
     * @return string Empty on failure
     *
     * @since 1.0.0
     */
    public function createBody() : string
    {
        $body           = '';
        $this->uniqueid = $this->generateId();

        $this->boundary[1] = 'b1=_' . $this->uniqueid;
        $this->boundary[2] = 'b2=_' . $this->uniqueid;
        $this->boundary[3] = 'b3=_' . $this->uniqueid;

        if (!empty($this->signKeyFile)) {
            $body .= $this->getMailMime() . self::$LE;
        }

        $this->setWordWrap();

        $bodyEncoding = $this->encoding;
        $bodyCharSet  = $this->charset;

        // Can we do a 7-bit downgrade?
        if ($bodyEncoding === EncodingType::E_8BIT && !((bool) \preg_match('/[\x80-\xFF]/', $this->body))) {
            $bodyEncoding = EncodingType::E_7BIT;

            //All ISO 8859, Windows codepage and UTF-8 charsets are ascii compatible up to 7-bit
            $bodyCharSet = CharsetType::ASCII;
        }

        // If lines are too long, and we're not already using an encoding that will shorten them,
        // change to quoted-printable transfer encoding for the body part only
        if ($this->encoding !== EncodingType::E_BASE64
            && ((bool) \preg_match('/^(.{' . (self::MAX_LINE_LENGTH + \strlen(self::$LE)) . ',})/m', $this->body))
        ) {
            $bodyEncoding = EncodingType::E_QUOTED;
        }

        $altBodyEncoding = $this->encoding;
        $altBodyCharSet  = $this->charset;

        //Can we do a 7-bit downgrade?
        if ($altBodyEncoding === EncodingType::E_8BIT && !((bool) \preg_match('/[\x80-\xFF]/', $this->bodyAlt))) {
            $altBodyEncoding = EncodingType::E_7BIT;

            //All ISO 8859, Windows codepage and UTF-8 charsets are ascii compatible up to 7-bit
            $altBodyCharSet = CharsetType::ASCII;
        }

        //If lines are too long, and we're not already using an encoding that will shorten them,
        //change to quoted-printable transfer encoding for the alt body part only
        if ($altBodyEncoding !== EncodingType::E_BASE64
            && ((bool) \preg_match('/^(.{' . (self::MAX_LINE_LENGTH + \strlen(self::$LE)) . ',})/m', $this->bodyAlt))
        ) {
            $altBodyEncoding = EncodingType::E_QUOTED;
        }

        //Use this as a preamble in all multipart message types
        $mimePre = '';
        switch ($this->messageType) {
            case 'inline':
                $body .= $mimePre;
                $body .= $this->getBoundary($this->boundary[1], $bodyCharSet, '', $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;
                $body .= $this->attachAll('inline', $this->boundary[1]);
                break;
            case 'attach':
                $body .= $mimePre;
                $body .= $this->getBoundary($this->boundary[1], $bodyCharSet, '', $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            case 'inline_attach':
                $body .= $mimePre;
                $body .= '--' . $this->boundary[1] . self::$LE;
                $body .= 'Content-Type: ' . MimeType::M_RELATED . ';' . self::$LE;
                $body .= ' boundary="' . $this->boundary[2] . '";' . self::$LE;
                $body .= ' type="' . MimeType::M_HTML . '"' . self::$LE . self::$LE;
                $body .= $this->getBoundary($this->boundary[2], $bodyCharSet, '', $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;
                $body .= $this->attachAll('inline', $this->boundary[2]) . self::$LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            case 'alt':
                $body .= $mimePre;
                $body .= $this->getBoundary($this->boundary[1], $altBodyCharSet, MimeType::M_TEXT, $altBodyEncoding);
                $body .= $this->encodeString($this->bodyAlt, $altBodyEncoding) . self::$LE;
                $body .= $this->getBoundary($this->boundary[1], $bodyCharSet, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;

                if (!empty($this->ical)) {
                    $method  = ICALMethodType::REQUEST;
                    $methods = ICALMethodType::getConstants();

                    foreach ($methods as $imethod) {
                        if (\stripos($this->ical, 'METHOD:' . $imethod) !== false) {
                            $method = $imethod;
                            break;
                        }
                    }

                    $body .= $this->getBoundary($this->boundary[1], '', MimeType::M_ICS . '; method=' . $method, '');
                    $body .= $this->encodeString($this->ical, $this->encoding) . self::$LE;
                }

                $body .= self::$LE . '--' . $this->boundary[1] . '--' . self::$LE;
                break;
            case 'alt_inline':
                $body .= $mimePre;
                $body .= $this->getBoundary($this->boundary[1], $altBodyCharSet, MimeType::M_TEXT, $altBodyEncoding);
                $body .= $this->encodeString($this->bodyAlt, $altBodyEncoding) . self::$LE;
                $body .= '--' . $this->boundary[1] . self::$LE;
                $body .= 'Content-Type: ' . MimeType::M_RELATED . ';' . self::$LE;
                $body .= ' boundary="' . $this->boundary[2] . '";' . self::$LE;
                $body .= ' type="' . MimeType::M_HTML . '"' . self::$LE . self::$LE;
                $body .= $this->getBoundary($this->boundary[2], $bodyCharSet, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;
                $body .= $this->attachAll('inline', $this->boundary[2]) . self::$LE;
                $body .= self::$LE . '--' . $this->boundary[1] . '--' . self::$LE;
                break;
            case 'alt_attach':
                $body .= $mimePre;
                $body .= '--' . $this->boundary[1] . self::$LE;
                $body .= 'Content-Type: ' . MimeType::M_ALT . ';' . self::$LE;
                $body .= ' boundary="' . $this->boundary[2] . '"' . self::$LE . self::$LE;
                $body .= $this->getBoundary($this->boundary[2], $altBodyCharSet, MimeType::M_TEXT, $altBodyEncoding);
                $body .= $this->encodeString($this->bodyAlt, $altBodyEncoding) .  self::$LE;
                $body .= $this->getBoundary($this->boundary[2], $bodyCharSet, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;

                if (!empty($this->ical)) {
                    $method  = ICALMethodType::REQUEST;
                    $methods = ICALMethodType::getConstants();

                    foreach ($methods as $imethod) {
                        if (\stripos($this->ical, 'METHOD:' . $imethod) !== false) {
                            $method = $imethod;
                            break;
                        }
                    }

                    $body .= $this->getBoundary($this->boundary[2], '', MimeType::M_ICS . '; method=' . $method, '');
                    $body .= $this->encodeString($this->ical, $this->encoding);
                }

                $body .= self::$LE . '--' . $this->boundary[2] . '--' . self::$LE . self::$LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            case 'alt_inline_attach':
                $body .= $mimePre;
                $body .= '--' . $this->boundary[1] . self::$LE;
                $body .= 'Content-Type: ' . MimeType::M_ALT . ';' . self::$LE;
                $body .= ' boundary="' . $this->boundary[2] . '"' . self::$LE;
                $body .= $this->getBoundary($this->boundary[2], $altBodyCharSet, MimeType::M_TEXT, $altBodyEncoding);
                $body .= $this->encodeString($this->bodyAlt, $altBodyEncoding) . self::$LE;
                $body .= '--' . $this->boundary[2] . self::$LE;
                $body .= 'Content-Type: ' . MimeType::M_RELATED . ';' . self::$LE;
                $body .= ' boundary="' . $this->boundary[3] . '";' . self::$LE;
                $body .= ' type="' . MimeType::M_HTML . '"' . self::$LE . self::$LE;
                $body .= $this->getBoundary($this->boundary[3], $bodyCharSet, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding) . self::$LE;
                $body .= $this->attachAll('inline', $this->boundary[3]) . self::$LE;
                $body .= self::$LE . '--' . $this->boundary[2] . '--' . self::$LE . self::$LE;
                $body .= $this->attachAll('attachment', $this->boundary[1]);
                break;
            default:
                // Catch case 'plain' and case '', applies to simple `text/plain` and `text/html` body content types
                $this->encoding = $bodyEncoding;
                $body          .= $this->encodeString($this->body, $this->encoding);
                break;
        }

        if (!empty($this->signKeyFile)) {
            if (!\defined('PKCS7_TEXT')) {
                return '';
            }

            $file   = \tempnam($tmpDir = \sys_get_temp_dir(), 'srcsign');
            $signed = \tempnam($tmpDir, 'mailsign');
            \file_put_contents($file, $body);

            try {
                // Workaround for PHP bug https://bugs.php.net/bug.php?id=69197
                $sign = empty($this->signExtracertFiles)
                    ? \openssl_pkcs7_sign(\realpath($file), $signed,
                            'file://' . \realpath($this->signCertFile),
                            ['file://' . \realpath($this->signKeyFile), $this->signKeyPass],
                            [],
                        )
                    : \openssl_pkcs7_sign(\realpath($file), $signed,
                            'file://' . \realpath($this->signCertFile),
                            ['file://' . \realpath($this->signKeyFile), $this->signKeyPass],
                            [],
                            \PKCS7_DETACHED,
                            $this->signExtracertFiles
                        );
            } catch (\Throwable $t) {
                $sign = false;
            }

            \unlink($file);
            if ($sign === false) {
                \unlink($signed);
                return '';
            }

            $body = \file_get_contents($signed);
            \unlink($signed);

            //The message returned by openssl contains both headers and body, so need to split them up
            $parts             = \explode("\n\n", $body, 2);
            $this->headerMime .= $parts[0] . self::$LE . self::$LE;
            $body              = $parts[1];
        }

        return $body;
    }

    /**
     * Return the start of a message boundary.
     *
     * @param string $boundary    Boundary
     * @param string $charset     Charset
     * @param string $contentType Content type
     * @param string $encoding    Concoding
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function getBoundary(string $boundary, string $charset, string $contentType, string $encoding) : string
    {
        $result = '';
        if ($charset === '') {
            $charset = $this->charset;
        }

        if ($contentType === '') {
            $contentType = $this->contentType;
        }

        if ($encoding === '') {
            $encoding = $this->encoding;
        }

        $result .= '--' . $boundary . self::$LE;
        $result .= \sprintf('Content-Type: %s; charset=%s', $contentType, $charset);
        $result .= self::$LE;

        // RFC1341 part 5 says 7bit is assumed if not specified
        if ($encoding !== EncodingType::E_7BIT) {
            $result .= 'Content-Transfer-Encoding: ' . $encoding . self::$LE;
        }

        return $result . self::$LE;
    }

    /**
     * Attach all file, string, and binary attachments to the message.
     *
     * @param string $dispositionType Disposition type
     * @param string $boundary        Boundary string
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function attachAll(string $dispositionType, string $boundary) : string
    {
        $mime    = [];
        $cidUniq = [];
        $incl    = [];

        $attachments = $this->getAttachments();
        foreach ($attachments as $attachment) {
            if ($attachment[6] !== $dispositionType) {
                continue;
            }

            $bString = $attachment[5];
            $string  = $bString ? $attachment[0] : '';
            $path    = !$bString ? $attachment[0] : '';

            $inclHash = \hash('sha256', \serialize($attachment));
            if (\in_array($inclHash, $incl, true)) {
                continue;
            }

            $incl[]      = $inclHash;
            $name        = $attachment[2];
            $encoding    = $attachment[3];
            $type        = $attachment[4];
            $disposition = $attachment[6];
            $cid         = $attachment[7];

            if ($disposition === 'inline' && isset($cidUniq[$cid])) {
                continue;
            }

            $cidUniq[$cid] = true;
            $mime[]        = \sprintf('--%s%s', $boundary, self::$LE);

            //Only include a filename property if we have one
            $mime[] = !empty($name)
                ? \sprintf('Content-Type: %s; name=%s%s',
                        $type,
                        self::quotedString($this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $name)))),
                        self::$LE
                    )
                : \sprintf('Content-Type: %s%s',
                    $type,
                    self::$LE
                );

            // RFC1341 part 5 says 7bit is assumed if not specified
            if ($encoding !== EncodingType::E_7BIT) {
                $mime[] = \sprintf('Content-Transfer-Encoding: %s%s', $encoding, self::$LE);
            }

            //Only set Content-IDs on inline attachments
            if ((string) $cid !== '' && $disposition === 'inline') {
                $mime[] = 'Content-ID: <' . $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $cid))) . '>' . self::$LE;
            }

            // Allow for bypassing the Content-Disposition header
            if (!empty($disposition)) {
                $encodedName = $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $name)));
                    $mime[]  = !empty($encodedName)
                        ? \sprintf('Content-Disposition: %s; filename=%s%s',
                                $disposition,
                                self::quotedString($encodedName),
                                self::$LE . self::$LE
                            )
                        : \sprintf('Content-Disposition: %s%s', $disposition, self::$LE . self::$LE);
            } else {
                $mime[] = self::$LE;
            }

            // Encode as string attachment
            $mime[] = $bString
                ? $this->encodeString($string, $encoding)
                : $this->encodeFile($path, $encoding);

            $mime[] = self::$LE;
        }

        $mime[] = \sprintf('--%s--%s', $boundary, self::$LE);

        return \implode('', $mime);
    }

    /**
     * If a string contains any "special" characters, double-quote the name,
     * and escape any double quotes with a backslash.
     *
     * @param string $str String to quote
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function quotedString(string $str) : string
    {
        if (\preg_match('/[ ()<>@,;:"\/\[\]?=]/', $str)) {
            return '"' . \str_replace('"', '\\"', $str) . '"';
        }

        return $str;
    }

    /**
     * Encode a file attachment in requested format.
     *
     * @param string $path     Path
     * @param string $encoding Encoding
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeFile(string $path, string $encoding = EncodingType::E_BASE64) : string
    {
        if (!FileUtils::isAccessible($path)) {
            return '';
        }

        $fileBuffer = \file_get_contents($path);
        if ($fileBuffer === false) {
            return ''; // @codeCoverageIgnore
        }

        $fileBuffer = $this->encodeString($fileBuffer, $encoding);

        return $fileBuffer;
    }

    /**
     * Encode a string in requested format.
     *
     * @param string $str      The text to encode
     * @param string $encoding Encoding
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeString(string $str, string $encoding = EncodingType::E_BASE64) : string
    {
        $encoded = '';
        switch (\strtolower($encoding)) {
            case EncodingType::E_BASE64:
                $encoded = \chunk_split(\base64_encode($str), self::STD_LINE_LENGTH, self::$LE);
                break;
            case EncodingType::E_7BIT:
            case EncodingType::E_8BIT:
                $encoded = self::normalizeBreaks($str, self::$LE);
                if (\substr($encoded, -(\strlen(self::$LE))) !== self::$LE) {
                    $encoded .= self::$LE;
                }

                break;
            case EncodingType::E_BINARY:
                $encoded = $str;
                break;
            case EncodingType::E_QUOTED:
                $encoded = self::normalizeBreaks(\quoted_printable_encode($str), self::$LE);
                break;
            default:
                return '';
        }

        return $encoded;
    }

    /**
     * Set message type based on content
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function setMessageType() : void
    {
        $type = [];
        if (!empty($this->bodyAlt)) {
            $type[] = 'alt';
        }

        if ($this->hasInlineImage()) {
            $type[] = 'inline';
        }

        if ($this->hasAttachment()) {
            $type[] = 'attach';
        }

        $this->messageType = \implode('_', $type);
        if ($this->messageType === '') {
            $this->messageType = 'plain';
        }
    }

    /**
     * Mail has inline image
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasInlineImage() : bool
    {
        foreach ($this->attachment as $attachment) {
            if ($attachment[6] === 'inline') {
                return true;
            }
        }

        return false;
    }

    /**
     * Mail has attachment
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasAttachment() : bool
    {
        foreach ($this->attachment as $attachment) {
            if ($attachment[6] === 'attachment') {
                return true;
            }
        }

        return false;
    }

    /**
     * Create address list
     *
     * @param string $type Address type
     * @param array  $addr Addresses
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function createAddressList(string $type, array $addr) : string
    {
        $addresses = [];
        foreach ($addr as $address) {
            $addresses[] = $this->addrFormat($address);
        }

        return $type . ': ' . \implode(', ', $addresses) . static::$LE;
    }

    /**
     * Apply word wrapping
     *
     * @return void
     *
     * @return 1.0.0
     */
    public function setWordWrap() : void
    {
        if ($this->wordWrap < 1) {
            return;
        }

        switch ($this->messageType) {
            case 'alt':
            case 'alt_inline':
            case 'alt_attach':
            case 'alt_inline_attach':
                $this->bodyAlt = $this->wrapText($this->bodyAlt, $this->wordWrap);
                break;
            default:
                $this->body = $this->wrapText($this->body, $this->wordWrap);
                break;
        }
    }

    /**
     * Word-wrap message.
     * Original written by philippe.
     *
     * @param string $message The message to wrap
     * @param int    $length  The line length to wrap to
     * @param bool   $qpMode  Use Quoted-Printable mode
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function wrapText(string $message, int $length, bool $qpMode = false) : string
    {
        $softBreak = $qpMode ? \sprintf(' =%s', self::$LE) : self::$LE;

        // Don't split multibyte characters
        $isUtf8  = \strtolower($this->charset) === CharsetType::UTF_8;
        $leLen   = \strlen(self::$LE);
        $crlfLen = \strlen(self::$LE);

        $message = self::normalizeBreaks($message, self::$LE);

        //Remove a trailing line break
        if (\substr($message, -$leLen) === self::$LE) {
            $message = \substr($message, 0, -$leLen);
        }

        //Split message into lines
        $lines = \explode(self::$LE, $message);

        $message = '';
        foreach ($lines as $line) {
            $words     = \explode(' ', $line);
            $buf       = '';
            $firstword = true;

            foreach ($words as $word) {
                if ($qpMode && \strlen($word) > $length) {
                    $spaceLeft = $length - \strlen($buf) - $crlfLen;

                    if (!$firstword) {
                        if ($spaceLeft > 20) {
                            $len = $spaceLeft;
                            if ($isUtf8) {
                                $len = MbStringUtils::utf8CharBoundary($word, $len);
                            } elseif (\substr($word, $len - 1, 1) === '=') {
                                --$len;
                            } elseif (\substr($word, $len - 2, 1) === '=') {
                                $len -= 2;
                            }

                            $part     = \substr($word, 0, $len);
                            $word     = \substr($word, $len);
                            $buf     .= ' ' . $part;
                            $message .= $buf . \sprintf('=%s', self::$LE);
                        } else {
                            $message .= $buf . $softBreak;
                        }

                        $buf = '';
                    }

                    while ($word !== '') {
                        if ($length <= 0) {
                            break;
                        }

                        $len = $length;
                        if ($isUtf8) {
                            $len = MbStringUtils::utf8CharBoundary($word, $len);
                        } elseif (\substr($word, $len - 1, 1) === '=') {
                            --$len;
                        } elseif (\substr($word, $len - 2, 1) === '=') {
                            $len -= 2;
                        }

                        $part = \substr($word, 0, $len);
                        $word = (string) \substr($word, $len);

                        if ($word !== '') {
                            $message .= $part . \sprintf('=%s', self::$LE);
                        } else {
                            $buf = $part;
                        }
                    }
                } else {
                    $bufO = $buf;
                    if (!$firstword) {
                        $buf .= ' ';
                    }

                    $buf .= $word;
                    if ($bufO !== '' && \strlen($buf) > $length) {
                        $message .= $bufO . $softBreak;
                        $buf      = $word;
                    }
                }

                $firstword = false;
            }

            $message .= $buf . self::$LE;
        }

        return $message;
    }

    /**
     * Encode a header value (not including its label) optimally.
     * Picks shortest of Q, B, or none. Result includes folding if needed.
     *
     * @param string $str      Header value
     * @param string $position Context
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function encodeHeader(string $str, string $position = 'text') : string
    {
        $matchcount = 0;
        switch (\strtolower($position)) {
            case 'phrase':
                if (!\preg_match('/[\200-\377]/', $str)) {
                    $encoded = \addcslashes($str, "\0..\37\177\\\"");

                    return $str === $encoded && !\preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str)
                        ? $encoded
                        : '"' . $encoded . '"';
                }

                $matchcount = \preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
                break;
            /* @noinspection PhpMissingBreakStatementInspection */
            case 'comment':
                $matchcount = \preg_match_all('/[()"]/', $str, $matches);
            case 'text':
            default:
                $matchcount += \preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
                break;
        }

        $charset = ((bool) \preg_match('/[\x80-\xFF]/', $str)) ? $this->charset : CharsetType::ASCII;

        // Q/B encoding adds 8 chars and the charset ("` =?<charset>?[QB]?<content>?=`").
        $overhead = 8 + \strlen($charset);
        $maxLen   = $this->mailer === SubmitType::MAIL
            ? self::MAIL_MAX_LINE_LENGTH - $overhead
            : self::MAX_LINE_LENGTH - $overhead;

        // Select the encoding that produces the shortest output and/or prevents corruption.
        if ($matchcount > \strlen($str) / 3) {
            // More than 1/3 of the content needs encoding, use B-encode.
            $encoding = 'B';
        } elseif ($matchcount > 0) {
            // Less than 1/3 of the content needs encoding, use Q-encode.
            $encoding = 'Q';
        } elseif (\strlen($str) > $maxLen) {
            // No encoding needed, but value exceeds max line length, use Q-encode to prevent corruption.
            $encoding = 'Q';
        } else {
            // No reformatting needed
            $encoding = '';
        }

        switch ($encoding) {
            case 'B':
                if (\strlen($str) > \mb_strlen($str, $this->charset)) {
                    $encoded = $this->base64EncodeWrapMB($str, "\n");
                } else {
                    $encoded = \base64_encode($str);
                    $maxLen -= $maxLen % 4;
                    $encoded = \trim(\chunk_split($encoded, $maxLen, "\n"));
                }
                $encoded = \preg_replace('/^(.*)$/m', ' =?' . $charset . '?' . $encoding . '?\\1?=', $encoded);
                break;
            case 'Q':
                $encoded = $this->encodeQ($str, $position);
                $encoded = $this->wrapText($encoded, $maxLen, true);
                $encoded = \str_replace('=' . self::$LE, "\n", \trim($encoded));
                $encoded = \preg_replace('/^(.*)$/m', ' =?' . $charset . '?' . $encoding . '?\\1?=', $encoded);
                break;
            default:
                return $str;
        }

        return \trim(self::normalizeBreaks($encoded, self::$LE));
    }

    /**
     * Encode a string using Q encoding.
     *
     * @param string $str      Text to encode
     * @param string $position Where the text is going to be used, see the RFC for what that means
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeQ(string $str, string $position = 'text') : string
    {
        $pattern = '';
        $encoded = \str_replace(["\r", "\n"], '', $str);

        switch (\strtolower($position)) {
            case 'phrase':
                $pattern = '^A-Za-z0-9!*+\/ -';
                break;
            case 'comment':
                $pattern = '\(\)"';
            case 'text':
            default:
                // Replace every high ascii, control, =, ? and _ characters
                $pattern = '\000-\011\013\014\016-\037\075\077\137\177-\377' . $pattern;
                break;
        }

        if (\preg_match_all("/[{$pattern}]/", $encoded, $matches) !== false) {
            return \str_replace(' ', '_', $encoded);
        }

        $matches = [];
        // If the string contains an '=', make sure it's the first thing we replace
        // so as to avoid double-encoding
        $eqkey = \array_search('=', $matches[0], true);
        if ($eqkey !== false) {
            unset($matches[0][$eqkey]);
            \array_unshift($matches[0], '=');
        }

        $unique = \array_unique($matches[0]);
        foreach ($unique as $char) {
            $encoded = \str_replace($char, '=' . \sprintf('%02X', \ord($char)), $encoded);
        }

        // Replace spaces with _ (more readable than =20)
        // RFC 2047 section 4.2(2)
        return \str_replace(' ', '_', $encoded);
    }

    /**
     * Encode and wrap long multibyte strings for mail headers
     *
     * @param string $str       Multi-byte text to wrap encode
     * @param string $linebreak string to use as linefeed/end-of-line
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function base64EncodeWrapMB(string $str, string $linebreak) : string
    {
        $start   = '=?' . $this->charset . '?B?';
        $end     = '?=';
        $encoded = '';

        $mbLength  = \mb_strlen($str, $this->charset);
        $length    = 75 - \strlen($start) - \strlen($end);
        $ratio     = $mbLength / \strlen($str);
        $avgLength = \floor($length * $ratio * .75);

        $offset = 0;
        for ($i = 0; $i < $mbLength; $i += $offset) {
            $lookBack = 0;

            do {
                $offset = $avgLength - $lookBack;
                $chunk  = \mb_substr($str, $i, $offset, $this->charset);
                $chunk  = \base64_encode($chunk);
                ++$lookBack;
            } while (\strlen($chunk) > $length);

            $encoded .= $chunk . $linebreak;
        }

        return \substr($encoded, 0, -\strlen($linebreak));
    }

    /**
     * Add an attachment from a path on the filesystem.
     *
     * @param string $path        Path
     * @param string $name        Overrides the attachment name
     * @param string $encoding    File encoding
     * @param string $type        Mime type; determined automatically from $path if not specified
     * @param string $disposition Disposition to use
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addAttachment(
        string $path,
        string $name = '',
        string $encoding = EncodingType::E_BASE64,
        string $type = '',
        string $disposition = 'attachment'
    ) : bool {
        if (!FileUtils::isAccessible($path)) {
            return false;
        }

        // Mime from file
        if ($type === '') {
            $type = MimeType::extensionToMime(FileUtils::mb_pathinfo($path, \PATHINFO_EXTENSION));
        }

        $filename = FileUtils::mb_pathinfo($path, \PATHINFO_BASENAME);
        if ($name === '') {
            $name = $filename;
        }

        $this->attachment[] = [
            0 => $path,
            1 => $filename,
            2 => $name,
            3 => $encoding,
            4 => $type,
            5 => false, // isStringAttachment
            6 => $disposition,
            7 => $name,
        ];

        return true;
    }

    /**
     * Return the array of attachments.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachment;
    }

    /**
     * Add a string or binary attachment (non-filesystem).
     *
     * @param string $string      String attachment data
     * @param string $filename    Name of the attachment
     * @param string $encoding    File encoding (see $encoding)
     * @param string $type        File extension (MIME) type
     * @param string $disposition Disposition to use
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addStringAttachment(
        string $string,
        string $filename,
        string $encoding = EncodingType::E_BASE64,
        string $type = '',
        string $disposition = 'attachment'
    ) : bool {
        // Mime from file
        if ($type === '') {
            $type = MimeType::extensionToMime(FileUtils::mb_pathinfo($filename, \PATHINFO_EXTENSION));
        }

        $this->attachment[] = [
            0 => $string,
            1 => $filename,
            2 => FileUtils::mb_pathinfo($filename, \PATHINFO_BASENAME),
            3 => $encoding,
            4 => $type,
            5 => true, // isStringAttachment
            6 => $disposition,
            7 => 0,
        ];

        return true;
    }

    /**
     * Add an embedded (inline) attachment from a file.
     * This can include images, sounds, and just about any other document type.
     *
     * @param string $path        Path to the attachment
     * @param string $cid         Content ID of the attachment
     * @param string $name        Overrides the attachment name
     * @param string $encoding    File encoding (see $encoding)
     * @param string $type        File MIME type
     * @param string $disposition Disposition to use
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addEmbeddedImage(
        string $path,
        string $cid,
        string $name = '',
        string $encoding = EncodingType::E_BASE64,
        string $type = '',
        string $disposition = 'inline'
    ) : bool {
        if (!FileUtils::isAccessible($path)) {
            return false;
        }

        // Mime from file
        if ($type === '') {
            $type = MimeType::extensionToMime(FileUtils::mb_pathinfo($path, \PATHINFO_EXTENSION));
        }

        $filename = FileUtils::mb_pathinfo($path, \PATHINFO_BASENAME);
        if ($name === '') {
            $name = $filename;
        }

        // Append to $attachment array
        $this->attachment[] = [
            0 => $path,
            1 => $filename,
            2 => $name,
            3 => $encoding,
            4 => $type,
            5 => false, // isStringAttachment
            6 => $disposition,
            7 => $cid,
        ];

        return true;
    }

    /**
     * Add an embedded stringified attachment.
     * This can include images, sounds, and just about any other document type.
     *
     * @param string $string      The attachment binary data
     * @param string $cid         Content ID of the attachment
     * @param string $name        A filename for the attachment. Should use extension.
     * @param string $encoding    File encoding (see $encoding), defaults to 'base64'
     * @param string $type        MIME type - will be used in preference to any automatically derived type
     * @param string $disposition Disposition to use
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addStringEmbeddedImage(
        string $string,
        string $cid,
        string $name = '',
        string $encoding = EncodingType::E_BASE64,
        string $type = '',
        string $disposition = 'inline'
    ) : bool {
        // Mime from file
        if ($type === '' && !empty($name)) {
            $type = MimeType::extensionToMime(FileUtils::mb_pathinfo($name, \PATHINFO_EXTENSION));
        }

        // Append to $attachment array
        $this->attachment[] = [
            0 => $string,
            1 => $name,
            2 => $name,
            3 => $encoding,
            4 => $type,
            5 => true, // isStringAttachment
            6 => $disposition,
            7 => $cid,
        ];

        return true;
    }

    /**
     * Check if an embedded attachment is present with this cid.
     *
     * @param string $cid Cid
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected function cidExists(string $cid) : bool
    {
        foreach ($this->attachment as $attachment) {
            if ($attachment[6] === 'inline' && $cid === $attachment[7]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a custom header.
     *
     * @param string      $name  Name
     * @param null|string $value Value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addCustomHeader(string $name, string $value = null) : bool
    {
        $name  = \trim($name);
        $value = \trim($value);

        if (empty($name) || \strpbrk($name . $value, "\r\n") !== false) {
            return false;
        }

        $this->customHeader[] = [$name, $value];

        return true;
    }

    /**
     * Returns all custom headers.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getCustomHeaders() : array
    {
        return $this->customHeader;
    }

    /**
     * Create a message body from an HTML string.
     *
     * $basedir is prepended when handling relative URLs, e.g. <img src="/images/a.png"> and must not be empty
     * will look for an image file in $basedir/images/a.png and convert it to inline.
     * If you don't provide a $basedir, relative paths will be left untouched (and thus probably break in email)
     * Converts data-uri images into embedded attachments.
     *
     * If you don't want to apply these transformations to your HTML, just set Body and AltBody directly.
     *
     * @param string        $message  HTML message string
     * @param string        $basedir  Absolute path to a base directory to prepend to relative paths to images
     * @param null|\Closure $advanced Internal or external text to html converter
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function msgHTML(string $message, string $basedir = '', \Closure $advanced = null)
    {
        \preg_match_all('/(?<!-)(src|background)=["\'](.*)["\']/Ui', $message, $images);

        if (isset($images[2])) {
            if (\strlen($basedir) > 1 && \substr($basedir, -1) !== '/') {
                $basedir .= '/';
            }

            foreach ($images[2] as $imgindex => $url) {
                // Convert data URIs into embedded images
                $match = [];
                if (\preg_match('#^data:(image/(?:jpe?g|gif|png));?(base64)?,(.+)#', $url, $match)) {
                    if (\count($match) === 4 && $match[2] === EncodingType::E_BASE64) {
                        $data = \base64_decode($match[3]);
                    } elseif ($match[2] === '') {
                        $data = \rawurldecode($match[3]);
                    } else {
                        continue;
                    }

                    $cid = \substr(\hash('sha256', $data), 0, 32) . '@phpoms.0'; // RFC2392 S 2
                    if (!$this->cidExists($cid)) {
                        $this->addStringEmbeddedImage($data, $cid, 'embed' . $imgindex, EncodingType::E_BASE64, $match[1]);
                    }

                    $message = \str_replace($images[0][$imgindex], $images[1][$imgindex] . '="cid:' . $cid . '"', $message);

                    continue;
                }

                if (!empty($basedir)
                    && (\strpos($url, '..') === false)
                    && \strpos($url, 'cid:') !== 0
                    && !\preg_match('#^[a-z][a-z0-9+.-]*:?//#i', $url)
                ) {
                    $filename  = FileUtils::mb_pathinfo($url, \PATHINFO_BASENAME);
                    $directory = \dirname($url);

                    if ($directory === '.') {
                        $directory = '';
                    }

                    // RFC2392 S 2
                    $cid = \substr(\hash('sha256', $url), 0, 32) . '@phpoms.0';
                    if (\strlen($basedir) > 1 && \substr($basedir, -1) !== '/') {
                        $basedir .= '/';
                    }

                    if (\strlen($directory) > 1 && \substr($directory, -1) !== '/') {
                        $directory .= '/';
                    }

                    if ($this->addEmbeddedImage(
                            $basedir . $directory . $filename,
                            $cid,
                            $filename,
                            EncodingType::E_BASE64,
                            MimeType::extensionToMime((string) FileUtils::mb_pathinfo($filename, \PATHINFO_EXTENSION))
                        )
                    ) {
                        $message = \preg_replace(
                            '/' . $images[1][$imgindex] . '=["\']' . \preg_quote($url, '/') . '["\']/Ui',
                            $images[1][$imgindex] . '="cid:' . $cid . '"',
                            $message
                        );
                    }
                }
            }
        }

        $this->contentType = MimeType::M_HTML;
        $this->body        = self::normalizeBreaks($message, self::$LE);
        $this->bodyAlt     = self::normalizeBreaks($this->html2text($message, $advanced), self::$LE);

        if (empty($this->bodyAlt)) {
            // @todo: localize
            $this->bodyAlt = 'This is an HTML-only message. To view it, activate HTML in your email application.' . self::$LE;
        }

        return $this->body;
    }

    /**
     * Normalize line breaks in a string.
     *
     * @param string $text
     * @param string $breaktype What kind of line break to use; defaults to self::$LE
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function normalizeBreaks(string $text, string $breaktype) : string
    {
        $text = \str_replace(["\r\n", "\r"], "\n", $text);

        if ($breaktype !== "\n") {
            $text = \str_replace("\n", $breaktype, $text);
        }

        return $text;
    }

    /**
     * Convert an HTML string into plain text.
     *
     * @param string        $html     The HTML text to convert
     * @param null|\Closure $advanced Internal or external text to html converter
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function html2text(string $html, \Closure $advanced = null) : string
    {
        if ($advanced !== null) {
            return $advanced($html);
        }

        return \html_entity_decode(
            \trim(\strip_tags(\preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/si', '', $html))),
            \ENT_QUOTES,
            $this->charset
        );
    }

    /**
     * Set the public and private key files and password for S/MIME signing.
     *
     * @param string $certFile       Certification file
     * @param string $keyFile        Key file
     * @param string $keyPass        Password for private key
     * @param string $extracertsFile Optional path to chain certificate
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function sign($certFile, $keyFile, $keyPass, $extracertsFile = '') : void
    {
        $this->signCertFile       = $certFile;
        $this->signKeyFile        = $keyFile;
        $this->signKeyPass        = $keyPass;
        $this->signExtracertFiles = $extracertsFile;
    }

    /**
     * Quoted-Printable-encode a DKIM header.
     *
     * @param string $txt Text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function dkimQP(string $txt) : string
    {
        $line = '';
        $len  = \strlen($txt);

        for ($i = 0; $i < $len; ++$i) {
            $ord   = \ord($txt[$i]);
            $line .= (($ord >= 0x21) && ($ord <= 0x3A)) || $ord === 0x3C || (($ord >= 0x3E) && ($ord <= 0x7E))
                ? $txt[$i]
                : '=' . \sprintf('%02X', $ord);
        }

        return $line;
    }

    /**
     * Generate a DKIM signature.
     *
     * @param string $signHeader
     *
     * @return string The DKIM signature value
     *
     * @since 1.0.0
     */
    public function dkimSign(string $signHeader) : string
    {
        if (!\defined('PKCS7_TEXT')) {
            return '';
        }

        $privKeyStr = !empty($this->dkimPrivateKey)
            ? $this->dkimPrivateKey
            : \file_get_contents($this->dkimPrivatePath);

        $privKey = $this->dkimPass !== ''
            ? \openssl_pkey_get_private($privKeyStr, $this->dkimPass)
            : \openssl_pkey_get_private($privKeyStr);

        if (\openssl_sign($signHeader, $signature, $privKey, 'sha256WithRSAEncryption')) {
            return \base64_encode($signature);
        }

        return '';
    }

    /**
     * Generate a DKIM canonicalization header.
     *
     * @param string $signHeader Header
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function dkimHeaderC(string $signHeader) : string
    {
        $signHeader = self::normalizeBreaks($signHeader, "\r\n");
        $signHeader = \preg_replace('/\r\n[ \t]+/', ' ', $signHeader);
        $lines      = \explode("\r\n", $signHeader);

        foreach ($lines as $key => $line) {
            if (\strpos($line, ':') === false) {
                continue;
            }

            list($heading, $value) = \explode(':', $line, 2);
            $heading               = \strtolower($heading);
            $value                 = \preg_replace('/[ \t]+/', ' ', $value);

            $lines[$key] = \trim($heading, " \t") . ':' . \trim($value, " \t");
        }

        return \implode("\r\n", $lines);
    }

    /**
     * Generate a DKIM canonicalization body.
     *
     * @param string $body Message Body
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function dkimBodyC(string $body) : string
    {
        if (empty($body)) {
            return "\r\n";
        }

        $body = self::normalizeBreaks($body, "\r\n");

        return \rtrim($body, " \r\n\t") . "\r\n";
    }

    /**
     * Create the DKIM header and body in a new message header.
     *
     * @param string $headersLine Header lines
     * @param string $subject     Subject
     * @param string $body        Body
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function dkimAdd(string $headersLine, string $subject, string $body) : string
    {
        $DKIMsignatureType    = 'rsa-sha256';
        $DKIMcanonicalization = 'relaxed/simple';
        $DKIMquery            = 'dns/txt';
        $DKIMtime             = \time();

        $autoSignHeaders = [
            'from',
            'to',
            'cc',
            'date',
            'subject',
            'reply-to',
            'message-id',
            'content-type',
            'mime-version',
            'x-mailer',
        ];

        if (\stripos($headersLine, 'Subject') === false) {
            $headersLine .= 'Subject: ' . $subject . self::$LE;
        }

        $headerLines        = \explode(self::$LE, $headersLine);
        $currentHeaderLabel = '';
        $currentHeaderValue = '';
        $parsedHeaders      = [];
        $headerLineIndex    = 0;
        $headerLineCount    = \count($headerLines);

        foreach ($headerLines as $headerLine) {
            $matches = [];
            if (\preg_match('/^([^ \t]*?)(?::[ \t]*)(.*)$/', $headerLine, $matches)) {
                if ($currentHeaderLabel !== '') {
                    $parsedHeaders[] = ['label' => $currentHeaderLabel, 'value' => $currentHeaderValue];
                }

                $currentHeaderLabel = $matches[1];
                $currentHeaderValue = $matches[2];
            } elseif (\preg_match('/^[ \t]+(.*)$/', $headerLine, $matches)) {
                $currentHeaderValue .= ' ' . $matches[1];
            }

            ++$headerLineIndex;

            if ($headerLineIndex >= $headerLineCount) {
                $parsedHeaders[] = ['label' => $currentHeaderLabel, 'value' => $currentHeaderValue];
            }
        }

        $copiedHeaders     = [];
        $headersToSignKeys = [];
        $headersToSign     = [];

        foreach ($parsedHeaders as $header) {
            if (\in_array(\strtolower($header['label']), $autoSignHeaders, true)) {
                $headersToSignKeys[] = $header['label'];
                $headersToSign[]     = $header['label'] . ': ' . $header['value'];

                if ($this->dkimCopyHeader) {
                    $copiedHeaders[] = $header['label'] . ':'
                        . \str_replace('|', '=7C', $this->dkimQP($header['value']));
                }

                continue;
            }

            if (\in_array($header['label'], $this->dkimHeaders, true)) {
                foreach ($this->customHeader as $customHeader) {
                    if ($customHeader[0] === $header['label']) {
                        $headersToSignKeys[] = $header['label'];
                        $headersToSign[]     = $header['label'] . ': ' . $header['value'];

                        if ($this->dkimCopyHeader) {
                            $copiedHeaders[] = $header['label'] . ':'
                                . \str_replace('|', '=7C', $this->dkimQP($header['value']));
                        }

                        continue 2;
                    }
                }
            }
        }

        $copiedHeaderFields = '';
        if ($this->dkimCopyHeader && \count($copiedHeaders) > 0) {
            $copiedHeaderFields = ' z=';
            $first              = true;

            foreach ($copiedHeaders as $copiedHeader) {
                if (!$first) {
                    $copiedHeaderFields .= self::$LE . ' |';
                }

                $copiedHeaderFields .= \strlen($copiedHeader) > self::STD_LINE_LENGTH - 3
                    ? \substr(
                            \chunk_split($copiedHeader, self::STD_LINE_LENGTH - 3, self::$LE . self::FWS),
                            0,
                            -\strlen(self::$LE . self::FWS)
                        )
                    : $copiedHeader;

                $first = false;
            }

            $copiedHeaderFields .= ';' . self::$LE;
        }

        $headerKeys   = ' h=' . \implode(':', $headersToSignKeys) . ';' . self::$LE;
        $headerValues = \implode(self::$LE, $headersToSign);
        $body         = $this->dkimBodyC($body);
        $DKIMb64      = \base64_encode(\pack('H*', \hash('sha256', $body)));
        $ident        = '';

        if ($this->dkimIdentity !== '') {
            $ident = ' i=' . $this->dkimIdentity . ';' . self::$LE;
        }

        $dkimSignatureHeader = 'DKIM-Signature: v=1;'
            . ' d=' . $this->dkimDomain . ';'
            . ' s=' . $this->dkimSelector . ';' . self::$LE
            . ' a=' . $DKIMsignatureType . ';'
            . ' q=' . $DKIMquery . ';'
            . ' t=' . $DKIMtime . ';'
            . ' c=' . $DKIMcanonicalization . ';' . self::$LE
            . $headerKeys . $ident . $copiedHeaderFields
            . ' bh=' . $DKIMb64 . ';' . self::$LE
            . ' b=';

        $canonicalizedHeaders = $this->dkimHeaderC(
            $headerValues . self::$LE . $dkimSignatureHeader
        );

        $signature = $this->dkimSign($canonicalizedHeaders);
        $signature = \trim(\chunk_split($signature, self::STD_LINE_LENGTH - 3, self::$LE . self::FWS));

        return self::normalizeBreaks($dkimSignatureHeader . $signature, self::$LE);
    }
}
