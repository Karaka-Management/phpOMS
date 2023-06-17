<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\ConnectionFactory;

/**
 * @testdox phpOMS\tests\DataStorage\Cache\Connection\ConnectionFactoryTest: Factory for generating cache connections
 *
 * @internal
 */
final class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The file cache can be created
     * @group framework
     */
    public function testCreateFileCache() : void
    {
        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\FileCache::class,
            ConnectionFactory::create(['type' => CacheType::FILE, 'path' => 'Cache'])
        );
    }

    /**
     * @testdox The memcached cache can be created
     * @group framework
     */
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

    /**
     * @testdox The redis cache can be created
     * @group framework
     */
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

    /**
     * @testdox A invalid cache type results in an exception
     * @group framework
     */
    public function testInvalidCacheType() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        ConnectionFactory::create(['type' => 'invalid', 'path' => 'Cache']);
    }
}
