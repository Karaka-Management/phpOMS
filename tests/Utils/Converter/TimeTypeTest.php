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

namespace phpOMS\tests\Utils\Converter;


use phpOMS\Utils\Converter\TimeType;

class TimeTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(9, count(TimeType::getConstants()));
        self::assertEquals(TimeType::getConstants(), array_unique(TimeType::getConstants()));
        
        self::assertEquals('ms', TimeType::MILLISECONDS);
        self::assertEquals('s', TimeType::SECONDS);
        self::assertEquals('i', TimeType::MINUTES);
        self::assertEquals('h', TimeType::HOURS);
        self::assertEquals('d', TimeType::DAYS);
        self::assertEquals('w', TimeType::WEEKS);
        self::assertEquals('m', TimeType::MONTH);
        self::assertEquals('q', TimeType::QUARTER);
        self::assertEquals('y', TimeType::YEAR);
    }
}
