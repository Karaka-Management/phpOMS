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
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheType;

/**
 * Cache connection factory.
 *
 * @package    phpOMS\DataStorage\Cache\Connection
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class ConnectionFactory
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Create cache connection.
     *
     * @param string[] $cacheData the basic cache information for establishing a connection
     *
     * @return ConnectionInterface
     *
     * @throws \InvalidArgumentException Throws this exception if the cache is not supported.
     *
     * @since  1.0.0
     */
    public static function create(array $cacheData) : ConnectionInterface
    {
        switch ($cacheData['type'] ?? '') {
            case CacheType::FILE:
                return new FileCache($cacheData['path'] ?? '');
            case CacheType::REDIS:
                return new RedisCache($cacheData['data'] ?? []);
            case CacheType::MEMCACHED:
                return new MemCached($cacheData['data'] ?? []);
            default:
                throw new \InvalidArgumentException('Cache "' . ($cacheData['type'] ?? '') . '" is not supported.');
        }
    }
}
