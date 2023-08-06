<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Cache\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\Contract\SerializableInterface;
use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException;

/**
 * Memcache class.
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class MemCached extends ConnectionAbstract
{
    /**
     * Delimiter for cache meta data
     *
     * @var string
     * @since 1.0.0
     */
    private const DELIM = '$';

    /**
     * {@inheritdoc}
     */
    protected string $type = CacheType::MEMCACHED;

    /**
     * Only cache if data is larger than threshold (0-100).
     *
     * @var int
     * @since 1.0.0
     */
    private int $threshold = 0;

    /**
     * Constructor.
     *
     * @param array{host:string, port:int} $data Cache data
     *
     * @since 1.0.0
     */
    public function __construct(array $data)
    {
        $this->con = new \Memcached();
        $this->connect($data);
    }

    /**
     * Connect to cache
     *
     * @param null|array{host:string, port:int} $data Cache data
     *
     * @return void
     *
     * @throws InvalidConnectionConfigException
     *
     * @since 1.0.0
     */
    public function connect(array $data = null) : void
    {
        $this->dbdata = isset($data) ? $data : $this->dbdata;

        if (!isset($this->dbdata['host'], $this->dbdata['port'])) {
            $this->status = CacheStatus::FAILURE;
            throw new InvalidConnectionConfigException((string) \json_encode($this->dbdata));
        }

        $this->con->addServer($this->dbdata['host'], $this->dbdata['port']);

        $this->status = CacheStatus::OK;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function set(int | string $key, mixed $value, int $expire = -1) : void
    {
        if ($this->status !== CacheStatus::OK) {
            return;
        }

        if (!(\is_scalar($value) || $value === null || \is_array($value) || $value instanceof \JsonSerializable || $value instanceof SerializableInterface)) {
            throw new \InvalidArgumentException();
        }

        $this->con->set((string) $key, $value, \max($expire, 0));
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function add(int | string $key, mixed $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        if (!(\is_scalar($value) || $value === null || \is_array($value) || $value instanceof \JsonSerializable || $value instanceof SerializableInterface)) {
            throw new \InvalidArgumentException();
        }

        return $this->con->add((string) $key, $value, \max($expire, 0));
    }

    /**
     * {@inheritdoc}
     */
    public function get(int | string $key, int $expire = -1) : mixed
    {
        if ($this->status !== CacheStatus::OK) {
            return null;
        }

        $result = $this->con->get((string) $key);

        return $this->con->getResultCode() !== \Memcached::RES_SUCCESS ? null : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $result = $this->con->delete((string) $key);

        return $this->con->getResultCode() === \Memcached::RES_NOTFOUND ? true : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->get((string) $key) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function increment(int | string $key, int $value = 1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->increment((string) $key, $value) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement(int | string $key, int $value = 1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->decrement((string) $key, $value) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function rename(int | string $old, int | string $new, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $value = $this->get((string) $old);
        $this->set((string) $new, $value, $expire);
        $this->delete((string) $old);

        return true;
    }

    /**
     * Parse cached value
     *
     * @param int    $type      Cached value type
     * @param string $raw       Cached value
     * @param int    $expireEnd Value end position
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    /*
    private function reverseValue(int $type, string $raw, int $expireEnd) : mixed
    {
        switch ($type) {
            case CacheValueType::_INT:
                return (int) \substr($raw, $expireEnd + 1);
            case CacheValueType::_FLOAT:
                return (float) \substr($raw, $expireEnd + 1);
            case CacheValueType::_BOOL:
                return (bool) \substr($raw, $expireEnd + 1);
            case CacheValueType::_STRING:
                return \substr($raw, $expireEnd + 1);
            case CacheValueType::_ARRAY:
                $array = \substr($raw, $expireEnd + 1);
                return \json_decode($array === false ? '[]' : $array, true);
            case CacheValueType::_NULL:
                return null;
            case CacheValueType::_JSONSERIALIZABLE:
                $namespaceStart = (int) \strpos($raw, self::DELIM, $expireEnd);
                $namespaceEnd   = (int) \strpos($raw, self::DELIM, $namespaceStart + 1);
                $namespace      = \substr($raw, $namespaceStart + 1, $namespaceEnd - $namespaceStart - 1);

                if ($namespace === false) {
                    return null; // @codeCoverageIgnore
                }

                return new $namespace();
            case CacheValueType::_SERIALIZABLE:
                $namespaceStart = (int) \strpos($raw, self::DELIM, $expireEnd);
                $namespaceEnd   = (int) \strpos($raw, self::DELIM, $namespaceStart + 1);
                $namespace      = \substr($raw, $namespaceStart + 1, $namespaceEnd - $namespaceStart - 1);

                if ($namespace === false) {
                    return null; // @codeCoverageIgnore
                }

                $obj = new $namespace();
                $obj->unserialize(\substr($raw, $namespaceEnd + 1));

                return $obj;
            default:
                return null; // @codeCoverageIgnore
        }
    }
    */

    /**
     * {@inheritdoc}
     */
    public function updateExpire(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        if ($expire > 0) {
            $this->con->touch((string) $key, $expire);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
    {
        return $this->status === CacheStatus::OK;
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $this->con->flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function replace(int | string $key, mixed $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->replace((string) $key, $value, \max($expire, 0));
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        if ($this->status !== CacheStatus::OK) {
            return [];
        }

        $stat = $this->con->getStats();
        $temp = \reset($stat);

        $stats           = [];
        $stats['status'] = $this->status;
        $stats['count']  = $temp['curr_items'];
        $stats['size']   = $temp['bytes'];

        return $stats;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        return $this->threshold;
    }

    /**
     * Destructor.
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Closing cache.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function close() : void
    {
        if ($this->con !== null) {
            $this->con->quit();
        }

        parent::close();
    }
}
