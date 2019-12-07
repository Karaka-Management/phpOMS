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
class MemCached extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    protected string $type = CacheType::MEMCACHED;

    /**
     * Constructor.
     *
     * @param array $data Cache data
     *
     * @since 1.0.0
     */
    public function __construct(array $data)
    {
        $this->con = new \Memcached();
        $this->connect($data);
    }

    /**
     * {@inheritdoc}
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
        $value = $this->parseValue($value);

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

        $value = $this->parseValue($value);

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

        $value = $this->parseValue($value);

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
