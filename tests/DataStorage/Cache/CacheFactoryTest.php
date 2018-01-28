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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CacheFactory;

class CacheFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        self::assertInstanceOf(
            \phpOMS\DataStorage\Cache\FileCache::class,
            CacheFactory::create(['type' => 'file', 'path' => 'Cache'])
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCacheType()
    {
        CacheFactory::create(['type' => 'invalid', 'path' => 'Cache']);
    }
}

