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
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\DataStorageConnectionInterface;

/**
 * Cache interface.
 *
 * @package    phpOMS\DataStorage\Cache\Connection
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
interface ConnectionInterface extends DataStorageConnectionInterface
{

    /**
     * Updating or adding cache data.
     *
     * @param mixed $key    Unique cache key
     * @param mixed $value  Cache value
     * @param int   $expire Valid duration (in s). Negative expiration means no expiration.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function set($key, $value, int $expire = -1) : void;

    /**
     * Adding new data if it doesn't exist.
     *
     * @param mixed $key    Unique cache key
     * @param mixed $value  Cache value
     * @param int   $expire Valid duration (in s)
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function add($key, $value, int $expire = -1) : bool;

    /**
     * Get cache by key.
     *
     * @param mixed $key    Unique cache key
     * @param int   $expire Valid duration (in s). In case the data needs to be newer than the defined expiration time. If the expiration date is larger than the defined expiration time and supposed to be expired it will not remove the outdated cache.
     *
     * @return mixed Cache value
     *
     * @since  1.0.0
     */
    public function get($key, int $expire = -1);

    /**
     * Remove value by key.
     *
     * @param mixed $key    Unique cache key
     * @param int   $expire Valid duration (in s)
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function delete($key, int $expire = -1) : bool;

    /**
     * Removing all cache elements larger or equal to the expiration date. Call flushAll for removing persistent cache elements (expiration is negative) as well.
     *
     * @param int $expire Valid duration (in s)
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function flush(int $expire = 0) : bool;

    /**
     * Removing all elements from cache (invalidate cache).
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function flushAll() : bool;

    /**
     * Updating existing value/key.
     *
     * @param mixed $key    Unique cache key
     * @param mixed $value  Cache value
     * @param int   $expire Valid duration (in s)
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function replace($key, $value, int $expire = -1) : bool;

    /**
     * Requesting cache stats.
     *
     * @return mixed[] Stats array
     *
     * @since  1.0.0
     */
    public function stats() : array;

    /**
     * Get the threshold required to cache data using this cache.
     *
     * @return int Storage threshold
     *
     * @since  1.0.0
     */
    public function getThreshold() : int;
}
