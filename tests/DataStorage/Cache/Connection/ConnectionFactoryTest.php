<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\ConnectionFactory;

/**
 * @internal
 */
class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateFileCache() : void
    {
        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\FileCache::class,
            ConnectionFactory::create(['type' => CacheType::FILE, 'path' => 'Cache'])
        );
    }

    public function testCreateMemCached() : void
    {
        if (!\extension_loaded('memcached')) {
            $this->markTestSkipped(
              'The Memcached extension is not available.'
            );
        }

        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\MemCached::class,
            ConnectionFactory::create(['type' => CacheType::MEMCACHED, 'data' => $GLOBALS['CONFIG']['cache']['memcached']])
        );
    }

    public function testCreateRedisCache() : void
    {
        if (!\extension_loaded('redis')) {
            $this->markTestSkipped(
              'The Redis extension is not available.'
            );
        }

        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\RedisCache::class,
            ConnectionFactory::create(['type' => CacheType::REDIS, 'data' => $GLOBALS['CONFIG']['cache']['redis']])
        );
    }

    public function testInvalidCacheType() : void
    {
        self::expectException(\InvalidArgumentException::class);

        ConnectionFactory::create(['type' => 'invalid', 'path' => 'Cache']);
    }
}
