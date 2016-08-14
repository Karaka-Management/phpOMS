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

use phpOMS\System\File\Directory;

/**
 * MemCache class.
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
class FileCache implements CacheInterface
{

    /**
     * Cache path.
     *
     * @var string
     * @since 1.0.0
     */
    const CACHE_PATH = __DIR__ . '/../../../Cache';

    /**
     * Only cache if data is larger than threshold (0-100).
     *
     * @var int
     * @since 1.0.0
     */
    private $threshold = 50;

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, CacheStatus $type = null, int $expire = 2592000)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, CacheStatus $type = null, int $expire = 2592000) : bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, CacheStatus $type = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, CacheStatus $type = null) : bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flush(CacheStatus $type = null)
    {
        array_map('unlink', glob(self::CACHE_PATH . '/*'));
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, CacheType $type = null, int $expire = -1) : bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        $stats          = [];
        $stats['count'] = Directory::getFileCount(self::CACHE_PATH);

        // size, avg. last change compared to now

        return $stats;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        return $this->threshold;
    }

}
