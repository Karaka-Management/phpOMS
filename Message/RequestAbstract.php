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
namespace phpOMS\Message;

use phpOMS\Datatypes\Exception\InvalidEnumValue;
use phpOMS\Localization\Localization;
use phpOMS\Uri\UriInterface;

/**
 * Request class.
 *
 * @property mixed request
 *
 * @category   Request
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class RequestAbstract implements RequestInterface
{

    /**
     * Uri.
     *
     * @var UriInterface
     * @since 1.0.0
     */
    protected $uri = null;

    /**
     * Request method.
     *
     * @var string
     * @since 1.0.0
     */
    protected $method = null;

    /**
     * Root.
     *
     * @var string
     * @since 1.0.0
     */
    protected $rootPath = null;

    /**
     * Request data.
     *
     * @var array
     * @since 1.0.0
     */
    protected $data = null;

    /**
     * Request data.
     *
     * @var array
     * @since 1.0.0
     */
    protected $path = [];

    /**
     * Language.
     *
     * @var Localization
     * @since 1.0.0
     */
    protected $l11n = null;

    /**
     * Account.
     *
     * @var int
     * @since 1.0.0
     */
    protected $account = null;

    /**
     * Request type.
     *
     * @var \phpOMS\Message\RequestSource
     * @since 1.0.0
     */
    private static $source = null;

    /**
     * Request status.
     *
     * @var string
     * @since 1.0.0
     */
    private $status = RequestStatus::R_200;

    /**
     * Request hash.
     *
     * @var array
     * @since 1.0.0
     */
    protected $hash = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUri() : UriInterface
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function setUri(UriInterface $uri)
    {
        return $this->uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getHash() : array
    {
        return $this->hash;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestSource()
    {
        return self::$source;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestSource($source)
    {
        if(!RequestSource::isValidValue($source)) {
            throw new InvalidEnumValue($source);
        }

        self::$source = $source;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getMethod() : string;

    /**
     * {@inheritdoc}
     */
    public function setMethod(string $method) {
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($key = null)
    {
        if ($key === null) {
            return $this->path;
        }

        return $this->path[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = null)
    {
        $key = mb_strtolower($key);
        return !isset($key) ? $this->data : $this->data[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value, $overwrite = true)
    {
        $key = mb_strtolower($key);
        if ($overwrite || !isset($this->data[$key])) {
            $this->data[$key] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setLocalization(Localization $l11n)
    {
        return $this->l11n = $l11n;
    }

    /**
     * {@inheritdoc}
     */
    public function getL11n() : Localization
    {
        return $this->l11n;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccount() : int
    {
        return $this->account;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccount(int $account)
    {
        $this->account = $account;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusCode(string $status)
    {
        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode() : string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public abstract function getOrigin() : string;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->uri->__toString();
    }
}
