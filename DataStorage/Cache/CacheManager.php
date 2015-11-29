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


use phpOMS\DataStorage\Database\Pool;

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
class CacheManager implements OptionsInterface
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
     * FileCache instance.
     *
     * @var Pool
     * @since 1.0.0
     */
    private $dbPool = null;

    /**
     * Constructor.
     *
     * @param Pool $dbPool Database pool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(Pool $dbPool)
    {
        $this->dbPool = $dbPool;
    }

    /**
     * Init cache.
     *
     * @param mixed $options Options used to initialize the different caching types
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function init($options = null)
    {
        if ($options === null) {
            /* This is costing me 1ms, maybe init settings first cause i'm making another settings call later on -> same call 2 times */
            $sth = $this->dbPool->get('core')->con->prepare('SELECT `content` FROM `' . $this->dbPool->get('core')->prefix . 'settings` WHERE `id` = 1000000015');
            $sth->execute();
            $cache_data = $sth->fetchAll();

            $this->setOption('cache:type', (int) $cache_data[0][0]);
        } else {
            $this->options = $options;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, CacheStatus $type = null, \int $expire = 2592000)
    {
        $this->getInstance($type)->set($key, $value, $type = null, $expire);
    }

    /**
     * Requesting caching instance.
     *
     * @param CacheStatus $type Cache to request
     *
     * @return \phpOMS\DataStorage\Cache\MemCache|\phpOMS\DataStorage\Cache\FileCache|null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getInstance(CacheStatus $type = null)
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
    public function add($key, $value, CacheStatus $type = null, \int $expire = 2592000)
    {
        $this->getInstance($type)->add($key, $value, $type = null, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, CacheStatus $type = null)
    {
        return $this->getInstance($type)->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, CacheStatus $type = null)
    {
        $this->getInstance($type)->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush(CacheStatus $type = null)
    {
        if ($type === null) {
            $this->filec->flush();
            $this->memc->flush();
        } elseif ($type === CacheStatus::MEMCACHE) {
            $this->memc->flush();
        } elseif ($type === CacheStatus::FILECACHE) {
            $this->filec->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, CacheType $type = null)
    {
        $this->getInstance($type)->replace($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        $stats = [];

        if ($this->memc !== null) {
            $stats['memc'] = $this->memc->stats();
        }

        if ($this->filec !== null) {
            $stats['filec'] = $this->filec->stats();
        }

        return $stats;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : array
    {
        $threshold = [];

        if ($this->memc !== null) {
            $threshold['memc'] = $this->memc->getThreshold();
        }

        if ($this->filec !== null) {
            $threshold['filec'] = $this->filec->getThreshold();
        }

        return $threshold;
    }

}
