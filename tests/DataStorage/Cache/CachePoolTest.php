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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Cache\Connection\FileCache;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Cache\CachePoolTest: Pool for caches')]
final class CachePoolTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The pool has the expected default values after initialization')]
    public function testDefault() : void
    {
        $pool = new CachePool();

        self::assertFalse($pool->remove('core'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('New cache connections can be added to the pool')]
    public function testAdd() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache connections cannot be overwritten with a different cache connection')]
    public function testOverwrite() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->add('test', new FileCache(__DIR__)));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache connections can be accessed with an identifier')]
    public function testGet() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default a null cache is returned if no cache connection exists for the identifier')]
    public function testGetDefault() : void
    {
        $pool = new CachePool();

        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache connections can created by the pool and automatically get added but not overwritten')]
    public function testCreate() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertFalse($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache connections can be removed from the pool')]
    public function testRemove() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertTrue($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertTrue($pool->remove('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Removing a cache with an invalid identifier will result in no actions')]
    public function testRemoveInvalid() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->remove('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
    }
}
