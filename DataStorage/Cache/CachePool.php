<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Cache
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache;

use phpOMS\DataStorage\Cache\Connection\ConnectionFactory;
use phpOMS\DataStorage\Cache\Connection\NullCache;
use phpOMS\DataStorage\DataStorageConnectionInterface;
use phpOMS\DataStorage\DataStoragePoolInterface;

/**
 * Cache class.
 *
 * Responsible for storing cache implementation.
 *
 * @package phpOMS\DataStorage\Cache
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CachePool implements DataStoragePoolInterface
{
    /**
     * MemCache instance.
     *
     * @var DataStorageConnectionInterface[]
     * @since 1.0.0
     */
    private array $pool = [];

    /**
     * Add database.
     *
     * @param string                         $key   Database key
     * @param DataStorageConnectionInterface $cache Cache
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function add(string $key, DataStorageConnectionInterface $cache) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = $cache;

        return true;
    }

    /**
     * Remove database.
     *
     * @param string $key Database key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(string $key) : bool
    {
        if (!isset($this->pool[$key])) {
            return false;
        }

        unset($this->pool[$key]);

        return true;
    }

    /**
     * Requesting caching instance.
     *
     * @param string $key Cache to request
     *
     * @return DataStorageConnectionInterface
     *
     * @since 1.0.0
     */
    public function get(string $key = '') : DataStorageConnectionInterface
    {
        if ((!empty($key) && !isset($this->pool[$key])) || empty($this->pool)) {
            return new NullCache();
        }

        if (empty($key)) {
            return \reset($this->pool);
        }

        return $this->pool[$key];
    }

    /**
     * Create Cache.
     *
     * @param string $key    Database key
     * @param array  $config Database config data
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function create(string $key, array $config) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = ConnectionFactory::create($config);

        return true;
    }
}
