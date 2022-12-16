<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils;

use phpOMS\Utils\MbStringUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Utils\MbStringUtilsTest: Multi-Byte string utilities
 *
 * @internal
 */
final class MbStringUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The entropy of a string can be calculated
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testEntropy() : void
    {
        self::assertEqualsWithDelta(2.75, MbStringUtils::mb_entropy('akj@!©¥j'), 0.1);
    }

    /**
     * @testdox A string can be checked for multi-byte characters
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testHasMultiBytes() : void
    {
        self::assertTrue(MbStringUtils::hasMultiBytes('akj@!¥aj'));
        self::assertFalse(MbStringUtils::hasMultiBytes('akjc!aj'));
    }

    /**
     * @testdox A multi-byte string can be checked if it starts with a defined string
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testStartsMb() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(MbStringUtils::mb_startsWith($string, 'This '));
        self::assertFalse(MbStringUtils::mb_startsWith($string, 'Thss '));
    }

    /**
     * @testdox A multi-byte string can be checked if it ends with a defined string
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testEndsMb() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(MbStringUtils::mb_endsWith($string, 'string.'));
        self::assertFalse(MbStringUtils::mb_endsWith($string, 'strng.'));
    }

    /**
     * @testdox The first character of a multi-byte string can be turned into upper case
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testTransformUpperCase() : void
    {
        self::assertEquals('This ', MbStringUtils::mb_ucfirst('this '));
        self::assertNotEquals('this ', MbStringUtils::mb_ucfirst('this '));
    }

    /**
     * @testdox The first character of a multi-byte string can be turned into lower case
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testTransformLowerCase() : void
    {
        self::assertEquals('thss', MbStringUtils::mb_lcfirst('Thss'));
        self::assertNotEquals('Thss', MbStringUtils::mb_lcfirst('Thss'));
    }

    /**
     * @testdox A multi-byte string can be trimmed
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testTrim() : void
    {
        $string = 'This is a test string.';

        self::assertEquals($string, MbStringUtils::mb_trim($string, ' '));
        self::assertEquals('This is a test string', MbStringUtils::mb_trim($string, '.'));
        self::assertEquals('asdf', MbStringUtils::mb_trim(' asdf ', ' '));
        self::assertEquals('asdf', MbStringUtils::mb_trim('%asdf%', '%'));
    }

    /**
     * @testdox A multi-byte string can be right-trimmed
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testRTrim() : void
    {
        self::assertEquals(' asdf', MbStringUtils::mb_rtrim(' asdf   '));
        self::assertEquals('%asdf', MbStringUtils::mb_rtrim('%asdf%', '%'));
    }

    /**
     * @testdox A multi-byte string can be left-trimmed
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testLTrim() : void
    {
        self::assertEquals('asdf  ', MbStringUtils::mb_ltrim(' asdf  '));
        self::assertEquals('asdf%', MbStringUtils::mb_ltrim('%asdf%', '%'));
    }

    /**
     * @testdox A multi-byte string can be checked if it contains at least one defined string element
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testContainsMb() : void
    {
        $string = 'This is a test string.';

        self::assertTrue(MbStringUtils::mb_contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(MbStringUtils::mb_contains($string, ['iss', 'nothing', 'false']));
    }

    /**
     * @testdox The characters of a multi-byte string can be counted
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testCountMb() : void
    {
        self::assertEquals(5, MbStringUtils::mb_count_chars('αααααΕεΙιΜμΨψ')['α']);
    }

    /**
     * @testdox The previous boundary of a utf-8 encoded quoted printable is identified correctly
     * @covers phpOMS\Utils\MbStringUtils
     * @group framework
     */
    public function testUtf8CharBoundary() : void
    {
        self::assertEquals(10, MbStringUtils::utf8CharBoundary('H=E4tten H=FCte ein =DF im Namen, w=E4ren sie m=F6glicherweise k', 12));
    }
}
