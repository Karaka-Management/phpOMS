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

use phpOMS\Config\OptionsInterface;
use phpOMS\Config\OptionsTrait;


/**
 * Cache class.
 *
 * Responsible for caching scalar data types and arrays.
 * Caching HTML output and objects coming soon/is planned.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Pool implements OptionsInterface
{
    use OptionsTrait;

    /**
     * MemCache instance.
     *
     * @var \phpOMS\DataStorage\Cache\MemCache
     * @since 1.0.0
     */
    private $memc = null;

    /**
     * RedisCache instance.
     *
     * @var \phpOMS\DataStorage\Cache\RedisCache
     * @since 1.0.0
     */
    private $redisc = null;

    /**
     * RedisCache instance.
     *
     * @var \phpOMS\DataStorage\Cache\WinCache
     * @since 1.0.0
     */
    private $winc = null;

    /**
     * FileCache instance.
     *
     * @var \phpOMS\DataStorage\Cache\FileCache
     * @since 1.0.0
     */
    private $filec = null;


    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Requesting caching instance.
     *
     * @param int $type Cache to request
     *
     * @return \phpOMS\DataStorage\Cache\MemCache|\phpOMS\DataStorage\Cache\FileCache|null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get(int $type = null)
    {
        if (($type === null || $type === CacheStatus::MEMCACHE) && $this->memc !== null) {
            return $this->memc;
        }

        if (($type === null || $type === CacheStatus::REDISCACHE) && $this->redisc !== null) {
            return $this->redisc;
        }

        if (($type === null || $type === CacheStatus::WINCACHE) && $this->winc !== null) {
            return $this->winc;
        }

        if (($type === null || $type === CacheStatus::FILECACHE) && $this->filec !== null) {
            return $this->filec;
        }

        return new NullCache();
    }

    /**
     * {@inheritdoc}
     */
    public function add(int $type, CacheInterface $cache)
    {
        if (($type === null || $type === CacheStatus::MEMCACHE) && $this->memc !== null) {
            $this->memc = $cache;
        } elseif (($type === null || $type === CacheStatus::REDISCACHE) && $this->redisc !== null) {
            $this->redisc = $cache;
        } elseif (($type === null || $type === CacheStatus::WINCACHE) && $this->winc !== null) {
            $this->winc = $cache;
        } elseif (($type === null || $type === CacheStatus::FILECACHE) && $this->filec !== null) {
            $this->filec = $cache;
        } else {
            throw new \Exception('Invalid type');
        }
    }
}
