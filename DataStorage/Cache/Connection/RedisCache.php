<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Cache\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException;
use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;

/**
 * RedisCache class.
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class RedisCache extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    protected string $type = CacheType::REDIS;

    /**
     * Delimiter for cache meta data
     *
     * @var string
     * @since 1.0.0
     */
    private const DELIM = '$';

    /**
     * Constructor
     *
     * @param array{db:int, host:string, port:int} $data Cache data
     *
     * @since 1.0.0
     */
    public function __construct(array $data)
    {
        $this->con = new \Redis();
        $this->connect($data);
    }

    /**
     * Connect to cache
     *
     * @param null|array{db:int, host:string, port:int} $data Cache data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function connect(array $data = null) : void
    {
        $this->dbdata = isset($data) ? $data : $this->dbdata;

        if (!isset($this->dbdata['host'], $this->dbdata['port'], $this->dbdata['db'])) {
            $this->status = CacheStatus::FAILURE;
            throw new InvalidConnectionConfigException((string) \json_encode($this->dbdata));
        }

        $this->con->connect($this->dbdata['host'], $this->dbdata['port']);

        try {
            $this->con->ping();
        } catch (\Throwable $e) {
            $this->status = CacheStatus::FAILURE;
            return;
        }

        $this->con->setOption(\Redis::OPT_SERIALIZER, (string) \Redis::SERIALIZER_NONE);
        $this->con->setOption(\Redis::OPT_SCAN, (string) \Redis::SCAN_NORETRY);
        $this->con->select($this->dbdata['db']);

        $this->status = CacheStatus::OK;
    }

    /**
     * {@inheritdoc}
     */
    public function close() : void
    {
        if ($this->con !== null) {
            $this->con->close();
        }

        parent::close();
    }

    /**
     * {@inheritdoc}
     */
    public function set(int | string $key, mixed $value, int $expire = -1) : void
    {
        if ($this->status !== CacheStatus::OK) {
            return;
        }

        if ($expire > 0) {
            $this->con->setEx($key, $expire, $this->build($value));

            return;
        }

        $this->con->set($key, $this->build($value));
    }

    /**
     * {@inheritdoc}
     */
    public function add(int | string $key, mixed $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        if ($expire > 0) {
            return $this->con->setNx($key, $this->build($value), $expire);
        }

        return $this->con->setNx($key, $this->build($value));
    }

    /**
     * {@inheritdoc}
     */
    public function get(int | string $key, int $expire = -1) : mixed
    {
        if ($this->status !== CacheStatus::OK || $this->con->exists($key) < 1) {
            return null;
        }

        $result = $this->con->get($key);

        if (\is_string($result)) {
            $type   = (int) $result[0];
            $start  = (int) \strpos($result, self::DELIM);
            $result = $this->reverseValue($type, $result, $start);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->del($key) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(int | string $key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function increment(int | string $key, int $value = 1) : void
    {
        $this->con->incrBy($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement(int | string $key, int $value = 1) : void
    {
        $this->con->decrBy($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function rename(int | string $old, int | string $new, int $expire = -1) : void
    {
        $this->con->rename($old, $new);

        if ($expire > 0) {
            $this->con->expire($new, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLike(string $pattern, int $expire = -1) : array
    {
        if ($this->status !== CacheStatus::OK) {
            return [];
        }

        $keys   = $this->con->keys('*');
        $values = [];

        foreach ($keys as $key) {
            if (\preg_match('/' . $pattern . '/', $key) === 1) {
                $result = $this->con->get($key);
                if (\is_string($result)) {
                    $type   = (int) $result[0];
                    $start  = (int) \strpos($result, self::DELIM);
                    $result = $this->reverseValue($type, $result, $start);
                }

                $values[] = $result;
            }
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteLike(string $pattern, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $keys = $this->con->keys('*');
        foreach ($keys as $key) {
            if (\preg_match('/' . $pattern . '/', $key) === 1) {
                $this->con->del($key);
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function updateExpire(int | string $key, int $expire = -1) : bool
    {
        if ($expire > 0) {
            $this->con->expire($key, $expire);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
    {
        return $this->flushAll();
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $this->con->flushDb();

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

        if ($this->con->exists($key) > 0) {
            $this->set($key, $value, $expire);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        if ($this->status !== CacheStatus::OK) {
            return [];
        }

        $info = $this->con->info();

        $stats           = [];
        $stats['status'] = $this->status;
        $stats['count']  = $this->con->dbSize();
        $stats['size']   = $info['used_memory'];

        return $stats;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        return 0;
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
     * Removing all cache elements larger or equal to the expiration date. Call flushAll for removing persistent cache elements (expiration is negative) as well.
     *
     * @param mixed $value Data to cache
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function build(mixed $value) : mixed
    {
        $type = $this->dataType($value);
        $raw  = $this->cachify($value, $type);

        return \is_string($raw) ? $type . self::DELIM . $raw : $raw;
    }

    /**
     * Create string representation of data for storage
     *
     * @param mixed $value Value of the data
     * @param int   $type  Type of the cache data
     *
     * @return mixed
     *
     * @throws InvalidEnumValue This exception is thrown if an unsupported cache value type is used
     *
     * @since 1.0.0
     */
    private function cachify(mixed $value, int $type) : mixed
    {
        if ($type === CacheValueType::_INT || $type === CacheValueType::_STRING || $type === CacheValueType::_BOOL) {
            return (string) $value;
        } elseif ($type === CacheValueType::_FLOAT) {
            return \rtrim(\rtrim(\number_format($value, 5, '.', ''), '0'), '.');
        } elseif ($type === CacheValueType::_ARRAY) {
            return (string) \json_encode($value);
        } elseif ($type === CacheValueType::_SERIALIZABLE) {
            return \get_class($value) . self::DELIM . $value->serialize();
        } elseif ($type === CacheValueType::_JSONSERIALIZABLE) {
            return \get_class($value) . self::DELIM . ((string) \json_encode($value->jsonSerialize()));
        } elseif ($type === CacheValueType::_NULL) {
            return '';
        }

        throw new InvalidEnumValue($type);
    }

    /**
     * Parse cached value
     *
     * @param int   $type  Cached value type
     * @param mixed $raw   Cached value
     * @param int   $start Value start position
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function reverseValue(int $type, mixed $raw, int $start) : mixed
    {
        switch ($type) {
            case CacheValueType::_INT:
                return (int) \substr($raw, $start + 1);
            case CacheValueType::_FLOAT:
                return (float) \substr($raw, $start + 1);
            case CacheValueType::_BOOL:
                return (bool) \substr($raw, $start + 1);
            case CacheValueType::_STRING:
                return \substr($raw, $start + 1);
            case CacheValueType::_ARRAY:
                $array = \substr($raw, $start + 1);
                return \json_decode($array === false ? '[]' : $array, true);
            case CacheValueType::_NULL:
                return null;
            case CacheValueType::_JSONSERIALIZABLE:
                $namespaceStart = (int) \strpos($raw, self::DELIM, $start);
                $namespaceEnd   = (int) \strpos($raw, self::DELIM, $namespaceStart + 1);
                $namespace      = \substr($raw, $namespaceStart + 1, $namespaceEnd - $namespaceStart - 1);

                if ($namespace === false) {
                    return null;
                }

                return new $namespace();
            case CacheValueType::_SERIALIZABLE:
                $namespaceStart = (int) \strpos($raw, self::DELIM, $start);
                $namespaceEnd   = (int) \strpos($raw, self::DELIM, $namespaceStart + 1);
                $namespace      = \substr($raw, $namespaceStart + 1, $namespaceEnd - $namespaceStart - 1);

                if ($namespace === false) {
                    return null;
                }

                $obj = new $namespace();
                $obj->unserialize(\substr($raw, $namespaceEnd + 1));

                return $obj;
            default:
                return null;
        }
    }
}
