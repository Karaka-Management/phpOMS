<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    phpOMS\Message
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\Uri\UriInterface;

/**
 * Request class.
 *
 * @package    phpOMS\Message
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
    protected $data = [];

    /**
     * Request type.
     *
     * @var int
     * @since 1.0.0
     */
    protected $source = RequestSource::UNDEFINED;

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

    /**
     * Request header.
     *
     * @var HeaderAbstract
     * @since 1.0.0
     */
    protected $header = null;

    /**
     * Get request uri.
     *
     * @return UriInterface
     *
     * @since  1.0.0
     */
    public function getUri() : UriInterface
    {
        return $this->uri;
    }

    /**
     * Set request uri.
     *
     * @param UriInterface $uri Uri
     * 
     * @return void
     *
     * @since  1.0.0
     */
    public function setUri(UriInterface $uri) : void
    {
        $this->uri = $uri;
    }

    /**
     * Get request hash.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getHash() : array
    {
        return $this->hash;
    }

    /**
     * Get request source.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getRequestSource() : int
    {
        return $this->source;
    }

    /**
     * Set request source.
     *
     * @param int $source Request source
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setRequestSource(int $source) : void
    {
        if (!RequestSource::isValidValue($source)) {
            throw new InvalidEnumValue((string) $source);
        }

        $this->source = $source;
    }

    /**
     * Get request method.
     *
     * @return string
     *
     * @since  1.0.0
     */
    abstract public function getMethod() : string;

    /**
     * Set request method.
     *
     * @param string $method Request method
     * 
     * @return void
     *
     * @since  1.0.0
     */
    public function setMethod(string $method) : void
    {
        $this->method = $method;
    }

    /**
     * Get data.
     *
     * @param mixed $key Data key
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function getData($key = null)
    {
        if ($key === null) {
            return $this->data;
        }

        $key = mb_strtolower($key);

        return $this->data[$key] ?? null;
    }

    /**
     * Check if has data.
     *
     * @param mixed $key Data key
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function hasData($key) : bool
    {
        return isset($this->data[$key]);
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
     * Lock request for further manipulations.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function lock() : void
    {
        $this->lock = true;
    }

    /**
     * Get request header.
     *
     * @return HeaderAbstract
     *
     * @since  1.0.0
     */
    public function getHeader() : HeaderAbstract
    {
        return $this->header;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getOrigin() : string;

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
     */
    abstract public function getRequestTarget() : string;

    /**
     * Get route verb.
     *
     * @return int
     *
     * @since  1.0.0
     */
    abstract public function getRouteVerb() : int;
}
