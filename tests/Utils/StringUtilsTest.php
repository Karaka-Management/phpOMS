<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Utils\StringUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Utils\StringUtilsTest: String utilities
 *
 * @internal
 */
class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The entropy of a string can be calculated
     * @covers phpOMS\Utils\StringUtils
     */
    public function testEntropy() : void
    {
        self::assertTrue(\abs(2.5 - StringUtils::getEntropy('akj@!0aj')) < 0.1);
    }

    /**
     * @testdox A string can be checked if it starts with a defined string
     * @covers phpOMS\Utils\StringUtils
     */
    public function testStarts() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(StringUtils::startsWith($string, 'This '));
        self::assertFalse(StringUtils::startsWith($string, 'Thss '));
    }

    /**
     * @testdox A string can be checked if it ends with a defined string
     * @covers phpOMS\Utils\StringUtils
     */
    public function testEnds() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(StringUtils::endsWith($string, 'string.'));
        self::assertFalse(StringUtils::endsWith($string, 'strng.'));
    }

    /**
     * @testdox A multi-byte string can be checked if it starts with a defined string
     * @covers phpOMS\Utils\StringUtils
     */
    public function testStartsMb() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(StringUtils::mb_startsWith($string, 'This '));
        self::assertFalse(StringUtils::mb_startsWith($string, 'Thss '));
    }

    /**
     * @testdox A multi-byte string can be checked if it ends with a defined string
     * @covers phpOMS\Utils\StringUtils
     */
    public function testEndsMb() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(StringUtils::mb_endsWith($string, 'string.'));
        self::assertFalse(StringUtils::mb_endsWith($string, 'strng.'));
    }

    /**
     * @testdox The first character of a multi-byte string can be turned into upper case
     * @covers phpOMS\Utils\StringUtils
     */
    public function testTransformUpperCase() : void
    {
        self::assertEquals('This ', StringUtils::mb_ucfirst('this '));
        self::assertNotEquals('this ', StringUtils::mb_ucfirst('this '));
    }

    /**
     * @testdox The first character of a multi-byte string can be turned into lower case
     * @covers phpOMS\Utils\StringUtils
     */
    public function testTransformLowerCase() : void
    {
        self::assertEquals('thss', StringUtils::mb_lcfirst('Thss'));
        self::assertNotEquals('Thss', StringUtils::mb_lcfirst('Thss'));
    }

    /**
     * @testdox A multi-byte string can be trimmed
     * @covers phpOMS\Utils\StringUtils
     */
    public function testTrim() : void
    {
        $string = 'This is a test string.';

        self::assertEquals($string, StringUtils::mb_trim($string, ' '));
        self::assertEquals('This is a test string', StringUtils::mb_trim($string, '.'));
        self::assertEquals('asdf', StringUtils::mb_trim(' asdf ', ' '));
        self::assertEquals('asdf', StringUtils::mb_trim('%asdf%', '%'));
    }

    /**
     * @testdox A multi-byte string can be right-trimmed
     * @covers phpOMS\Utils\StringUtils
     */
    public function testRTrim() : void
    {
        self::assertEquals(' asdf', StringUtils::mb_rtrim(' asdf   '));
        self::assertEquals('%asdf', StringUtils::mb_rtrim('%asdf%', '%'));
    }

    /**
     * @testdox A multi-byte string can be left-trimmed
     * @covers phpOMS\Utils\StringUtils
     */
    public function testLTrim() : void
    {
        self::assertEquals('asdf  ', StringUtils::mb_ltrim(' asdf  '));
        self::assertEquals('asdf%', StringUtils::mb_ltrim('%asdf%', '%'));
    }

    /**
     * @testdox A string can be checked if it contains at least one defined string element
     * @covers phpOMS\Utils\StringUtils
     */
    public function testContains() : void
    {
        $string = 'This is a test string.';

        self::assertTrue(StringUtils::contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(StringUtils::contains($string, ['iss', 'nothing', 'false']));
    }

    /**
     * @testdox A multi-byte string can be checked if it contains at least one defined string element
     * @covers phpOMS\Utils\StringUtils
     */
    public function testContainsMb() : void
    {
        $string = 'This is a test string.';

        self::assertTrue(StringUtils::mb_contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(StringUtils::mb_contains($string, ['iss', 'nothing', 'false']));
    }

    /**
     * @testdox The characters of a multi-byte string can be counted
     * @covers phpOMS\Utils\StringUtils
     */
    public function testCountMb() : void
    {
        self::assertEquals(5, StringUtils::mb_count_chars('αααααΕεΙιΜμΨψ')['α']);
    }

    /**
     * @testdox The amount of a defined characters in the beginning of a string can be counted
     * @covers phpOMS\Utils\StringUtils
     */
    public function testCountBeginning() : void
    {
        self::assertEquals(4, StringUtils::countCharacterFromStart('    Test string', ' '));
        self::assertEquals(0, StringUtils::countCharacterFromStart('    Test string', 's'));
    }

    /**
     * @testdox Various data types can be stringified
     * @covers phpOMS\Utils\StringUtils
     */
    public function testStringify() : void
    {
        self::assertEquals('"abc"', StringUtils::stringify(new class() implements \JsonSerializable {
            public function jsonSerialize()
            {
                return 'abc';
            }
        }));

        self::assertEquals('["abc"]', StringUtils::stringify(['abc']));

        self::assertEquals('abc', StringUtils::stringify(new class() implements \Serializable {
            public function serialize()
            {
                return 'abc';
            }

            public function unserialize($val) : void
            {
            }
        }));

        self::assertEquals('abc', StringUtils::stringify('abc'));
        self::assertEquals('1', StringUtils::stringify(1));
        self::assertEquals('1.1', StringUtils::stringify(1.1));
        self::assertEquals('1', StringUtils::stringify(true));
        self::assertEquals('0', StringUtils::stringify(false));
        self::assertNull(StringUtils::stringify(null));

        $date = new \DateTime('now');
        self::assertEquals($date->format('Y-m-d H:i:s'), StringUtils::stringify($date));

        self::assertEquals('abc', StringUtils::stringify(new class() {
            public function __toString()
            {
                return 'abc';
            }
        }));

        self::assertEquals('abc', StringUtils::stringify(new class() implements RenderableInterface {
            public function render() : string
            {
                return 'abc';
            }
        }));
    }

    /**
     * @testdox The difference between two strings can be evaluated
     * @covers phpOMS\Utils\StringUtils
     */
    public function testStringDiffHtml() : void
    {
        $original = 'This is a test string.';
        $new      = 'This is a new string.';

        self::assertEquals(
            'This is a <del>t</del><ins>n</ins>e<del>st</del><ins>w</ins> string.',
            StringUtils::createDiffMarkup($original, $new)
        );

        self::assertEquals(
            'This is a <del>test</del><ins>new</ins> string.',
            StringUtils::createDiffMarkup($original, $new, ' ')
        );

        $original = '';
        $new      = 'This is a new string.';

        self::assertEquals(
            '<ins>' . $new . '</ins>',
            StringUtils::createDiffMarkup($original, $new)
        );

        $original = 'This is a new string.';
        $new      = '';

        self::assertEquals(
            '<del>' . $original . '</del>',
            StringUtils::createDiffMarkup($original, $new)
        );

        $original = 'This is a new string';
        $new      = 'This is a new string!';

        self::assertEquals(
            $original . '<ins>!</ins>',
            StringUtils::createDiffMarkup($original, $new)
        );

        $original = 'This is a new string.';
        $new      = 'This is a new string';

        self::assertEquals(
            $new . '<del>.</del>',
            StringUtils::createDiffMarkup($original, $new)
        );
    }
}
