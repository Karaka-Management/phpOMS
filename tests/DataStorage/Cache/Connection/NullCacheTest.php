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

use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\DataStorage\Cache\Connection\NullCache;

/**
 * @testdox phpOMS\tests\DataStorage\Cache\Connection\NullCacheTest: Null cache connection if no cache is available
 *
 * @internal
 */
final class NullCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The default cache has the expected default values after initialization
     * @covers phpOMS\DataStorage\Cache\Connection\NullCache<extended>
     * @group framework
     */
    public function testCache() : void
    {
        $cache = new NullCache();
        $cache->connect([]);

        self::assertEquals(CacheType::UNDEFINED, $cache->getType());
        self::assertTrue($cache->add(1, 1));

        $cache->set(1, 1);
        self::assertNull($cache->get(1));

        self::assertTrue($cache->delete(1));
        self::assertTrue($cache->flush(1));
        self::assertTrue($cache->flushAll());
        self::assertTrue($cache->replace(1, 1));
        self::assertEquals([], $cache->stats());
        self::assertEquals(0, $cache->getThreshold());
    }
}
