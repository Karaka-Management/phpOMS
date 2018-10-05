<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\Connection\ConnectionFactory;
use phpOMS\DataStorage\Cache\CacheType;

class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\FileCache::class,
            ConnectionFactory::create(['type' => CacheType::FILE, 'path' => 'Cache'])
        );

        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\MemCached::class,
            ConnectionFactory::create(['type' => CacheType::MEMCACHED, 'data' => $GLOBALS['CONFIG']['cache']['memcached']])
        );

        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\RedisCache::class,
            ConnectionFactory::create(['type' => CacheType::REDIS, 'data' => $GLOBALS['CONFIG']['cache']['redis']])
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCacheType()
    {
        ConnectionFactory::create(['type' => 'invalid', 'path' => 'Cache']);
    }
}
