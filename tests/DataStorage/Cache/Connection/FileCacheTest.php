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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\FileCache;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
class FileCacheTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
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
        self::assertNull($cache->get('test'));

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    public function testConnect() : void
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

    public function testGetSet() : void
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
        self::assertFalse($cache->get('key2'));

        $cache->set('key3', null); // 4
        self::assertNull($cache->get('key3'));

        $cache->set('key4', 4); // 5
        self::assertEquals(4, $cache->get('key4'));

        $cache->set('key5', 5.12); // 6
        self::assertEquals(5.12, $cache->get('key5'));

        $cache->set('key6', ['asdf', 1, true, 2.3]); // 7
        self::assertEquals(['asdf', 1, true, 2.3], $cache->get('key6'));

        $cache->set('key7', new FileCacheSerializable()); // 8
        self::assertEquals('abc', $cache->get('key7')->val);

        $cache->set('key8', new FileCacheJsonSerializable()); // 9
        self::assertEquals('abc', $cache->get('key8')->val);

        self::assertTrue($cache->replace('key4', 5));
        self::assertFalse($cache->replace('keyInvalid', 5));
        self::assertEquals(5, $cache->get('key4'));

        self::assertTrue($cache->delete('key4')); // 8
        self::assertTrue($cache->delete('keyInvalid'));
        self::assertNull($cache->get('key4'));

        self::assertEquals(
            [
                'status'  => CacheStatus::OK,
                'count'   => 8,
                'size'    => 220,
            ],
            $cache->stats()
        );

        self::assertTrue($cache->flushAll());
        self::assertNull($cache->get('key5'));

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

    public function testExpire() : void
    {
        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');

        $cache->flushAll();

        $cache->set('key1', 'testVal', 1);
        self::assertEquals('testVal', $cache->get('key1'));

        $cache->set('key2', 'testVal2', 1);
        self::assertEquals('testVal2', $cache->get('key2', 1));
        \sleep(3);
        self::assertNull($cache->get('key2', 1));

        $cache->set('key3', 'testVal3', 1);
        self::assertEquals('testVal3', $cache->get('key3', 1));
        \sleep(3);
        self::assertNull($cache->get('key3', 1));

        $cache->set('key4', 'testVal4', 1);
        self::assertFalse($cache->delete('key4', 0));
        \sleep(3);
        self::assertTrue($cache->delete('key4', 1));

        $cache->set('key5', 'testVal5', 10000);
        \sleep(3);
        self::assertFalse($cache->delete('key5', 1000000));
        self::assertTrue($cache->delete('key5', 1));

        $cache->set('key6', 'testVal6', 1);
        \sleep(2);

        $cache->flush(0);

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    public function testBadCacheStatus() : void
    {
        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');
        $cache->flushAll();

        TestUtils::setMember($cache, 'status', CacheStatus::FAILURE);

        $cache->set('key1', 'testVal');
        self::assertFalse($cache->add('key2', 'testVal2'));
        self::assertNull($cache->get('key1'));
        self::assertFalse($cache->replace('key1', 5));
        self::assertFalse($cache->delete('key1'));
        self::assertFalse($cache->flushAll());
        self::assertFalse($cache->flush());
        self::assertEquals([], $cache->stats());

        if (\file_exists(__DIR__ . '/Cache')) {
            \rmdir(__DIR__ . '/Cache');
        }
    }

    public function testInvalidCachePath() : void
    {
        self::expectException(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class);

        $cache = new FileCache('/etc/invalidPathOrPermission^$:?><');
    }

    public function testInvalidDataType() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $cache = new FileCache(__DIR__ . '/Cache');
        $cache->add('invalid', $cache);
    }
}
