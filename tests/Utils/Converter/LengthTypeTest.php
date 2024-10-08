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

use phpOMS\Utils\Converter\LengthType;

/**
 * @internal
 */
final class LengthTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(22, LengthType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(LengthType::getConstants(), \array_unique(LengthType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals('mi', LengthType::MILES);
        self::assertEquals('m', LengthType::METERS);
        self::assertEquals('micron', LengthType::MICROMETER);
        self::assertEquals('nm', LengthType::NANOMETER);
        self::assertEquals('cm', LengthType::CENTIMETERS);
        self::assertEquals('mm', LengthType::MILLIMETERS);
        self::assertEquals('km', LengthType::KILOMETERS);
        self::assertEquals('ch', LengthType::CHAINS);
        self::assertEquals('ft', LengthType::FEET);
        self::assertEquals('fur', LengthType::FURLONGS);
        self::assertEquals('muin', LengthType::MICROINCH);
        self::assertEquals('in', LengthType::INCHES);
        self::assertEquals('yd', LengthType::YARDS);
        self::assertEquals('pc', LengthType::PARSECS);
        self::assertEquals('uk nmi', LengthType::UK_NAUTICAL_MILES);
        self::assertEquals('us nmi', LengthType::US_NAUTICAL_MILES);
        self::assertEquals('uk nl', LengthType::UK_NAUTICAL_LEAGUES);
        self::assertEquals('nl', LengthType::NAUTICAL_LEAGUES);
        self::assertEquals('uk lg', LengthType::UK_LEAGUES);
        self::assertEquals('us lg', LengthType::US_LEAGUES);
        self::assertEquals('ly', LengthType::LIGHTYEARS);
        self::assertEquals('dm', LengthType::DECIMETERS);
    }
}
