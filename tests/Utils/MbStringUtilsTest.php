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

namespace phpOMS\tests\Utils;

use phpOMS\Utils\MbStringUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\MbStringUtils::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\MbStringUtilsTest: Multi-Byte string utilities')]
final class MbStringUtilsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The entropy of a string can be calculated')]
    public function testEntropy() : void
    {
        self::assertEqualsWithDelta(2.75, MbStringUtils::mb_entropy('akj@!©¥j'), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be checked for multi-byte characters')]
    public function testHasMultiBytes() : void
    {
        self::assertTrue(MbStringUtils::hasMultiBytes('akj@!¥aj'));
        self::assertFalse(MbStringUtils::hasMultiBytes('akjc!aj'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multi-byte string can be checked if it starts with a defined string')]
    public function testStartsMb() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(MbStringUtils::mb_startsWith($string, 'This '));
        self::assertFalse(MbStringUtils::mb_startsWith($string, 'Thss '));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multi-byte string can be checked if it ends with a defined string')]
    public function testEndsMb() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(MbStringUtils::mb_endsWith($string, 'string.'));
        self::assertFalse(MbStringUtils::mb_endsWith($string, 'strng.'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The first character of a multi-byte string can be turned into upper case')]
    public function testTransformUpperCase() : void
    {
        self::assertEquals('This ', MbStringUtils::mb_ucfirst('this '));
        self::assertNotEquals('this ', MbStringUtils::mb_ucfirst('this '));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The first character of a multi-byte string can be turned into lower case')]
    public function testTransformLowerCase() : void
    {
        self::assertEquals('thss', MbStringUtils::mb_lcfirst('Thss'));
        self::assertNotEquals('Thss', MbStringUtils::mb_lcfirst('Thss'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multi-byte string can be trimmed')]
    public function testTrim() : void
    {
        $string = 'This is a test string.';

        self::assertEquals($string, MbStringUtils::mb_trim($string, ' '));
        self::assertEquals('This is a test string', MbStringUtils::mb_trim($string, '.'));
        self::assertEquals('asdf', MbStringUtils::mb_trim(' asdf ', ' '));
        self::assertEquals('asdf', MbStringUtils::mb_trim('%asdf%', '%'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multi-byte string can be right-trimmed')]
    public function testRTrim() : void
    {
        self::assertEquals(' asdf', MbStringUtils::mb_rtrim(' asdf   '));
        self::assertEquals('%asdf', MbStringUtils::mb_rtrim('%asdf%', '%'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multi-byte string can be left-trimmed')]
    public function testLTrim() : void
    {
        self::assertEquals('asdf  ', MbStringUtils::mb_ltrim(' asdf  '));
        self::assertEquals('asdf%', MbStringUtils::mb_ltrim('%asdf%', '%'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A multi-byte string can be checked if it contains at least one defined string element')]
    public function testContainsMb() : void
    {
        $string = 'This is a test string.';

        self::assertTrue(MbStringUtils::mb_contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(MbStringUtils::mb_contains($string, ['iss', 'nothing', 'false']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The characters of a multi-byte string can be counted')]
    public function testCountMb() : void
    {
        self::assertEquals(5, MbStringUtils::mb_count_chars('αααααΕεΙιΜμΨψ')['α']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The previous boundary of a utf-8 encoded quoted printable is identified correctly')]
    public function testUtf8CharBoundary() : void
    {
        self::assertEquals(10, MbStringUtils::utf8CharBoundary('H=E4tten H=FCte ein =DF im Namen, w=E4ren sie m=F6glicherweise k', 12));
    }
}
