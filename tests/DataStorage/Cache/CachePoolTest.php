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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Cache\Connection\FileCache;

/**
 * @testdox phpOMS\tests\DataStorage\Cache\CachePoolTest: Pool for caches
 *
 * @internal
 */
class CachePoolTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The pool has the expected default values after initialization
     */
    public function testDefault() : void
    {
        $pool = new CachePool();

        self::assertFalse($pool->remove('core'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get());
    }

    /**
     * @testdox New cache connections can be added to the pool
     */
    public function testAdd() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
    }

    /**
     * @testdox Cache connections cannot be overwritten with a different cache connection
     */
    public function testOverwrite() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->add('test', new FileCache(__DIR__)));
    }

    /**
     * @testdox Cache connections can be accessed with an identifier
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
     */
    public function testGetDefault() : void
    {
        $pool = new CachePool();

        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get('abc'));
    }

    /**
     * @testdox Cache connections can created by the pool and automatically get added but not overwritten
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
     */
    public function testRemoveInvalid() : void
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->remove('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
    }
}
