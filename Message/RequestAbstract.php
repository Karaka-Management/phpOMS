<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Uri\UriInterface;

/**
 * Request class.
 *
 * @package phpOMS\Message
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class RequestAbstract implements MessageInterface
{
    /**
     * Uri.
     *
     * @var UriInterface
     * @since 1.0.0
     */
    public UriInterface $uri;

    /**
     * Request data.
     *
     * @var array<int|string, mixed>
     * @since 1.0.0
     */
    protected array $data = [];

    /**
     * Files data.
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
     * @var HeaderAbstract
     * @since 1.0.0
     */
    public HeaderAbstract $header;

    /**
     * Request hash.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $hash = [];

    /**
     * Get data.
     *
     * @param string $key  Data key
     * @param string $type Return type
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getData(string $key = null, string $type = null) : mixed
    {
        if ($key === null) {
            return $this->data;
        }

        $key = \mb_strtolower($key);

        if (!isset($this->data[$key])) {
            return null;
        }

        if ($type === null) {
            return $this->data[$key] ?? null;
        }

        switch ($type) {
            case 'int':
                return (int) $this->data[$key];
            case 'string':
                return (string) $this->data[$key];
            case 'float':
                return (float) $this->data[$key];
            case 'bool':
                return (bool) $this->data[$key];
            default:
                return $this->data[$key];
        }
    }

    /**
     * Get data.
     *
     * @param string $key Data key
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDataJson(string $key) : array
    {
        $key = \mb_strtolower($key);

        if (!isset($this->data[$key])) {
            return [];
        }

        $json = \json_decode($this->data[$key], true); /** @phpstan-ignore-line */

        return !\is_array($json) ? [] : $json;
    }

    /**
     * Get data.
     *
     * @param string $key   Data key
     * @param string $delim Data delimiter
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDataList(string $key, string $delim = ',') : array
    {
        $key = \mb_strtolower($key);

        if (!isset($this->data[$key])) {
            return [];
        }

        /* @phpstan-ignore-next-line */
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
     * @since 1.0.0
     */
    public function getLike(string $regex) : array
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            if (\preg_match('/' . $regex . '/', (string) $key) === 1) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * Check if has data.
     *
     * @param string $key Data key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasData(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Set request data.
     *
     * @param string $key       Data key
     * @param mixed  $value     Value
     * @param bool   $overwrite Overwrite data
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setData(string $key, mixed $value, bool $overwrite = false) : bool
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
     * @since 1.0.0
     */
    public function lock() : void
    {
        $this->lock = true;
    }

    /**
     * Get request language.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLanguage() : string
    {
        return $this->header->l11n->getLanguage();
    }

    /**
     * Get request hash.
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getHash() : array
    {
        return $this->hash;
    }

    /**
     * Get the origin request source (IPv4/IPv6)
     *
     * @return string
     *
     * @since 1.0.0
     */
    abstract public function getOrigin() : string;

    /**
     * Get the route verb
     *
     * @return int
     *
     * @since 1.0.0
     */
    abstract public function getRouteVerb() : int;

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->uri->__toString();
    }

    /**
     * Get files.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getFiles() : array
    {
        return $this->files;
    }

    /**
     * Add file to request
     *
     * @param array $file File data to add (Array here means one file with multiple information e.g. name, path, size)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addFile(array $file) : void
    {
        $this->files[] = $file;
    }
}
