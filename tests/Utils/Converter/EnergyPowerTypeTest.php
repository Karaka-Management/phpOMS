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
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\EnergyPowerType;

/**
 * @internal
 */
class EnergyPowerTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(9, EnergyPowerType::getConstants());
        self::assertEquals(EnergyPowerType::getConstants(), \array_unique(EnergyPowerType::getConstants()));

        self::assertEquals('kWh', EnergyPowerType::KILOWATT_HOUERS);
        self::assertEquals('MWh', EnergyPowerType::MEGAWATT_HOUERS);
        self::assertEquals('kt', EnergyPowerType::KILOTONS);
        self::assertEquals('J', EnergyPowerType::JOULS);
        self::assertEquals('Cal', EnergyPowerType::CALORIES);
        self::assertEquals('BTU', EnergyPowerType::BTU);
        self::assertEquals('kJ', EnergyPowerType::KILOJOULS);
        self::assertEquals('thmEC', EnergyPowerType::THERMEC);
        self::assertEquals('Nm', EnergyPowerType::NEWTON_METERS);
    }
}
