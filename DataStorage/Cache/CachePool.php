<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Cache
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache;

use phpOMS\DataStorage\DataStoragePoolInterface;
use phpOMS\DataStorage\DataStorageConnectionInterface;
use phpOMS\DataStorage\Cache\Connection\ConnectionFactory;
use phpOMS\DataStorage\Cache\Connection\NullCache;

/**
 * Cache class.
 *
 * Responsible for caching scalar data types and arrays.
 * Caching HTML output and objects coming soon/is planned.
 *
 * @package    phpOMS\DataStorage\Cache
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class CachePool implements DataStoragePoolInterface
{
    /**
     * MemCache instance.
     *
     * @var DataStorageConnectionInterface[]
     * @since 1.0.0
     */
    private $pool = null;

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Add database.
     *
     * @param string                         $key   Database key
     * @param DataStorageConnectionInterface $cache Cache
     *
     * @return bool
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
