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

use phpOMS\Utils\Converter\Numeric;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Converter\Numeric::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Converter\NumericTest: Numeric converter')]
final class NumericTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Arabic numbers can be converted to roman numbers')]
    public function testArabicToRoman() : void
    {
        $rand = \mt_rand(1, 9999);
        self::assertEquals($rand, Numeric::romanToArabic(Numeric::arabicToRoman($rand)));

        self::assertEquals('VIII', Numeric::arabicToRoman(8));
        self::assertEquals('IX', Numeric::arabicToRoman(9));
        self::assertEquals('X', Numeric::arabicToRoman(10));
        self::assertEquals('XI', Numeric::arabicToRoman(11));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Roman numbers can be converted to arabic numbers')]
    public function testRomanToArabic() : void
    {
        self::assertEquals(8, Numeric::romanToArabic('VIII'));
        self::assertEquals(9, Numeric::romanToArabic('IX'));
        self::assertEquals(10, Numeric::romanToArabic('X'));
        self::assertEquals(11, Numeric::romanToArabic('XI'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Letters can be converted to numbers')]
    public function testAlphaToNumeric() : void
    {
        self::assertEquals(0, Numeric::alphaToNumeric('A'));
        self::assertEquals(1, Numeric::alphaToNumeric('B'));
        self::assertEquals(53, Numeric::alphaToNumeric('BB'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Numbers can be converted to letters')]
    public function testNumericToAlpha() : void
    {
        self::assertEquals('A', Numeric::numericToAlpha(0));
        self::assertEquals('B', Numeric::numericToAlpha(1));
        self::assertEquals('BB', Numeric::numericToAlpha(53));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Numbers can be converted between bases')]
    public function testBase() : void
    {
        self::assertEquals('443', Numeric::convertBase('123', '0123456789', '01234'));
        self::assertEquals('7B', Numeric::convertBase('123', '0123456789', '0123456789ABCDEF'));
        self::assertEquals('173', Numeric::convertBase('123', '0123456789', '01234567'));

        self::assertEquals('123', Numeric::convertBase('443', '01234', '0123456789'));
        self::assertEquals('123', Numeric::convertBase('7B', '0123456789ABCDEF', '0123456789'));
        self::assertEquals('123', Numeric::convertBase('173', '01234567', '0123456789'));

        self::assertEquals('173', Numeric::convertBase('173', '01234567', '01234567'));

        self::assertEquals('2', Numeric::convertBase('2', '0123456789', '0123456789ABCDEF'));
    }
}
