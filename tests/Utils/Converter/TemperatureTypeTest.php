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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\TemperatureType;

/**
 * @internal
 */
final class TemperatureTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(8, TemperatureType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(TemperatureType::getConstants(), \array_unique(TemperatureType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('celsius', TemperatureType::CELSIUS);
        self::assertEquals('fahrenheit', TemperatureType::FAHRENHEIT);
        self::assertEquals('kelvin', TemperatureType::KELVIN);
        self::assertEquals('reaumur', TemperatureType::REAUMUR);
        self::assertEquals('rankine', TemperatureType::RANKINE);
        self::assertEquals('delisle', TemperatureType::DELISLE);
        self::assertEquals('newton', TemperatureType::NEWTON);
        self::assertEquals('romer', TemperatureType::ROMER);
    }
}
