<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\RedisCache;
use phpOMS\Utils\TestUtils;

class RedisCacheTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped(
              'The Redis extension is not available.'
            );
        }
    }

    public function testDefault() : void
    {
        $cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);

        self::assertEquals('', $cache->getPrefix());
        self::assertEquals(CacheType::REDIS, $cache->getType());
        self::assertTrue($cache->flushAll());
        self::assertTrue($cache->flush());
        self::assertEquals(0, $cache->getThreshold());
        self::assertEquals(null, $cache->get('test'));
    }

    public function testConnect() : void
    {
        $cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);

        self::assertEquals(CacheStatus::OK, $cache->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['cache']['redis']['db'], $cache->getCache());
        self::assertEquals($GLOBALS['CONFIG']['cache']['redis']['host'], $cache->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['cache']['redis']['port'], $cache->getPort());
    }

    public function testGetSet() : void
    {
        $cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);

        $cache->flushAll();

        $cache->set('key1', 'testVal'); // 1
        self::assertEquals('testVal', $cache->get('key1'));

        self::assertTrue($cache->add('addKey', 'testValAdd')); // 2
        self::assertFalse($cache->add('addKey', 'testValAdd2'));
        self::assertEquals('testValAdd', $cache->get('addKey'));

        $cache->set('key2', false); // 3
        self::assertEquals(false, $cache->get('key2'));

        $cache->set('key3', null); // 4
        self::assertEquals(null, $cache->get('key3'));

        $cache->set('key4', 4); // 5
        self::assertEquals(4, $cache->get('key4'));

        $cache->set('key5', 5.12); // 6
        self::assertEquals(5.12, $cache->get('key5'));

        $cache->set('key6', ['asdf', 1, true, 2.3]); // 7
        self::assertEquals(json_encode(['asdf', 1, true, 2.3]), $cache->get('key6'));

        self::assertTrue($cache->replace('key4', 5));
        self::assertFalse($cache->replace('keyInvalid', 5));
        self::assertEquals(5, $cache->get('key4'));

        self::assertTrue($cache->delete('key4')); // 6
        self::assertFalse($cache->delete('keyInvalid'));
        self::assertEquals(null, $cache->get('key4'));

        self::assertArraySubset(
            [
                'status'  => CacheStatus::OK,
                'count'   => 6,
            ],
            $cache->stats()
        );

        self::assertTrue($cache->flushAll());
        self::assertTrue($cache->flush());
        self::assertEquals(null, $cache->get('key5'));

        $cache->flushAll();

        self::assertArraySubset(
            [
                'status'  => CacheStatus::OK,
                'count'   => 0,
            ],
            $cache->stats()
        );
    }

    public function testBadCacheStatus() : void
    {
        $cache = new RedisCache($GLOBALS['CONFIG']['cache']['redis']);
        $cache->flushAll();

        TestUtils::setMember($cache, 'status', CacheStatus::FAILURE);

        $cache->set('key1', 'testVal');
        self::assertFalse($cache->add('key2', 'testVal2'));
        self::assertEquals(null, $cache->get('key1'));
        self::assertFalse($cache->replace('key1', 5));
        self::assertFalse($cache->delete('key1'));
        self::assertFalse($cache->flushAll());
        self::assertFalse($cache->flush());
        self::assertEquals([], $cache->stats());
    }

    /**
     * @expectedException \phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException
     */
    public function testInvalidCacheHost() : void
    {
        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['host']);

        $cache = new RedisCache($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException
     */
    public function testInvalidCachePort() : void
    {
        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['port']);

        $cache = new RedisCache($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException
     */
    public function testInvalidCacheDatabase() : void
    {
        $db = $GLOBALS['CONFIG']['cache']['redis'];
        unset($db['db']);

        $cache = new RedisCache($db);
    }
}
