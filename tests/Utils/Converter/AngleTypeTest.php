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

use phpOMS\Utils\Converter\AngleType;

/**
 * @internal
 */
final class AngleTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(10, AngleType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(AngleType::getConstants(), \array_unique(AngleType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('deg', AngleType::DEGREE);
        self::assertEquals('rad', AngleType::RADIAN);
        self::assertEquals('arcsec', AngleType::SECOND);
        self::assertEquals('arcmin', AngleType::MINUTE);
        self::assertEquals('mil (us ww2)', AngleType::MILLIRADIAN_US);
        self::assertEquals('mil (uk)', AngleType::MILLIRADIAN_UK);
        self::assertEquals('mil (ussr)', AngleType::MILLIRADIAN_USSR);
        self::assertEquals('mil (nato)', AngleType::MILLIRADIAN_NATO);
        self::assertEquals('g', AngleType::GRADIAN);
        self::assertEquals('crad', AngleType::CENTRAD);
    }
}
