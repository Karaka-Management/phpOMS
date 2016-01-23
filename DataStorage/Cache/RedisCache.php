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
 * RedisCache class.
 *
 * PHP Version 5.6
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class RedisCache implements CacheInterface
{

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, CacheStatus $type = null, int $expire = 2592000)
    {
        // TODO: Implement set() method.
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, CacheStatus $type = null, int $expire = 2592000)
    {
        // TODO: Implement add() method.
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, CacheStatus $type = null)
    {
        // TODO: Implement get() method.
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, CacheStatus $type = null)
    {
        // TODO: Implement delete() method.
    }

    /**
     * {@inheritdoc}
     */
    public function flush(CacheStatus $type = null)
    {
        // TODO: Implement flush() method.
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, CacheType $type = null, int $expire = -1)
    {
        // TODO: Implement replace() method.
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        // TODO: Implement stats() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        // TODO: Implement getThreshold() method.
    }

}
