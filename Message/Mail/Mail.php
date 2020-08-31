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
 *
 * Extended based on:
 * GLGPL 2.1 License
 * © 2012 - 2015 Marcus Bointon, 2010 - 2012 Jim Jagielski, 2004 - 2009 Andy Prevost
 * © PHPMailer
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\System\CharsetType;
use phpOMS\System\File\Local\File;
use phpOMS\System\MimeType;
use phpOMS\Utils\MbStringUtils;

/**
 * Mail class.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Mail
{
    /**
     * Mail id.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $id = '';

    /**
     * Mail from.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $from = '';

    /**
     * Mail from.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $fromName = '';

    /**
     * Mail to.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $to = [];

    /**
     * Mail subject.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $subject = '';

    /**
     * Mail cc.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $cc = [];

    /**
     * Mail bcc.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $bcc = [];

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
    protected string $body = '';

    /**
     * Mail overview.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $overview = '';

    /**
     * Mail alt.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $bodyAlt = '';

    /**
     * Mail mime.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $bodyMime = '';

    /**
     * Mail body.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $icalBody = '';

    /**
     * Mail header.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $header = '';

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
    protected string $charset = CharsetType::ISO_8859_1;

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
    protected ?\DateTime $messageDate = null;

    /**
     * Should confirm reading
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $confirmReading = false;

    private string $signKeyFile   = '';

    private string $signCertFile  = '';

    private string $signExtraFile = '';

    private string $signKeyPass   = '';

    /**
     * Constructor.
     *
     * @param mixed $id Id
     *
     * @since 1.0.0
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Set body.
     *
     * @param string $body Mail body
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setBody(string $body) : void
    {
        $this->body = $body;
    }

    /**
     * Set body alt.
     *
     * @param string $body Mail body
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setBodyAlt(string $body) : void
    {
        $this->bodyAlt     = $body;
        $this->contentType = empty($body) ? MimeType::M_TXT : MimeType::M_ALT;
    }

    /**
     * Set body.
     *
     * @param array $overview Mail overview
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setOverview(array $overview) : void
    {
        $this->overview = $overview;
    }

    /**
     * Set encoding.
     *
     * @param int $encoding Mail encoding
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setEncoding(int $encoding) : void
    {
        $this->encoding = $encoding;
    }

    /**
     * Set content type.
     *
     * @param int $contentType Mail content type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setContentType(int $contentType) : void
    {
        $this->contentType = empty($this->bodyAlt) ? $contentType : MimeType::M_ALT;
    }

    /**
     * Set subject
     *
     * @param string $subject Subject
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSubject(string $subject) : void
    {
        $this->subject = \trim($subject);
    }

    /**
     * Set the from address
     *
     * @param string $mail Mail address
     * @param string $name Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setFrom(string $mail, string $name = '') : bool
    {
        $mail = $this->normalizeEmailAddress($mail);
        $name = $this->normalizeName($name);

        if ($mail === null) {
            return false;
        }

        $this->from     = $mail;
        $this->fromName = $name;

        return true;
    }

    /**
     * Add a to address
     *
     * @param string $mail Mail address
     * @param string $name Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addTo(string $mail, string $name = '') : bool
    {
        $mail = $this->normalizeEmailAddress($mail);
        $name = \trim($name);

        if ($mail === null) {
            return false;
        }

        $this->to[$mail] = $name;

        return true;
    }

    /**
     * Get to addresses
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getTo() : array
    {
        return $this->to;
    }

    /**
     * Add a cc address
     *
     * @param string $mail Mail address
     * @param string $name Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addCc(string $mail, string $name = '') : bool
    {
        $mail = $this->normalizeEmailAddress($mail);
        $name = \trim($name);

        if ($mail === null) {
            return false;
        }

        $this->cc[$mail] = $name;

        return true;
    }

    /**
     * Add a bcc address
     *
     * @param string $mail Mail address
     * @param string $name Name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addBcc(string $mail, string $name = '') : bool
    {
        $mail = $this->normalizeEmailAddress($mail);
        $name = \trim($name);

        if ($mail === null) {
            return false;
        }

        $this->bcc[$mail] = $name;

        return true;
    }

    /**
     * Add an attachment
     *
     * @param string $path        Path to the file
     * @param string $name        Name of the file
     * @param string $encoding    Encoding
     * @param string $type        Mime type
     * @param string $disposition Disposition
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
        string $disposition = DispositionType::ATTACHMENT
    ) : bool {
        if ((bool) \preg_match('#^[a-z]+://#i', $path)) {
            return false;
        }

        $this->attachment[] = [
            'path'        => $path,
            'filename'    => \basename($path),
            'name'        => $name,
            'encoding'    => $encoding,
            'type'        => $type,
            'string'      => false,
            'disposition' => $disposition,
            'id'          => $name,
        ];

        return true;
    }

    /**
     * Add string attachment
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
        string $disposition = DispositionType::ATTACHMENT
    ) : bool {
        $type = $type === '' ? MimeType::getByName('M_' . \strtoupper(File::extension($filename))) : $type;

        $this->attachment[] = [
            'path'        => $string,
            'filename'    => $filename,
            'name'        => \basename($filename),
            'encoding'    => $encoding,
            'type'        => $type,
            'string'      => true,
            'disposition' => $disposition,
            'id'          => 0,
        ];

        return true;
    }

    /**
     * Add inline image
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
        string $disposition = DispositionType::INLINE
    ) : bool {
        if ((bool) \preg_match('#^[a-z]+://#i', $path)) {
            return false;
        }

        $type     = $type === '' ? MimeType::getByName('M_' . \strtoupper(File::extension($path))) : $type;
        $filename = \basename($path);

        $this->attachment[] = [
            'path'        => $path,
            'filename'    => $filename,
            'name'        => empty($name) ? $filename : $name,
            'encoding'    => $encoding,
            'type'        => $type,
            'string'      => false,
            'disposition' => $disposition,
            'id'          => $cid,
        ];

        return true;
    }

    /**
     * Add inline image attachment
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
        string $disposition = DispositionType::INLINE
    ) : bool {
        $type = $type === '' ? MimeType::getByName('M_' . \strtoupper(File::extension($name))) : $type;

        $this->attachment[] = [
            'path'        => $string,
            'filename'    => $name,
            'name'        => $name,
            'encoding'    => $encoding,
            'type'        => $type,
            'string'      => true,
            'disposition' => $disposition,
            'id'          => $cid,
        ];

        return true;
    }

    /**
     * The email should be confirmed by the receivers
     *
     * @param string $confirm Should be confirmed?
     *
     * @return void
     *
     * @sicne 1.0.0
     */
    public function shouldBeConfirmed(bool $confirm = true) : void
    {
        $this->confirmReading = $confirm;
    }

    /**
     * Normalize an email address
     *
     * @param string $mail Mail address
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    private function normalizeEmailAddress(string $mail) : ?string
    {
        $mail = \trim($mail);
        $pos  = \strrpos($mail, '@');

        if ($pos === false || !\filter_var($mail, \FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $domain = \substr($mail, ++$pos);
        if (!((bool) \preg_match('/[\x80-\xFF]/', $domain))) {
            return $mail;
        }

        $domain     = \mb_convert_encoding($domain, 'UTF-8', $this->charset);
        $normalized = \idn_to_ascii($mail);

        return $normalized === false ? $mail : \substr($domain, 0, $pos) . $normalized;
    }

    /**
     * Normalize an email name
     *
     * @param string $name Name
     *
     * @return string
     *
     * @since1 1.0.0
     */
    private function normalizeName(string $name) : string
    {
        return \trim(\preg_replace("/[\r\n]+/", '', $name));
    }

    /**
     * Parsing an email containing a name
     *
     * @param string $mail Mail string
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function parseEmailAddress(string $mail) : array
    {
        $addresses = [];
        $list      = \explode(',', $mail);

        foreach ($list as $address) {
            $address = \trim($address);

            if (\stripos($address, '<') === false) {
                if (($address = $this->normalizeEmailAddress($address)) !== null) {
                    $addresses[] = [
                        'name'    => '',
                        'address' => $address,
                    ];
                }
            } else {
                $parts   = \explode('<', $address);
                $address = \trim(\str_replace('>', '', $parts[1]));

                if (($address = $this->normalizeEmailAddress($address)) !== null) {
                    $addresses[] = [
                        'name'    => \trim(\str_replace(['"', '\''], '', $parts[0])),
                        'address' => $address,
                    ];
                }
            }
        }

        return $addresses;
    }

    /**
     * Define the message type based on the content
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function setMessageType() : void
    {
        $this->messageType = '';

        $type = [];
        if (!empty($this->bodyAlt)) {
            $type[] = DispositionType::ALT;
        }

        foreach ($this->attachment as $attachment) {
            if ($attachment['disposition'] === DispositionType::INLINE) {
                $type[] = DispositionType::INLINE;
            } elseif ($attachment['disposition'] === DispositionType::ATTACHMENT) {
                $type[] = DispositionType::ATTACHMENT;
            }
        }

        $this->messageType = \implode('_', $type);
        $this->messageType = empty($this->messageType) ? DispositionType::PLAIN : $this->messageType;
    }

    /**
     * Create the mail body
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function createBody() : string
    {
        $this->id = empty($this->id) ? $this->generatedId() : $this->id;

        $output      = '';
        $boundary    = [];
        $boundary[0] = 'b0_' . $this->id;
        $boundary[1] = 'b1_' . $this->id;
        $boundary[2] = 'b2_' . $this->id;
        $boundary[3] = 'b3_' . $this->id;

        $output .= !empty($this->signKeyFile) ? $this->generateMimeHeader($boundary) . $this->endOfLine : '';

        $body         = $this->wrapText($this->body, $this->wordWrap, false);
        $bodyEncoding = $this->encoding;
        $bodyCharset  = $this->charset;

        if ($bodyEncoding === EncodingType::E_8BIT && !((bool) \preg_match('/[\x80-\xFF]/', $body))) {
            $bodyEncoding = EncodingType::E_7BIT;
            $bodyCharset  = CharsetType::ASCII;
        }

        if ($this->encoding !== EncodingType::E_BASE64 && ((bool) \preg_match('/^(.{' . (63 + \strlen($this->endOfLine)) . ',})/m', $body))) {
            $bodyEncoding = EncodingType::E_QUOTED;
        }

        $bodyAlt         = $this->wrapText($this->bodyAlt, $this->wordWrap, false);
        $bodyAltEncoding = $this->encoding;
        $bodyAltCharset  = $this->charset;

        if ($bodyAlt !== '') {
            if ($bodyAltEncoding === EncodingType::E_8BIT && !((bool) \preg_match('/[\x80-\xFF]/', $bodyAlt))) {
                $bodyAltEncoding = EncodingType::E_7BIT;
                $bodyAltCharset  = CharsetType::ASCII;
            }

            if ($this->encoding !== EncodingType::E_BASE64 && ((bool) \preg_match('/^(.{' . (63 + \strlen($this->endOfLine)) . ',})/m', $bodyAlt))) {
                $bodyAltEncoding = EncodingType::E_QUOTED;
            }
        }

        $mimeBody = 'This is a multi-part message in MIME format.' . $this->endOfLine . $this->endOfLine;

        switch ($this->messageType) {
            case DispositionType::INLINE:
            case DispositionType::ATTACHMENT:
                $body .= $mimeBody;
                $body .= $this->getBoundary($boundary[0], $bodyCharset, $this->contentType, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding);
                $body .= $this->endOfLine;
                $body .= $this->attachAll($this->messageType, $boundary[0]);
                break;
            case DispositionType::INLINE . '_' . DispositionType::ATTACHMENT:
                $body .= $mimeBody;
                $body .= '--' . $boundary[0] . $this->endOfLine;
                $body .= 'Content-Type: ' . MimeType::M_RELATED . ';' . $this->endOfLine;
                $body .= ' boundary ="' . $boundary[1] . '";' . $this->endOfLine;
                $body .= ' type ="' . MimeType::M_HTML . '";' . $this->endOfLine;
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[1], $bodyCharset, $this->contentType, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding);
                $body .= $this->endOfLine;
                $body .= $this->attachAll(DispositionType::INLINE, $boundary[1]);
                $body .= $this->endOfLine;
                $body .= $this->attachAll(DispositionType::ATTACHMENT, $boundary[1]);
                break;
            case DispositionType::ALT:
                $body .= $mimeBody;
                $body .= $this->getBoundary($boundary[0], $bodyAltCharset, MimeType::M_TEXT, $bodyAltEncoding);
                $body .= $this->encodeString($this->bodyAlt, $bodyAltEncoding);
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[0], $bodyCharset, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding);
                $body .= $this->endOfLine;

                if (!empty($this->icalBody)) {
                    $method    = ICALMethodType::REQUEST;
                    $constants = ICALMethodType::getConstants();

                    foreach ($constants as $enum) {
                        if (\stripos($this->icalBody, 'METHOD:' . $enum) !== false
                            || \stripos($this->icalBody, 'METHOD: ' . $enum) !== false
                        ) {
                            $method = $enum;
                            break;
                        }
                    }

                    $body .= $this->getBoundary($boundary[0], $this->charset, MimeType::M_ICS . '; method=' . $method, $this->encoding);
                    $body .= $this->encodeString($this->icalBody, $this->encoding);
                    $body .= $this->endOfLine;
                }

                $body .= $this->endOfLine . '--' . $boundary[0] . '--' . $this->endOfLine;
                break;
            case DispositionType::ALT . '_' . DispositionType::INLINE:
                $body .= $mimeBody;
                $body .= $this->getBoundary($boundary[0], $bodyAltCharset, MimeType::M_TEXT, $bodyAltEncoding);
                $body .= $this->encodeString($this->bodyAlt, $bodyAltEncoding);
                $body .= $this->endOfLine;
                $body .= '--' . $boundary[0] . $this->endOfLine;
                $body .= 'Content-Type: ' . MimeType::M_RELATED . ';' . $this->endOfLine;
                $body .= ' boundary="' . $boundary[1] . '";' . $this->endOfLine;
                $body .= ' type="' . MimeType::M_HTML . '";' . $this->endOfLine;
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[1], $bodyCharset, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding);
                $body .= $this->endOfLine;
                $body .= $this->attachAll(DispositionType::INLINE, $boundary[1]);
                $body .= $this->endOfLine;
                $body .= $this->endOfLine . '--' . $boundary[0] . '--' . $this->endOfLine;
                break;
            case DispositionType::ALT . '_' . DispositionType::ATTACHMENT:
                $body .= $mimeBody;
                $body .= '--' . $boundary[0] . $this->endOfLine;
                $body .= 'Content-Type: ' . MimeType::M_ALT . ';' . $this->endOfLine;
                $body .= ' boundary="' . $boundary[1] . '"' . $this->endOfLine;
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[1], $bodyAltCharset, MimeType::M_TEXT, $bodyAltEncoding);
                $body .= $this->encodeString($this->bodyAlt, $bodyAltEncoding);
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[1], $bodyCharset, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding);
                $body .= $this->endOfLine;

                if (!empty($this->icalBody)) {
                    $method    = ICALMethodType::REQUEST;
                    $constants = ICALMethodType::getConstants();

                    foreach ($constants as $enum) {
                        if (\stripos($this->icalBody, 'METHOD:' . $enum) !== false
                            || \stripos($this->icalBody, 'METHOD: ' . $enum) !== false
                        ) {
                            $method = $enum;
                            break;
                        }
                    }

                    $body .= $this->getBoundary($boundary[1], $this->charset, MimeType::M_ICS . '; method=' . $method, $this->encoding);
                    $body .= $this->encodeString($this->icalBody, $this->encoding);
                }

                $body .= $this->endOfLine . '--' . $boundary[1] . '--' . $this->endOfLine;
                $body .= $this->endOfLine;
                $body .= $this->attachAll(DispositionType::ATTACHMENT, $boundary[0]);
                break;
            case DispositionType::ALT . '_' . DispositionType::INLINE . '_' . DispositionType::ATTACHMENT:
                $body .= $mimeBody;
                $body .= '--' . $boundary[0] . $this->endOfLine;
                $body .= 'Content-Type: ' . MimeType::M_ALT . $this->endOfLine;
                $body .= ' boundary="' . $boundary[1] . '"' . $this->endOfLine;
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[1], $bodyAltCharset, MimeType::M_TEXT, $bodyAltEncoding);
                $body .= $this->encodeString($this->bodyAlt, $bodyAltEncoding);
                $body .= $this->endOfLine;
                $body .= '--' . $boundary[1] . $this->endOfLine;
                $body .= 'Content-Type: ' . MimeType::M_RELATED . ';' . $this->endOfLine;
                $body .= ' boundary="' . $boundary[2] . '"' . $this->endOfLine;
                $body .= ' type="' . MimeType::M_HTML . '"' . $this->endOfLine;
                $body .= $this->endOfLine;
                $body .= $this->getBoundary($boundary[2], $bodyCharset, MimeType::M_HTML, $bodyEncoding);
                $body .= $this->encodeString($this->body, $bodyEncoding);
                $body .= $this->endOfLine;
                $body .= $this->attachAll(DispositionType::INLINE, $boundary[2]);
                $body .= $this->endOfLine;
                $body .= $this->endOfLine . '--' . $boundary[2] . '--' . $this->endOfLine;
                $body .= $this->attachAll(DispositionType::ATTACHMENT, $boundary[1]);
                break;
            default:
                $body .= $this->encodeString($this->body, $bodyEncoding);
        }

        if ($this->signKeyFile !== '') {
            // @todo implement
            $output .= '';
        }

        return $output;
    }

    /**
     * Create html message
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createHtmlMsg() : void
    {
    }

    /**
     * Convert html to text message
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function htmlToText() : string
    {
        return '';
    }

    /**
     * Normalize text
     *
     * Line break
     *
     * @param string $text Text to normalized
     * @param string $lb   Line break
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function normalizeText(string $text, string $lb = "\n") : string
    {
        return \str_replace(["\r\n", "\r", "\n"], $lb, $text);
    }

    /**
     * Generate a random id
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function generatedId() : string
    {
        $rand = '';

        try {
            $rand = \random_bytes(32);
        } catch (\Throwable $t) {
            $rand = \hash('sha256', \uniqid((string) \mt_rand(), true), true);
        }

        return \base64_encode(\hash('sha256', $rand, true));
    }

    /**
     * Generate the mime header
     *
     * @param array $boundary Message boundary
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function generateMimeHeader(array $boundary) : string
    {
        $mime        = '';
        $isMultipart = true;

        switch ($this->messageType) {
            case DispositionType::INLINE:
                $mime .= 'Content-Type:' . MimeType::M_RELATED . ';' . $this->endOfLine;
                $mime .= ' boundary="' . $boundary[0] . '"' . $this->endOfLine;
                break;
            case DispositionType::ATTACHMENT:
            case DispositionType::INLINE . '_' . DispositionType::ATTACHMENT:
            case DispositionType::ALT . '_' . DispositionType::ATTACHMENT:
            case DispositionType::ALT . '_' . DispositionType::INLINE . '_' . DispositionType::ATTACHMENT:
                $mime .= 'Content-Type:' . MimeType::M_MIXED . ';' . $this->endOfLine;
                $mime .= ' boundary="' . $boundary[0] . '"' . $this->endOfLine;
                break;
            case DispositionType::ALT:
            case DispositionType::ALT . '_' . DispositionType::INLINE:
                $mime .= 'Content-Type:' . MimeType::M_ALT . ';' . $this->endOfLine;
                $mime .= ' boundary="' . $boundary[0] . '"' . $this->endOfLine;
                break;
            default:
                $mime .= 'Content-Type:' . $this->contentType . '; charset=' . CharsetType::UTF_8 . ';' . $this->endOfLine;

                $isMultipart = false;
        }

        return $isMultipart && $this->encoding !== EncodingType::E_7BIT
            ? 'Content-Transfer-Encoding:' . $this->encoding . ';' . $this->endOfLine
            : $mime;
    }

    /**
     * Wrap text
     *
     * @param string $text   Text to wrap
     * @param int    $length Line length
     * @param bool   $quoted Is quoted
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function wrapText(string $text, int $length, bool $quoted = false) : string
    {
        if ($length < 1 || $text === '') {
            return $text;
        }

        $softEndOfLine = $quoted ? ' =' . $this->endOfLine : $this->endOfLine;

        $text = $this->normalizeText($text, $this->endOfLine);
        $text = \rtrim($text, "\r\n");

        $lines = \explode($this->endOfLine, $text);

        $buffer     = '';
        $output     = '';
        $crlfLength = \strlen($this->endOfLine);
        $first      = true;
        $isUTF8     = $this->charset === CharsetType::UTF_8;

        foreach ($lines as $line) {
            $words = \explode(' ', $line);

            foreach ($words as $word) {
                if ($quoted && \strlen($word) > $length) {
                    if ($first) {
                        $spaces = $length - \strlen($buffer) - $crlfLength;

                        if ($spaces > 20) {
                            $len = $spaces;
                            if ($isUTF8) {
                                $len = MbStringUtils::utf8CharBoundary($word, $len);
                            } elseif ($word[$len - 1] === '=') {
                                --$len;
                            } elseif ($word[$len - 2] === '=') {
                                $len -= 2;
                            }

                            $part    = \substr($word, 0, $len);
                            $word    = \substr($word, $len);
                            $output .= $buffer . ' ' . $part . '=' . $this->endOfLine;
                        } else {
                            $output .= $buffer . $softEndOfLine;
                        }

                        $buffer = '';
                    }

                    while ($word !== '') {
                        if ($length < 1) {
                            break;
                        }

                        $len = $length;

                        if ($isUTF8) {
                            $len = MbStringUtils::utf8CharBoundary($word, $len);
                        } elseif ($word[$len - 1] === '=') {
                            --$len;
                        } elseif ($word[$len - 2] === '=') {
                            $len -= 2;
                        }

                        $part = \substr($word, 0, $len);
                        $word = \substr($word, $len);

                        if ($word !== '') {
                            $output .= $part . '=' . $this->endOfLine;
                        } else {
                            $buffer = $part;
                        }
                    }
                } else {
                    $oldBuf  = $buffer;
                    $buffer .= $word . ' ';

                    if (\strlen($buffer) > $length) {
                        $output .= \rtrim($oldBuf) . $softEndOfLine;
                        $buffer  = $word;
                    }
                }
            }

            $output .= \rtrim($buffer) . $this->endOfLine;
        }

        return $output;
    }

    /**
     * Render the boundary
     *
     * @param string $boundary    Boundary identifier
     * @param string $charset     Charset
     * @param string $contentType ContentType
     * @param string $encoding    Encoding
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function getBoundary(string $boundary, string $charset = null, string $contentType = null, string $encoding = null) : string
    {
        $boundary    = '';
        $charset     = empty($charset) ? $this->charset : $charset;
        $contentType = empty($contentType) ? $this->contentType : $contentType;
        $encoding    = empty($encoding) ? $this->encoding : $encoding;

        $boundary .= '--' . $boundary . $this->endOfLine;
        $boundary .= 'Content-Type: ' . $contentType . '; charset=' . $charset . $this->endOfLine;

        if ($encoding !== EncodingType::E_7BIT) {
            $boundary .= 'Content-Transfer-Encoding: ' . $encoding . $this->endOfLine;
        }

        return $boundary . $this->endOfLine;
    }

    /**
     * Encode a string
     *
     * @param string $text     Text to encode
     * @param string $encoding Encoding to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeString(string $text, string $encoding = EncodingType::E_BASE64) : string
    {
        $encoded = '';
        if ($encoding === EncodingType::E_BASE64) {
            $encoded = \chunk_split(\base64_encode($text), 76, $this->endOfLine);
        } elseif ($encoding === EncodingType::E_7BIT || $encoding === EncodingType::E_8BIT) {
            $encoded = $this->normalizeText($text, $this->endOfLine);

            if (\substr($encoded, -\strlen($this->endOfLine)) !== $this->endOfLine) {
                $encoded .= $this->endOfLine;
            }
        } elseif ($encoding === EncodingType::E_BINARY) {
            $encoded = $text;
        } elseif ($encoded === EncodingType::E_QUOTED) {
            $encoded = $this->normalizeText(\quoted_printable_decode($text));
        }

        return $encoded;
    }

    /**
     * Attach all attachments
     *
     * @param string $disposition Disposition type
     * @param string $boundary    Boundary identifier
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function attachAll(string $disposition, string $boundary) : string
    {
        $mime = [];
        $cid  = [];
        $incl = [];

        foreach ($this->attachment as $attach) {
            if ($attach['disposition'] === $disposition) {
                $hash = \hash('sha256', \serialize($attach));
                if (\in_array($hash, $incl, true)) {
                    continue;
                }

                $incl[] = $hash;

                if ($attach['disposition'] && isset($cid[$attach['id']])) {
                    continue;
                }

                $cid[$attach['id']] = true;

                $mime[] = '--' . $boundary . $this->endOfLine;
                $mime[] = !empty($attach['name'])
                    ? 'Content-Type: ' . $attach['type'] . '; name=' . $this->quotedString($this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $attach['name'])))) . '"' . $this->endOfLine
                    : 'Content-Type: ' . $attach['type'] . $this->endOfLine;

                if ($attach['encoding'] !== EncodingType::E_7BIT) {
                    $mime[] = 'Content-Transfer-Encoding: ' . $attach['encoding'] . $this->endOfLine;
                }

                if (((string) $attach['cid']) !== '' && $attach['disposition'] === DispositionType::INLINE) {
                    $mime[] = 'Content-ID: <' . $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $attach['cid']))) . '>' . $this->endOfLine;
                }

                if (!empty($attach['disposition'])) {
                    $encodedName = $this->encodeHeader(\trim(\str_replace(["\r", "\n"], '', $attach['name'])));

                    // @todo: "" might be wrong for || condition
                    $mime[] = !empty($encodedName)
                        ? 'Content-Disposition: ' . $attach['disposition'] . '; filename=' . $this->quotedString($encodedName) . $this->endOfLine
                        : 'Content-Disposition: ' . $attach['disposition'] . $this->endOfLine;
                }

                $mime[] = $this->endOfLine;
                $mime[] = $attach['string'] ? $this->encodeString($attach['path'], $attach['encoding']) : $this->encodeFile($attach['path'], $attach['encoding']);
                $mime[] = $this->endOfLine;
            }
        }

        $mime[] = '--' . $boundary . '--' . $this->endOfLine;

        return \implode('', $mime);
    }

    /**
     * Encode header value
     *
     * @param string $value   Value to encode
     * @param int    $context Value context
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeHeader(string $value, int $context = HeaderContext::TEXT) : string
    {
        $matches = 0;
        switch ($context) {
            case HeaderContext::PHRASE:
                if (!\preg_match('/[\200-\377]/', $value)) {
                    $encoded = \addslashes($value, "\0..\37\177\\\"");

                    return ($encoded === $value) && !\preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $value) ? $encoded : '"' . $encoded . '"';
                }

                $matches = \preg_match_all('/[^\040\041\043-\133\135-\176]/', $value, $matched);
                break;
            case HeaderContext::COMMENT:
                $matches = \preg_match_all('/[()"]/', $value, $matched);
                /* fallthrough */
            default:
                $matches += \preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $value, $matched);
        }

        $charset   = ((bool) \preg_match('/[\x80-\xFF]/', $value)) ? $this->charset : CharsetType::ASCII;
        $overhead  = \strlen($charset) + 8;
        $maxlength = $this->submitType === SubmitType::MAIL ? 63 - $overhead : 998 - $overhead;

        $valueLength = \strlen($value);
        $encoded     = '';

        if ($matches > $valueLength / 3) {
            $encoded = MbStringUtils::hasMultiBytes($value)
                ? $this->base64EncodeWrapMb($value, "\n")
                : \trim(\chunk_split(\base64_encode($value), $maxlength - $maxlength % 4, "\n"));

            $encoded = \preg_replace('/^(.*)$/m', ' =?' . $charset . '?B?\\1?=', $encoded);
        } elseif ($matches > 0 || $valueLength > $maxlength) {
            $encoded = $this->encodeQ($value, $context);
            $encoded = $this->wrapText($encoded, $maxlength, true);
            $encoded = \str_replace('=' . $this->endOfLine, "\n", \trim($encoded));
            $encoded = \preg_replace('/^(.*)$/m', ' =?' . $charset . '?Q?\\1?=', $encoded);
        } else {
            return $value;
        }

        return \trim($this->normalizeText($encoded));
    }

    /**
     * Encode a file
     *
     * @param string $path     Path to a file
     * @param string $encoding Encoding of the file
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeFile(string $path, string $encoding = EncodingType::E_BASE64) : string
    {
        if (!\is_readable($path) || (bool) \preg_match('#^[a-z]+://#i', $path)) {
            return '';
        }

        $content = \file_get_contents($path);

        if ($content === false) {
            return '';
        }

        return $this->encodeString($content, $encoding);
    }

    /**
     * Encode text as base64 multibye
     *
     * @param string $text Text to encode
     * @param string $lb   Linebreak
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function base64EncodeWrapMb(string $text, string $lb = "\n") : string
    {
        $start   = '=?' . $this->charset . '?B?';
        $end     = '?=';
        $encoded = '';

        $mbLength  = \mb_strlen($text, $this->charset);
        $length    = 75 - \strlen($start) - \strlen($end);
        $ratio     = $mbLength / \strlen($text);
        $avgLength = \floor($length * $ratio * 0.75);

        $offset = 0;
        $chunk  = '';

        for ($i = 0; $i < $mbLength; $i += $offset) {
            $lookBack = 0;

            do {
                $offset = $avgLength - $lookBack;
                $chunk  = \mb_substr($text, $i, $offset, $this->charset);
                $chunk  = \base64_encode($chunk);

                ++$lookBack;
            } while (\strlen($chunk) > $length);

            $encoded .= $chunk . $lb;
        }

        return \substr($encoded, 0, -\strlen($lb));
    }

    /**
     * Escape special strings
     *
     * @param string $text Text to escape
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function quotedString(string $text) : string
    {
        return \preg_match('/[ ()<>@,;:"\/\[\]?=]/', $text) === false ? $text : '"' . \str_replace('"', '\\"', $text) . '"';
    }

    /**
     * Quoted encode
     *
     * @param string $text    Text to encode
     * @param int    $context Value context
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function encodeQ(string $text, int $context = HeaderContext::TEXT) : string
    {
        $pattern = '';
               switch ($context) {
            case HeaderContext::PHRASE:
                $pattern = '^A-Za-z0-9!*+\/ -';
                break;
            case HeaderContext::COMMENT:
                $pattern = '\(\)"';
            case HeaderContext::TEXT:
            default:
                $pattern = '\000-\011\013\014\016-\037\075\077\137\177-\377' . $pattern;
        }

        $matches = [];
        $encoded = \str_replace(["\r", "\n"], '', $text);

        if (\preg_match_all('/[{' . $pattern . '}]/', $encoded, $matches) !== false) {
            $eqkey = \array_search('', $matches[0], true);
            if ($eqkey !== false) {
                unset($matches[0][$eqkey]);
                \array_unshift($matches[0], '=');
            }

            $unique = \array_unique($matches[0]);
            foreach ($unique as $char) {
                $encoded = \str_replace($char, '=' . \sprintf('%02X', \ord($char)), $encoded);
            }
        }

        return \str_replace(' ', '_', $encoded);
    }

    /**
     * Set signing files
     *
     * @param string $certFile Certification file path
     * @param string $keyFile  Key file path
     * @param string $keyPass  Password for the key
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function sign(string $certFile, string $keyFile, string $keyPass) : void
    {
        $this->signCertFile = $certFile;
        $this->signKeyFile  = $keyFile;
        $this->signKeyPass  = $keyPass;
    }

    public function preSend() : void
    {
        $this->mimeHeader = '';
        $this->mimeBody   = $this->createBody();
        // @todo: only if createBody impements sign / #tempheader = $this->header
        $this->mimeHeader = $this->createHeader();

        // set mime body
        // set mime header
        // ...
    }

    private function addrAppend(string $type, array $addr) : string
    {
    }
}
