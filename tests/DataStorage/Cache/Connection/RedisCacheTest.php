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

    protected function setUp() : void
    {
        if (!\extension_loaded('redis')) {
            $this->markTestSkipped(
              'The Redis extension is not available.'
            );
        }

        $this->cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);
    }

    protected function tearDown(): void
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
        self::assertEquals('', $this->cache->getPrefix());
        self::assertEquals(CacheType::REDIS, $this->cache->getType());
        self::assertTrue($this->cache->flushAll());
        self::assertEquals(0, $this->cache->getThreshold());
        self::assertNull($this->cache->get('test'));
        self::assertEquals(CacheStatus::OK, $this->cache->stats()['status']);
        self::assertEquals(0, $this->cache->stats()['count']);
        self::assertGreaterThanOrEqual(0, $this->cache->stats()['size']);
    }

    /**
     * @testdox The connection to a cache can be established (none-exising directories get created)
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
        self::expectException(\InvalidArgumentException::class);

        $this->cache->add('invalid', $this->cache);
    }

    /**
     * @testdox Setting a invalid data type will throw an InvalidArgumentException
     * @covers phpOMS\DataStorage\Cache\Connection\MemCached<extended>
     * @group framework
     */
    public function testInvalidDataTypeSet() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $this->cache->set('invalid', $this->cache);
    }

    /**
     * @testdox A invalid host throws a InvalidConnectionConfigException
     * @covers phpOMS\DataStorage\Cache\Connection\RedisCache<extended>
     * @group framework
     */
    public function testInvalidCacheHost() : void
    {
        self::expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

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
        self::expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

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
        self::expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['db']);

        $cache = new RedisCache($db);
    }
}
