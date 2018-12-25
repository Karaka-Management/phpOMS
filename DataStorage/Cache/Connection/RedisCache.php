<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Cache\Connection
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException;

/**
 * RedisCache class.
 *
 * @package    phpOMS\DataStorage\Cache\Connection
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class RedisCache extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    protected $type = CacheType::REDIS;

    /**
     * Constructor
     *
     * @param array $data Cache data
     *
     * @since  1.0.0
     */
    public function __construct(array $data)
    {
        $this->con = new \Redis();
        $this->connect($data);
    }

    /**
     * {@inheritdoc}
     */
    public function connect(array $data) : void
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
    public function set($key, $value, int $expire = -1) : void
    {
        $value = $this->parseValue($value);

        if ($expire > 0) {
            $this->con->set($key, $value, $expire);
        }

        $this->con->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $value = $this->parseValue($value);

        if ($expire > 0) {
            return $this->con->setNx($key, $value, $expire);
        }

        return $this->con->setNx($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, int $expire = -1)
    {
        if ($this->status !== CacheStatus::OK) {
            return null;
        }

        $result = $this->con->get($key);

        return $result === false ? null : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->delete($key) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
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
    public function replace($key, $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        $value = $this->parseValue($value);

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
     * @since  1.0.0
     */
    public function __destruct()
    {
        $this->close();
    }
}
