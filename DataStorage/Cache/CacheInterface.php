<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\DataStorage\Cache;

/**
 * Cache interface.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface CacheInterface
{

    /**
     * Updating or adding cache data.
     *
     * @param mixed       $key    Unique cache key
     * @param mixed       $value  Cache value
     * @param CacheStatus $type   Cache type
     * @param int         $expire Valid duration (in s)
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set($key, $value, CacheStatus $type = null, int $expire = 2592000);

    /**
     * Adding new data if it doesn't exist.
     *
     * @param mixed       $key    Unique cache key
     * @param mixed       $value  Cache value
     * @param CacheStatus $type   Cache type
     * @param int         $expire Valid duration (in s)
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add($key, $value, CacheStatus $type = null, int $expire = 2592000) : bool;

    /**
     * Get cache by key.
     *
     * @param mixed       $key  Unique cache key
     * @param CacheStatus $type Cache status/type
     *
     * @return mixed Cache value
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get($key, CacheStatus $type = null);

    /**
     * Remove value by key.
     *
     * @param mixed       $key  Unique cache key
     * @param CacheStatus $type Cache status/type
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function delete($key, CacheStatus $type = null) : bool;

    /**
     * Removing all elements from cache (invalidate cache).
     *
     * @param CacheStatus $type Cache status/type
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function flush(CacheStatus $type = null);

    /**
     * Updating existing value/key.
     *
     * @param mixed     $key    Unique cache key
     * @param mixed     $value  Cache value
     * @param CacheType $type   Cache type
     * @param  int      $expire Timestamp
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function replace($key, $value, CacheType $type = null, int $expire = -1) : bool;

    /**
     * Requesting cache stats.
     *
     * @return mixed[] Stats array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function stats() : array;

    /**
     * Get the threshold required to cache data using this cache.
     *
     * @return int Storage threshold
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getThreshold() : int;

}
