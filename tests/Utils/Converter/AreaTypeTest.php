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

use phpOMS\Utils\Converter\AreaType;

/**
 * @internal
 */
final class AreaTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(13, AreaType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(AreaType::getConstants(), \array_unique(AreaType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals('ft', AreaType::SQUARE_FEET);
        self::assertEquals('m', AreaType::SQUARE_METERS);
        self::assertEquals('km', AreaType::SQUARE_KILOMETERS);
        self::assertEquals('mi', AreaType::SQUARE_MILES);
        self::assertEquals('yd', AreaType::SQUARE_YARDS);
        self::assertEquals('in', AreaType::SQUARE_INCHES);
        self::assertEquals('muin', AreaType::SQUARE_MICROINCHES);
        self::assertEquals('cm', AreaType::SQUARE_CENTIMETERS);
        self::assertEquals('mm', AreaType::SQUARE_MILIMETERS);
        self::assertEquals('micron', AreaType::SQUARE_MICROMETERS);
        self::assertEquals('dm', AreaType::SQUARE_DECIMETERS);
        self::assertEquals('ha', AreaType::HECTARES);
        self::assertEquals('ac', AreaType::ACRES);
    }
}
