<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\RedisCache;
use phpOMS\Utils\TestUtils;

/**
 * @testdox phpOMS\tests\DataStorage\Cache\Connection\RedisCacheTest: Redis cache connection
 *
 * @internal
 */
class RedisCacheTest extends \PHPUnit\Framework\TestCase
{
    protected RedisCache $cache;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (!\extension_loaded('redis')) {
            $this->markTestSkipped(
              'The Redis extension is not available.'
            );
        }

        $this->cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);
    }

    protected function tearDown() : void
    {
        $this->cache->flushAll();
    }

    /**
     * @testdox The redis cache connection has the expected default values after initialization
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals(CacheType::REDIS, $this->cache->getType());
        self::assertTrue($this->cache->flushAll());
        self::assertEquals(0, $this->cache->getThreshold());
        self::assertNull($this->cache->get('test'));
        self::assertEquals(CacheStatus::OK, $this->cache->stats()['status']);
        self::assertEquals(0, $this->cache->stats()['count']);
        self::assertGreaterThanOrEqual(0, $this->cache->stats()['size']);
        self::assertEquals('1', $this->cache->getCache());
        self::assertEquals('127.0.0.1', $this->cache->getHost());
        self::assertEquals(6379, $this->cache->getPort());
    }

    /**
     * @testdox The connection to a cache can be established (none-existing directories get created)
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testConnect() : void
    {
        $cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);

        self::assertEquals(CacheStatus::OK, $cache->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['cache']['redis']['db'], $cache->getCache());
        self::assertEquals($GLOBALS['CONFIG']['cache']['redis']['host'], $cache->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['cache']['redis']['port'], $cache->getPort());
    }

    /**
     * @testdox Different cache data (types) can be set and returned
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testSetInputOutput() : void
    {
        $this->cache->set('key1', 'testVal');
        self::assertEquals('testVal', $this->cache->get('key1'));

        $this->cache->set('key2', false);
        self::assertFalse($this->cache->get('key2'));

        $this->cache->set('key3', null);
        self::assertNull($this->cache->get('key3'));

        $this->cache->set('key4', 4);
        self::assertEquals(4, $this->cache->get('key4'));

        $this->cache->set('key5', 5.12);
        self::assertEquals(5.12, $this->cache->get('key5'));

        $this->cache->set('key6', ['asdf', 1, true, 2.3]);
        self::assertEquals(['asdf', 1, true, 2.3], $this->cache->get('key6'));

        $this->cache->set('key7', new FileCacheSerializable());
        self::assertEquals('abc', $this->cache->get('key7')->val);

        $this->cache->set('key8', new FileCacheJsonSerializable());
        self::assertEquals('asdf', $this->cache->get('key8')->val);
    }

    /**
     * @testdox Cache data can bet added and returned
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testAddInputOutput() : void
    {
        self::assertTrue($this->cache->add('addKey', 'testValAdd'));
        self::assertEquals('testValAdd', $this->cache->get('addKey'));
    }

    public function testExists() : void
    {
        self::assertTrue($this->cache->add('addKey', 'testValAdd'));
        self::assertTrue($this->cache->exists('addKey'));
    }

    public function testExpiredExists() : void
    {
        $this->cache->set('key2', 'testVal2', 2);
        \sleep(1);
        self::assertTrue($this->cache->exists('key2'));
        self::assertFalse($this->cache->exists('key2', 0));
        \sleep(3);
        self::assertFalse($this->cache->exists('key2'));
    }

    public function testExistsInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->exists('invalid'));
    }

    public function testGetLike() : void
    {
        $this->cache->set('key1', 'testVal1');
        $this->cache->set('key2', 'testVal2');
        self::assertEquals(['testVal1', 'testVal2'], $this->cache->getLike('key\d'));
    }

    public function testExpiredGetLike() : void
    {
        $this->cache->set('key1', 'testVal1', 2);
        $this->cache->set('key2', 'testVal2', 2);
        \sleep(1);
        self::assertEquals([], \array_diff(['testVal1', 'testVal2'], $this->cache->getLike('key\d')));
        self::assertEquals([], $this->cache->getLike('key\d', 0));
        \sleep(3);
        self::assertEquals([], $this->cache->getLike('key\d'));
    }

    public function testGetLikeInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertEquals([], $this->cache->getLike('key\d'));
    }

    public function testIncrement() : void
    {
        $this->cache->set(1, 1);
        self::assertTrue($this->cache->increment(1, 2));
        self::assertEquals(3, $this->cache->get(1));
    }

    public function testInvalidKeyIncrement() : void
    {
        self::assertFalse($this->cache->increment('invalid', 2));
    }

    public function testDecrement() : void
    {
        $this->cache->set(1, 3);
        self::assertTrue($this->cache->decrement(1, 2));
        self::assertEquals(1, $this->cache->get(1));
    }

    public function testInvalidKeyDecrement() : void
    {
        self::assertFalse($this->cache->decrement('invalid', 2));
    }

    public function testRename() : void
    {
        $this->cache->set('a', 'testVal1');
        $this->cache->rename('a', 'b');
        self::assertEquals('testVal1', $this->cache->get('b'));
    }

    public function testDeleteLike() : void
    {
        $this->cache->set('key1', 'testVal1');
        $this->cache->set('key2', 'testVal2');
        self::assertTrue($this->cache->deleteLike('key\d'));
        self::assertEquals([], $this->cache->getLike('key\d'));
    }

    public function testDeleteLikeInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->deleteLike('key\d'));
    }

    public function testUpdateExpire() : void
    {
        $this->cache->set('key2', 'testVal2', 1);
        self::assertEquals('testVal2', $this->cache->get('key2', 1));
        \sleep(2);
        self::assertTrue($this->cache->updateExpire('key2', \time() + 10000));
        self::assertEquals('testVal2', $this->cache->get('key2'));
    }

    /**
     * @testdox Cache data cannot be added if it already exists
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->cache->add('addKey', 'testValAdd'));
        self::assertFalse($this->cache->add('addKey', 'testValAdd2'));
        self::assertEquals('testValAdd', $this->cache->get('addKey'));
    }

    /**
     * @testdox Existing cache data can be replaced
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testReplace() : void
    {
        $this->cache->set('key4', 4);
        self::assertEquals(4, $this->cache->get('key4'));

        self::assertTrue($this->cache->replace('key4', 5));
        self::assertEquals(5, $this->cache->get('key4'));
    }

    /**
     * @testdox None-existing cache data cannot be replaced
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testInvalidReplace() : void
    {
        self::assertFalse($this->cache->replace('keyInvalid', 5));
    }

    /**
     * @testdox Existing cache data can be deleted
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testDelete() : void
    {
        $this->cache->set('key4', 4);
        self::assertEquals(4, $this->cache->get('key4'));

        self::assertTrue($this->cache->delete('key4'));
        self::assertNull($this->cache->get('key4'));
    }

    public function testInvalidKeyDelete() : void
    {
        self::assertTrue($this->cache->delete('invalid'));
    }

    /**
     * @testdox The cache correctly handles general cache information
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testStats() : void
    {
        $this->cache->set('key1', 'testVal');
        self::assertEquals('testVal', $this->cache->get('key1'));

        $this->cache->set('key2', false);
        self::assertFalse($this->cache->get('key2'));

        self::assertGreaterThan(0, $this->cache->stats()['count']);
        self::assertGreaterThan(0, $this->cache->stats()['size']);
    }

    /**
     * @testdox The cache can be flushed
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testFlush() : void
    {
        $this->cache->set('key1', 'testVal');
        self::assertEquals('testVal', $this->cache->get('key1'));

        $this->cache->set('key2', false);
        self::assertFalse($this->cache->get('key2'));

        self::assertTrue($this->cache->flushAll());
        self::assertNull($this->cache->get('key5'));

        self::assertEquals(CacheStatus::OK, $this->cache->stats()['status']);
        self::assertEquals(0, $this->cache->stats()['count']);
        self::assertGreaterThanOrEqual(0, $this->cache->stats()['size']);
    }

    /**
     * @testdox Cache data can be set and returned with expiration limits
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testUnexpiredInputOutput() : void
    {
        $this->cache->set('key1', 'testVal', 1);
        self::assertEquals('testVal', $this->cache->get('key1'));
    }

    /**
     * @testdox Expired cache data cannot be returned
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testExpiredInputOutput() : void
    {
        $this->cache->set('key2', 'testVal2', 1);
        self::assertEquals('testVal2', $this->cache->get('key2', 1));
        \sleep(2);
        self::assertNull($this->cache->get('key2', 1));
    }

    /**
     * @testdox A bad cache status will prevent all cache actions
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testBadCacheStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);

        $this->cache->set('key1', 'testVal');
        self::assertFalse($this->cache->add('key2', 'testVal2'));
        self::assertNull($this->cache->get('key1'));
        self::assertFalse($this->cache->replace('key1', 5));
        self::assertFalse($this->cache->delete('key1'));
        self::assertFalse($this->cache->flushAll());
        self::assertFalse($this->cache->flush());
        self::assertEquals([], $this->cache->stats());
    }

    /**
     * @testdox Adding a invalid data type will throw an InvalidArgumentException
     * @covers phpOMS\DataStorage\Cache\Connection\MemCached<extended>
     * @group framework
     */
    public function testInvalidDataTypeAdd() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cache->add('invalid', $this->cache);
    }

    /**
     * @testdox Setting a invalid data type will throw an InvalidArgumentException
     * @covers phpOMS\DataStorage\Cache\Connection\MemCached<extended>
     * @group framework
     */
    public function testInvalidDataTypeSet() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cache->set('invalid', $this->cache);
    }

    /**
     * @testdox A invalid host throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testInvalidCacheHost() : void
    {
        $this->expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['host']);

        $cache = new RedisCache($db);
    }

    /**
     * @testdox A invalid port throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testInvalidCachePort() : void
    {
        $this->expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['port']);

        $cache = new RedisCache($db);
    }

    /**
     * @testdox A invalid database throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testInvalidCacheDatabase() : void
    {
        $this->expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['db']);

        $cache = new RedisCache($db);
    }
}
