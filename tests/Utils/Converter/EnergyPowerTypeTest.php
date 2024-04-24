<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\EnergyPowerType;

/**
 * @internal
 */
final class EnergyPowerTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(9, EnergyPowerType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(EnergyPowerType::getConstants(), \array_unique(EnergyPowerType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals('kWh', EnergyPowerType::KILOWATT_HOURS);
        self::assertEquals('MWh', EnergyPowerType::MEGAWATT_HOURS);
        self::assertEquals('kt', EnergyPowerType::KILOTONS);
        self::assertEquals('J', EnergyPowerType::JOULES);
        self::assertEquals('Cal', EnergyPowerType::CALORIES);
        self::assertEquals('BTU', EnergyPowerType::BTU);
        self::assertEquals('kJ', EnergyPowerType::KILOJOULES);
        self::assertEquals('thmEC', EnergyPowerType::THERMEC);
        self::assertEquals('Nm', EnergyPowerType::NEWTON_METERS);
    }
}
