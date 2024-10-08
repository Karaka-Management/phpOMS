<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\MemCached;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Cache\Connection\MemCached::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Cache\Connection\MemCachedTest: Memcache connection')]
final class MemCachedTest extends \PHPUnit\Framework\TestCase
{
    protected MemCached $cache;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (!\extension_loaded('memcached')) {
            $this->markTestSkipped(
              'The Memcached extension is not available.'
            );
        }

        $this->cache = new MemCached($GLOBALS['CONFIG']['cache']['memcached']);
    }

    protected function tearDown() : void
    {
        if (!isset($this->cache)) {
            return;
        }

        $this->cache->flushAll();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The memcached connection has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals(CacheType::MEMCACHED, $this->cache->getType());
        self::assertTrue($this->cache->flushAll());
        self::assertEquals(0, $this->cache->getThreshold());
        self::assertNull($this->cache->get('test'));
        self::assertEquals('', $this->cache->getCache());
        self::assertEquals('127.0.0.1', $this->cache->getHost());
        self::assertEquals(11211, $this->cache->getPort());
        self::assertEquals(
            [
                'status' => CacheStatus::OK,
                'count'  => 0,
                'size'   => 0,
            ],
            $this->cache->stats()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The connection to a cache can be established (none-existing directories get created)')]
    public function testConnect() : void
    {
        $cache = new MemCached($GLOBALS['CONFIG']['cache']['memcached']);

        self::assertEquals(CacheStatus::OK, $cache->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['cache']['memcached']['host'], $cache->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['cache']['memcached']['port'], $cache->getPort());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different cache data (types) can be set and returned')]
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

        /*
        @todo It doesn't know that it has to use unserialize
        $this->cache->set('key7', new FileCacheSerializable());
        self::assertEquals('abc', $this->cache->get('key7')->val);

        $this->cache->set('key8', new FileCacheJsonSerializable());
        self::assertEquals('asdf', $this->cache->get('key8')->val);
        */
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache data can bet added and returned')]
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
        self::assertTrue($this->cache->exists('key2', 0));
        \sleep(3);
        self::assertFalse($this->cache->exists('key2'));
    }

    public function testExistsInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->exists('invalid'));
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

    public function testIncrementInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->increment('invalid', 2));
    }

    public function testDecrement() : void
    {
        $this->cache->set(1, 3);
        self::assertTrue($this->cache->decrement(1, 2));
        self::assertEquals(1, $this->cache->get(1));
    }

    public function testDecrementInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->decrement('invalid', 2));
    }

    public function testInvalidKeyDecrement() : void
    {
        self::assertFalse($this->cache->decrement('invalid', 2));
    }

    public function testRename() : void
    {
        $this->cache->set('a', 'testVal1');
        self::assertTrue($this->cache->rename('a', 'b'));
        self::assertEquals('testVal1', $this->cache->get('b'));
    }

    public function testRenameInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->rename('old', 'new'));
    }

    public function testUpdateExpire() : void
    {
        $this->cache->set('key2', 'testVal2', 10);
        self::assertEquals('testVal2', $this->cache->get('key2', 1));
        \sleep(2);
        self::assertTrue($this->cache->updateExpire('key2', 30));
        self::assertEquals('testVal2', $this->cache->get('key2'));
    }

    public function testUpdateExpireInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->updateExpire('invalid', 2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache data cannot be added if it already exists')]
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->cache->add('addKey', 'testValAdd'));
        self::assertFalse($this->cache->add('addKey', 'testValAdd2'));
        self::assertEquals('testValAdd', $this->cache->get('addKey'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Existing cache data can be replaced')]
    public function testReplace() : void
    {
        $this->cache->set('key4', 4);
        self::assertEquals(4, $this->cache->get('key4'));

        self::assertTrue($this->cache->replace('key4', 5));
        self::assertEquals(5, $this->cache->get('key4'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing cache data cannot be replaced')]
    public function testInvalidReplace() : void
    {
        self::assertFalse($this->cache->replace('keyInvalid', 5));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Existing cache data can be deleted')]
    public function testDelete() : void
    {
        $this->cache->set('key4', 4);
        self::assertEquals(4, $this->cache->get('key4'));

        self::assertTrue($this->cache->delete('key4'));
        self::assertNull($this->cache->get('key4'));
    }

    public function testDeleteInvalidStatus() : void
    {
        TestUtils::setMember($this->cache, 'status', CacheStatus::FAILURE);
        self::assertFalse($this->cache->delete('invalid', 2));
    }

    public function testInvalidKeyDelete() : void
    {
        self::assertTrue($this->cache->delete('invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cache correctly handles general cache information')]
    public function testStats() : void
    {
        $this->cache->set('key1', 'testVal');
        self::assertEquals('testVal', $this->cache->get('key1'));

        $this->cache->set('key2', false);
        self::assertFalse($this->cache->get('key2'));

        self::assertGreaterThan(0, $this->cache->stats()['count']);
        self::assertGreaterThan(0, $this->cache->stats()['size']);
        /*
        self::assertEquals(
            [
                'status'  => CacheStatus::OK,
                'count'   => 2,
                'size'    => 137,
            ],
            $this->cache->stats()
        );
        */
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cache can be flushed')]
    public function testFlush() : void
    {
        $this->cache->set('key1', 'testVal');
        self::assertEquals('testVal', $this->cache->get('key1'));

        $this->cache->set('key2', false);
        self::assertFalse($this->cache->get('key2'));

        self::assertTrue($this->cache->flushAll());
        self::assertNull($this->cache->get('key5'));

        // Careful memcached is dumb and keeps expired elements which were not acessed after flushing in stat
        self::assertGreaterThanOrEqual(0, $this->cache->stats()['count']);
        self::assertGreaterThanOrEqual(0, $this->cache->stats()['size']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache data can be set and returned with expiration limits')]
    public function testUnexpiredInputOutput() : void
    {
        $this->cache->set('key1', 'testVal', 1);
        self::assertEquals('testVal', $this->cache->get('key1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Expired cache data cannot be returned')]
    public function testExpiredInputOutput() : void
    {
        $this->cache->set('key2', 'testVal2', 1);
        self::assertEquals('testVal2', $this->cache->get('key2', 1));
        \sleep(2);
        self::assertNull($this->cache->get('key2', 1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Cache data can be flushed by expiration date')]
    public function testFlushExpired() : void
    {
        $this->cache->set('key6', 'testVal6', 1);
        \sleep(2);

        $this->cache->flush(0);
        self::assertNull($this->cache->get('key6', 0));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A bad cache status will prevent all cache actions')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Adding a invalid data type will throw an InvalidArgumentException')]
    public function testInvalidDataTypeAdd() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cache->add('invalid', $this->cache);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Setting a invalid data type will throw an InvalidArgumentException')]
    public function testInvalidDataTypeSet() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cache->set('invalid', $this->cache);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid host throws a InvalidConnectionConfigException')]
    public function testInvalidCacheHost() : void
    {
        $this->expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['cache']['memcached'];
        unset($db['host']);

        $cache = new MemCached($db);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid port throws a InvalidConnectionConfigException')]
    public function testInvalidCachePort() : void
    {
        $this->expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $db = $GLOBALS['CONFIG']['cache']['memcached'];
        unset($db['port']);

        $cache = new MemCached($db);
    }
}
