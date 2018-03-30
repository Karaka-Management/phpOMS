<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Cache\Connection\FileCache;

class CachePoolTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $pool = new CachePool();

        self::assertFalse($pool->remove('core'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get());
    }

    public function testGetSet()
    {
        $pool = new CachePool();

        self::assertTrue($pool->add('test', new FileCache(__DIR__)));
        self::assertFalse($pool->add('test', new FileCache(__DIR__)));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get());
        self::assertTrue($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertFalse($pool->create('abc', ['type' => 'file', 'path' => __DIR__]));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('abc'));
        self::assertTrue($pool->remove('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\NullCache', $pool->get('abc'));
        self::assertInstanceOf('\phpOMS\DataStorage\Cache\Connection\ConnectionInterface', $pool->get('test'));
        self::assertFalse($pool->remove('abc'));
    }
}
