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

/**
 * Mail class.
 *
 * @category   Framework
 * @package    phpOMS\Message\Mail
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Mail
{
    /**
     * Mail from.
     *
     * @var string
     * @since 1.0.0
     */
    protected $from = '';

    /**
     * Mail to.
     *
     * @var array
     * @since 1.0.0
     */
    protected $to = [];

    /**
     * Mail subject.
     *
     * @var string
     * @since 1.0.0
     */
    protected $subject = '';

    /**
     * Mail cc.
     *
     * @var array
     * @since 1.0.0
     */
    protected $cc = [];

    /**
     * Mail reply to.
     *
     * @var array
     * @since 1.0.0
     */
    protected $replyTo = [];

    /**
     * Mail bcc.
     *
     * @var array
     * @since 1.0.0
     */
    protected $bcc = [];

    /**
     * Mail attachments.
     *
     * @var array
     * @since 1.0.0
     */
    protected $attachment = [];

    /**
     * Mail body.
     *
     * @var string
     * @since 1.0.0
     */
    protected $body = '';

    /**
     * Mail overview.
     *
     * @var string
     * @since 1.0.0
     */
    protected $overview = '';

    /**
     * Mail alt.
     *
     * @var string
     * @since 1.0.0
     */
    protected $bodyAlt = '';

    /**
     * Mail mime.
     *
     * @var string
     * @since 1.0.0
     */
    protected $bodyMime = '';

    /**
     * Mail header.
     *
     * @var string
     * @since 1.0.0
     */
    protected $headerMail = '';

    /**
     * Word wrap.
     *
     * @var string
     * @since 1.0.0
     */
    protected $wordWrap = 78;

    /**
     * Encoding.
     *
     * @var int
     * @since 1.0.0
     */
    protected $encoding = 0;

    /**
     * Mail type.
     *
     * @var int
     * @since 1.0.0
     */
    protected $type = MailType::MAIL;

    /**
     * Mail host name.
     *
     * @var string
     * @since 1.0.0
     */
    protected $hostname = '';

    /**
     * Mail id.
     *
     * @var string
     * @since 1.0.0
     */
    protected $messageId = '';

    /**
     * Mail message type.
     *
     * @var string
     * @since 1.0.0
     */
    protected $messageType = '';

    /**
     * Mail from.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected $messageDate = null;

    /**
     * todo: ???
     */
    protected $mailer = null;

    /**
     * Constructor.
     *
     * @param mixed $id Id
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct($id)
    {
        $this->messageId = $id;
    }

    /**
     * Set body.
     *
     * @param string $body Mail body
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setBody(string $body) /* : void */
    {
        $this->body = $body;
    }

    /**
     * Set body.
     *
     * @param string $overview Mail overview
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setOverview(string $overview) /* : void */
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setEncoding(int $encoding) /* : void */
    {
        $this->encoding = $encoding;
    }

}
