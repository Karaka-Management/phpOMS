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

namespace phpOMS\tests\DataStorage\Cache;

use phpOMS\DataStorage\Cache\CacheType;

/**
 * @internal
 */
class CacheTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, CacheType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(CacheType::getConstants(), array_unique(CacheType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('file', CacheType::FILE);
        self::assertEquals('mem', CacheType::MEMCACHED);
        self::assertEquals('redis', CacheType::REDIS);
        self::assertEquals('na', CacheType::UNDEFINED);
    }
}
