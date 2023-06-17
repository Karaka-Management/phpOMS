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

namespace phpOMS\tests\Utils;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Contract\SerializableInterface;
use phpOMS\Utils\StringUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Utils\StringUtilsTest: String utilities
 *
 * @internal
 */
final class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The entropy of a string can be calculated
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testEntropy() : void
    {
        self::assertEqualsWithDelta(2.5, StringUtils::entropy('akj@!0aj'), 0.1);
    }

    /**
     * @testdox A string can be checked if it starts with a defined string
     * @covers phpOMS\Utils\StringUtils
     * @group framework
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
     * @group framework
     */
    public function testEnds() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(StringUtils::endsWith($string, 'string.'));
        self::assertFalse(StringUtils::endsWith($string, 'strng.'));
    }

    /**
     * @testdox A string can be checked if it contains at least one defined string element
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testContains() : void
    {
        $string = 'This is a test string.';

        self::assertTrue(StringUtils::contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(StringUtils::contains($string, ['iss', 'nothing', 'false']));
    }

    /**
     * @testdox The amount of a defined characters in the beginning of a string can be counted
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testCountBeginning() : void
    {
        self::assertEquals(4, StringUtils::countCharacterFromStart('    Test string', ' '));
        self::assertEquals(0, StringUtils::countCharacterFromStart('    Test string', 's'));
    }

    /**
     * @testdox A string creates a integer hash
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testIntHash() : void
    {
        self::assertGreaterThan(0, StringUtils::intHash('test'));
    }

    /**
     * @testdox The same string creates the same hash
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testSameHash() : void
    {
        self::assertEquals(StringUtils::intHash('test'), StringUtils::intHash('test'));
    }

    /**
     * @testdox Different strings create different hashes
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testDifferentHash() : void
    {
        self::assertNotEquals(StringUtils::intHash('test1'), StringUtils::intHash('test2'));
    }

    /**
     * @testdox Various data types can be stringified
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testStringify() : void
    {
        self::assertEquals('"abc"', StringUtils::stringify(new class() implements \JsonSerializable {
            public function jsonSerialize() : mixed
            {
                return 'abc';
            }
        }));

        self::assertEquals('["abc"]', StringUtils::stringify(['abc']));

        self::assertEquals('abc', StringUtils::stringify(new class() implements SerializableInterface {
            public function serialize() : string
            {
                return 'abc';
            }

            public function unserialize(mixed $val) : void
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
            public function render(mixed ...$data) : string
            {
                return 'abc';
            }
        }));
    }

    /**
     * @testdox Stringify/rendering a unknown data type returns null
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testInvalidStringify() : void
    {
        self::assertNull(StringUtils::stringify(new class() {}));
    }

    /**
     * @testdox The difference between two strings can be evaluated
     * @covers phpOMS\Utils\StringUtils
     * @group framework
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

    /**
     * @testdox A string can be validated for shell safety
     * @covers phpOMS\Utils\StringUtils
     * @group framework
     */
    public function testIsShellSafe() : void
    {
        self::assertTrue(StringUtils::isShellSafe('asdf'));
        self::assertFalse(StringUtils::isShellSafe('&#;`|*?~<>^()[]{}$\\'));
        self::assertFalse(StringUtils::isShellSafe('™'));
    }
}
