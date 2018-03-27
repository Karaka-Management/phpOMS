<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\Connection\CacheValueType;

class CacheValueTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(8, count(CacheValueType::getConstants()));
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
