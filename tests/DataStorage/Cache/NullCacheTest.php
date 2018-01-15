<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache;


use phpOMS\DataStorage\Cache\NullCache;

class NullCacheTest extends \PHPUnit\Framework\TestCase
{
    public function testCache()
    {
        $cache = new NullCache();
        self::assertTrue($cache->add(1, 1));
        self::assertEquals(null, $cache->get(1));
        self::assertTrue($cache->delete(1));
        self::assertTrue($cache->flush(1));
        self::assertTrue($cache->flushAll());
        self::assertTrue($cache->replace(1, 1));
        self::assertEquals([], $cache->stats());
        self::assertEquals(0, $cache->getThreshold());
    }
}