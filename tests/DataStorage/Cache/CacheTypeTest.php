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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CacheType;

class CacheTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertEquals(4, \count(CacheType::getConstants()));
        self::assertEquals('file', CacheType::FILE);
        self::assertEquals('mem', CacheType::MEMCACHED);
        self::assertEquals('redis', CacheType::REDIS);
        self::assertEquals('na', CacheType::UNDEFINED);
    }
}
