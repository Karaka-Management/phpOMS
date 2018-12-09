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
use phpOMS\DataStorage\Cache\Connection\FileCache;
use phpOMS\Utils\TestUtils;

class FileCacheTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');

        self::assertEquals('', $cache->getPrefix());
        self::assertEquals(CacheType::FILE, $cache->getType());
        self::assertTrue(\is_dir(__DIR__ . '/Cache'));
        self::assertTrue($cache->flushAll());
        self::assertEquals(50, $cache->getThreshold());
        self::assertEquals(null, $cache->get('test'));

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    public function testConnect()
    {
        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');

        self::assertEquals(CacheStatus::OK, $cache->getStatus());

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    public function testGetSet()
    {
        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');

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
        self::assertEquals(['asdf', 1, true, 2.3], $cache->get('key6'));

        self::assertTrue($cache->replace('key4', 5));
        self::assertFalse($cache->replace('keyInvalid', 5));
        self::assertEquals(5, $cache->get('key4'));

        self::assertTrue($cache->delete('key4')); // 6
        self::assertFalse($cache->delete('keyInvalid'));
        self::assertEquals(null, $cache->get('key4'));

        self::assertEquals(
            [
                'status'  => CacheStatus::OK,
                'count'   => 6,
                'size'    => 70,
            ],
            $cache->stats()
        );

        self::assertTrue($cache->flushAll());
        self::assertEquals(null, $cache->get('key5'));

        $cache->flushAll();

        self::assertEquals(
            [
                'status'  => CacheStatus::OK,
                'count'   => 0,
                'size'    => 0,
            ],
            $cache->stats()
        );

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    public function testBadCacheStatus()
    {
        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');
        $cache->flushAll();

        TestUtils::setMember($cache, 'status', CacheStatus::FAILURE);

        $cache->set('key1', 'testVal');
        self::assertFalse($cache->add('key2', 'testVal2'));
        self::assertEquals(null, $cache->get('key1'));
        self::assertFalse($cache->replace('key1', 5));
        self::assertFalse($cache->delete('key1'));
        self::assertFalse($cache->flushAll());
        self::assertEquals([], $cache->stats());

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    /**
     * @expectedException \phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException
     */
    public function testInvalidCachePath()
    {
        $cache = new FileCache('/etc/invalidPathOrPermission^$:?><');
    }
}
