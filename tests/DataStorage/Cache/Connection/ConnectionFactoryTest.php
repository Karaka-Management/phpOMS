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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Cache\Connection\ConnectionFactoryTest: Factory for generating cache connections')]
final class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The file cache can be created')]
    public function testCreateFileCache() : void
    {
        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\Connection\FileCache::class,
            ConnectionFactory::create(['type' => CacheType::FILE, 'path' => 'Cache'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The memcached cache can be created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The redis cache can be created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid cache type results in an exception')]
    public function testInvalidCacheType() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        ConnectionFactory::create(['type' => 'invalid', 'path' => 'Cache']);
    }
}
