<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Cache\Connection\FileCache;

/**
 * @testdox phpOMS\tests\DataStorage\Cache\CachePoolTest: Pool for caches
 *
 * @internal
 */
final class CachePoolTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The pool has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        $pool = new CachePool();

        self::assertFalse($pool->remove('core'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get());
    }

    /**
     * @testdox New cache connections can be added to the pool
     * @group framework
     */
    public function testAdd() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
    }

    /**
     * @testdox Cache connections cannot be overwritten with a different cache connection
     * @group framework
     */
    public function testOverwrite() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->add('test', new FileCache(__DIR__)));
    }

    /**
     * @testdox Cache connections can be accessed with an identifier
     * @group framework
     */
    public function testGet() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get());
    }

    /**
     * @testdox By default a null cache is returned if no cache connection exists for the identifier
     * @group framework
     */
    public function testGetDefault() : void
    {
        $pool = new CachePool();

        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get('abc'));
    }

    /**
     * @testdox Cache connections can created by the pool and automatically get added but not overwritten
     * @group framework
     */
    public function testCreate() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertFalse($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('abc'));
    }

    /**
     * @testdox Cache connections can be removed from the pool
     * @group framework
     */
    public function testRemove() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertTrue($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertTrue($pool->remove('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
    }

    /**
     * @testdox Removing a cache with an invalid identifier will result in no actions
     * @group framework
     */
    public function testRemoveInvalid() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->remove('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
    }
}
