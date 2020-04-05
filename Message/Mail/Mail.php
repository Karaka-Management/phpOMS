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

use phpOMS\System\CharsetType;
use phpOMS\System\MimeType;

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
     * @var int
     * @since 1.0.0
     */
    protected int $encoding = EncodingType::E_8BIT;

    /**
     * Mail content type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $contentType = MimeType::M_TXT;

    /**
     * Boundaries
     *
     * @var array
     * @since 1.0.0
     */
    protected array $boundary = [];

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

    private string $signKeyFile = '';
    private string $signCertFile = '';
    private string $signExtraFile = '';
    private string $signKeyPass = '';

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
        $this->contentType = MimeType::M_ALT;
        $this->setMessageType();
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
        $this->subject = $subject;
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

        $info = [];
        \preg_match('#^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^.\\\\/]+?)|))[\\\\/.]*$#m', $path, $info);
        $filename = $info[2] ?? '';

        $this->attachment[] = [
            'path'        => $path,
            'filename'    => $filename,
            'name'        => $name,
            'encoding'    => $encoding,
            'type'        => $type,
            'disposition' => $disposition,
            '???'         => $name,
        ];

        $this->setMessageType();

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

        $normalized = \idn_to_ascii($mail);

        return $normalized === false ? $mail : $normalized;
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
                    $addresses[$address] = '';
                }
            } else {
                $parts   = \explode('<', $address);
                $address = \trim(\str_replace('>', '', $parts[1]));

                if (($address = $this->normalizeEmailAddress($address)) !== null) {
                    $addresses[$address] = \trim(\str_replace(['"', '\''], '', $parts[0]));
                }
            }
        }

        return $addresses;
    }

    /**
     * Check if text has none ascii characters
     *
     * @param string $text Text to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function hasNoneASCII(string $text) : bool
    {
        return (bool) \preg_match('/[\x80-\xFF]/', $text);
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

        $output = '';
        $this->boundary[0] = 'b0_' . $this->id;
        $this->boundary[1] = 'b1_' . $this->id;
        $this->boundary[2] = 'b2_' . $this->id;
        $this->boundary[3] = 'b3_' . $this->id;

        $output .= !empty($this->signKeyFile) ? $this->generateMimeHeader() . $this->endOfLine : '';

        $body = $this->wrapText($this->body, $this->wordWrap, false);


        return $output;
    }

    /**
     * Normalize text
     *
     * Line break
     *
     * @param string $text Text to normalized
     * @param string $lb   Line break
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
     * @return string
     *
     * @since 1.0.0
     */
    private function generateMimeHeader() : string
    {
        $mime        = '';
        $isMultipart = true;

        switch ($this->messageType) {
            case DispositionType::INLINE:
                $mime .= 'Content-Type:' . MimeType::M_RELATED . ';' . $this->endOfLine;
                $mime .= ' boundary="' . $this->boundary[0] . '"' . $this->endOfLine;
                break;
            case DispositionType::ATTACHMENT:
            case DispositionType::INLINE . '_' . DispositionType::ATTACHMENT:
            case DispositionType::ALT . '_' . DispositionType::ATTACHMENT:
            case DispositionType::ALT . '_' . DispositionType::INLINE . '_' . DispositionType::ATTACHMENT:
                $mime .= 'Content-Type:' . MimeType::M_MIXED . ';' . $this->endOfLine;
                $mime .= ' boundary="' . $this->boundary[0] . '"' . $this->endOfLine;
                break;
            case DispositionType::ALT:
            case DispositionType::ALT . '_' . DispositionType::INLINE:
                $mime .= 'Content-Type:' . MimeType::M_ALT . ';' . $this->endOfLine;
                $mime .= ' boundary="' . $this->boundary[0] . '"' . $this->endOfLine;
                break;
            default:
                $mime .= 'Content-Type:' . $this->contentType . '; charset=' . CharsetType::UTF_8 . ';' . $this->endOfLine;

                $isMultipart = false;
        }

        return $isMultipart && $this->encoding !== EncodingType::E_7BIT
            ? 'Content-Transfer-Encoding:' . $this->encoding . ';' . $this->endOfLine
            : $mime;
    }

    private function wrapText(string $text, int $length, bool $quoted = false) : string
    {
        if ($length < 1) {
            return $text;
        }

        $softEndOfLine = $quoted ? ' =' . $this->endOfLine : $this->endOfLine;

        $text = $this->normalizeText($text, $this->endOfLine);
        $text = \rtrim($text, "\r\n");

        $lines = \explode($this->endOfLine, $text);

        $buffer = '';
        $output = '';
        foreach ($lines as $line) {
            $words = \explode(' ', $line);

            foreach ($words as $word) {
                if ($quoted && \strlen($word) > $length) {

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
}
