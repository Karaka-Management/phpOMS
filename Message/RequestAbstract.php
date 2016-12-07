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
abstract class RequestAbstract implements MessageInterface
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
     * Request type.
     *
     * @var string
     * @since 1.0.0
     */
    protected $type = null;

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
    protected $status = null;

    /**
     * Request hash.
     *
     * @var array
     * @since 1.0.0
     */
    protected $hash = [];

    /**
     * Request lock.
     *
     * @var bool
     * @since 1.0.0
     */
    protected $lock = false;

    protected $header = null;

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
     * Get request uri.
     *
     * @return UriInterface
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getUri() : UriInterface
    {
        return $this->uri;
    }

    /**
     * Set request uri.
     *
     * @param UriInterface $uri
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setUri(UriInterface $uri) /* : void */
    {
        $this->uri = $uri;
    }

    /**
     * Get request hash.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
    public function setRequestSource($source) /* : void */
    {
        if (!RequestSource::isValidValue($source)) {
            throw new InvalidEnumValue($source);
        }

        self::$source = $source;
    }

    /**
     * Get request method.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    abstract public function getMethod() : string;

    /**
     * Set request method.
     *
     * @param string $method
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setMethod(string $method) /* : void */
    {
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($key = null) /* : ?string */
    {
        if ($key === null) {
            return $this->path;
        }

        return $this->path[$key] ?? null;
    }

    /**
     * Set request type.
     *
     * E.g. M_JSON
     *
     * @param string $type Request type
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setType(string $type) /* : void */
    {
        $this->type = $type;
    }

    /**
     * Get request type.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getType() : string
    {
        return $this->type;
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
     * Set request data.
     *
     * @param mixed $key       Data key
     * @param mixed $value     Value
     * @param bool  $overwrite Overwrite data
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setData($key, $value, bool $overwrite = true) : bool
    {
        if (!$this->lock) {
            $key = mb_strtolower($key);
            if ($overwrite || !isset($this->data[$key])) {
                $this->data[$key] = $value;

                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getL11n() : Localization
    {
        return $this->l11n;
    }

    /**
     * Lock request for further manipulations.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function lock() /* : void */
    {
        $this->lock = true;
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
    public function setAccount(int $account) /* : void */
    {
        $this->account = $account;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusCode(string $status) /* : void */
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
     * Get request header.
     *
     * @return HeaderAbstract
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getHeader() : HeaderAbstract
    {
        return $this->header;
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

    /**
     * Get request target.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    abstract public function getRequestTarget() : string;

    /**
     * Get route verb.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    abstract public function getRouteVerb() : int;
}
