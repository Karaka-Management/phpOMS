<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\Connection\FileCache;

class FileCacheTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        if (file_exists(__DIR__ . '/Cache')) {
            rmdir(__DIR__ . '/Cache');
        }

        $cache = new FileCache(__DIR__ . '/Cache');

        self::assertTrue(is_dir(__DIR__ . '/Cache'));
        self::assertTrue($cache->flushAll());
        self::assertEquals(50, $cache->getThreshold());
        self::assertEquals(null, $cache->get('test'));

        if (file_exists(__DIR__ . '/Cache')) {
            rmdir(__DIR__ . '/Cache');
        }
    }

    public function testGetSet()
    {
        if (file_exists(__DIR__ . '/Cache')) {
            rmdir(__DIR__ . '/Cache');
        }
        
        $cache = new FileCache(__DIR__ . '/Cache');

        $cache->set('key1', 'testVal');
        self::assertEquals('testVal', $cache->get('key1'));

        $cache->set('key2', false);
        self::assertEquals(false, $cache->get('key2'));

        $cache->set('key3', null);
        self::assertEquals(null, $cache->get('key3'));

        $cache->set('key4', 4);
        self::assertEquals(4, $cache->get('key4'));

        $cache->set('key5', 5.12);
        self::assertEquals(5.12, $cache->get('key5'));

        $cache->set('key6', ['asdf', 1, true, 2.3]);
        self::assertEquals(['asdf', 1, true, 2.3], $cache->get('key6'));

        $cache->replace('key4', 5);
        self::assertEquals(5, $cache->get('key4'));

        $cache->delete('key4');
        self::assertEquals(null, $cache->get('key4'));

        $cache->flushAll();
        self::assertEquals(null, $cache->get('key5'));

        if (file_exists(__DIR__ . '/Cache')) {
            rmdir(__DIR__ . '/Cache');
        }
    }
}
