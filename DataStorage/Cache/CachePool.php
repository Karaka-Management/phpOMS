<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache;

use phpOMS\Config\OptionsInterface;
use phpOMS\Config\OptionsTrait;
use phpOMS\DataStorage\Cache\CacheFactory;


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
class CachePool implements OptionsInterface
{
    use OptionsTrait;

    /**
     * MemCache instance.
     *
     * @var \phpOMS\DataStorage\Cache\CacheInterface
     * @since 1.0.0
     */
    private $pool = null;


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
     * Add database.
     *
     * @param mixed          $key   Database key
     * @param CacheInterface $cache Cache
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add(string $key = 'core', CacheInterface $cache) : bool
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
     * @param mixed $key Database key
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @return \phpOMS\DataStorage\Cache\CacheInterface
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get(string $key)
    {
        if (!isset($this->pool[$key])) {
            return false;
        }

        return $this->pool[$key];
    }

    /**
     * Create Cache.
     *
     * @param mixed $key    Database key
     * @param array $config Database config data
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function create(string $key, array $config) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = CacheFactory::create($config);

        return true;
    }
}
