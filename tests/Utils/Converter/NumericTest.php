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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\Numeric;

class NumericTest extends \PHPUnit\Framework\TestCase
{
    public function testArabicRoman() : void
    {
        $rand = mt_rand(1, 9999);
        self::assertEquals($rand, Numeric::romanToArabic(Numeric::arabicToRoman($rand)));

        self::assertEquals('VIII', Numeric::arabicToRoman(8));
        self::assertEquals('IX', Numeric::arabicToRoman(9));
        self::assertEquals('X', Numeric::arabicToRoman(10));
        self::assertEquals('XI', Numeric::arabicToRoman(11));
    }

    public function testAlphaNumeric() : void
    {
        self::assertEquals(0, Numeric::alphaToNumeric('A'));
        self::assertEquals(1, Numeric::alphaToNumeric('B'));
        self::assertEquals(53, Numeric::alphaToNumeric('BB'));

        self::assertEquals('A', Numeric::numericToAlpha(0));
        self::assertEquals('B', Numeric::numericToAlpha(1));
        self::assertEquals('BB', Numeric::numericToAlpha(53));
    }

    public function testBase() : void
    {
        self::assertEquals('443', Numeric::convertBase('123', '0123456789', '01234'));
        self::assertEquals('7B', Numeric::convertBase('123', '0123456789', '0123456789ABCDEF'));
        self::assertEquals('173', Numeric::convertBase('123', '0123456789', '01234567'));

        self::assertEquals('123', Numeric::convertBase('443', '01234', '0123456789'));
        self::assertEquals('123', Numeric::convertBase('7B', '0123456789ABCDEF', '0123456789'));
        self::assertEquals('123', Numeric::convertBase('173', '01234567', '0123456789'));

        self::assertEquals('173', Numeric::convertBase('173', '01234567', '01234567'));
    }
}
