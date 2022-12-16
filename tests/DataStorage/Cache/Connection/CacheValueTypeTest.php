<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\Connection\CacheValueType;

/**
 * @internal
 */
final class CacheValueTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(8, CacheValueType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(CacheValueType::getConstants(), \array_unique(CacheValueType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, CacheValueType::_INT);
        self::assertEquals(1, CacheValueType::_STRING);
        self::assertEquals(2, CacheValueType::_ARRAY);
        self::assertEquals(3, CacheValueType::_SERIALIZABLE);
        self::assertEquals(4, CacheValueType::_FLOAT);
        self::assertEquals(5, CacheValueType::_BOOL);
        self::assertEquals(6, CacheValueType::_JSONSERIALIZABLE);
        self::assertEquals(7, CacheValueType::_NULL);
    }
}
