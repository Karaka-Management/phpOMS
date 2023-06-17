<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\PressureType;

/**
 * @internal
 */
final class PressureTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(13, PressureType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(PressureType::getConstants(), \array_unique(PressureType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('Pa', PressureType::PASCALS);
        self::assertEquals('bar', PressureType::BAR);
        self::assertEquals('psi', PressureType::POUND_PER_SQUARE_INCH);
        self::assertEquals('atm', PressureType::ATMOSPHERES);
        self::assertEquals('inHg', PressureType::INCHES_OF_MERCURY);
        self::assertEquals('inH20', PressureType::INCHES_OF_WATER);
        self::assertEquals('mmH20', PressureType::MILLIMETERS_OF_WATER);
        self::assertEquals('mmHg', PressureType::MILLIMETERS_OF_MERCURY);
        self::assertEquals('mbar', PressureType::MILLIBAR);
        self::assertEquals('kg/m2', PressureType::KILOGRAM_PER_SQUARE_METER);
        self::assertEquals('N/m2', PressureType::NEWTONS_PER_METER_SQUARED);
        self::assertEquals('psf', PressureType::POUNDS_PER_SQUARE_FOOT);
        self::assertEquals('Torr', PressureType::TORRS);
    }
}
