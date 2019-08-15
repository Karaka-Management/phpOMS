<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Message
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Uri\UriInterface;

/**
 * Request class.
 *
 * @package    phpOMS\Message
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class RequestAbstract implements MessageInterface
{
    /**
     * Uri.
     *
     * @var null|UriInterface
     * @since 1.0.0
     */
    protected ?UriInterface $uri = null;

    /**
     * Request method.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected ?string $method = null;

    /**
     * Request type.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected ?string $type = null;

    /**
     * Request data.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $data = [];

    /**
     * Request hash.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $hash = [];

    /**
     * Uploaded files.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $files = [];

    /**
     * Request lock.
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $lock = false;

    /**
     * Request header.
     *
     * @var null|HeaderAbstract
     * @since 1.0.0
     */
    protected ?HeaderAbstract $header = null;

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

        $key = \mb_strtolower($key);

        return $this->data[$key] ?? null;
    }

    /**
     * Get data.
     *
     * @param mixed $key Data key
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getDataJson($key) : array
    {
        $key = \mb_strtolower($key);

        if (!isset($this->data[$key])) {
            return [];
        }

        $json = \json_decode($this->data[$key], true);

        return $json === false ? [] : $json;
    }

    /**
     * Get data.
     *
     * @param mixed  $key   Data key
     * @param string $delim Data delimiter
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getDataList($key, string $delim = ',') : array
    {
        $key = \mb_strtolower($key);

        if (!isset($this->data[$key])) {
            return [];
        }

        $list = \explode($delim, $this->data[$key]);

        if ($list === false) {
            return []; // @codeCoverageIgnore
        }

        foreach ($list as $i => $e) {
            $list[$i] = \trim($e);
        }

        return $list;
    }

    /**
     * Get data based on wildcard.
     *
     * @param string $regex Regex data key
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getLike(string $regex) : array
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            if (\preg_match('/' . $regex . '/', $key) === 1) {
                $data[$key] = $value;
            }
        }

        return $data;
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
            $key = \mb_strtolower($key);
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
    public function __toString() : string
    {
        return $this->uri->__toString();
    }

    /**
     * Get route verb.
     *
     * @return int
     *
     * @since  1.0.0
     */
    abstract public function getRouteVerb() : int;

    /**
     * Get files.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getFiles() : array
    {
        return $this->files;
    }
}
