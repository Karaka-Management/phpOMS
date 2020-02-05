<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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

/**
 * Memcache class.
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class MemCached extends ConnectionAbstract
{
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
     * @param array{host:string, port:int} $data Cache data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function connect(array $data) : void
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
     */
    public function set($key, $value, int $expire = -1) : void
    {
        if ($this->status !== CacheStatus::OK) {
            return;
        }

        if (!(\is_scalar($value) || $value === null || \is_array($value) || $value instanceof \JsonSerializable || $value instanceof \Serializable)) {
            throw new \InvalidArgumentException();
        }

        $this->con->set($key, $value, \max($expire, 0));
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        if (!(\is_scalar($value) || $value === null || \is_array($value) || $value instanceof \JsonSerializable || $value instanceof \Serializable)) {
            throw new \InvalidArgumentException();
        }

        return $this->con->add($key, $value, \max($expire, 0));
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

        if ($this->con->getResultCode() !== \Memcached::RES_SUCCESS) {
            return null;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
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
    public function replace($key, $value, int $expire = -1) : bool
    {
        if ($this->status !== CacheStatus::OK) {
            return false;
        }

        return $this->con->replace($key, $value, \max($expire, 0));
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
