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

namespace phpOMS\tests\Utils;

use phpOMS\Utils\StringUtils;
use phpOMS\Contract\RenderableInterface;

require_once __DIR__ . '/../Autoloader.php';

class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluation() : void
    {
        self::assertTrue(\abs(2.5 - StringUtils::getEntropy('akj@!0aj')) < 0.1);
    }

    public function testStartsEnds() : void
    {
        $string = 'This is a test string.';
        self::assertTrue(StringUtils::startsWith($string, 'This '));
        self::assertFalse(StringUtils::startsWith($string, 'Thss '));
        self::assertTrue(StringUtils::endsWith($string, 'string.'));
        self::assertFalse(StringUtils::endsWith($string, 'strng.'));

        self::assertTrue(StringUtils::mb_startsWith($string, 'This '));
        self::assertFalse(StringUtils::mb_startsWith($string, 'Thss '));
        self::assertTrue(StringUtils::mb_endsWith($string, 'string.'));
        self::assertFalse(StringUtils::mb_endsWith($string, 'strng.'));
    }

    public function testTransform() : void
    {
        self::assertEquals('This ', StringUtils::mb_ucfirst('this '));
        self::assertNotEquals('this ', StringUtils::mb_ucfirst('this '));
        self::assertEquals('thss', StringUtils::mb_lcfirst('Thss'));
        self::assertNotEquals('Thss', StringUtils::mb_lcfirst('Thss'));
    }

    public function testTrim() : void
    {
        $string = 'This is a test string.';

        self::assertEquals($string, StringUtils::mb_trim($string, ' '));
        self::assertEquals('This is a test string', StringUtils::mb_trim($string, '.'));
        self::assertEquals('asdf', StringUtils::mb_trim(' asdf ', ' '));
        self::assertEquals('asdf', StringUtils::mb_trim('%asdf%', '%'));

        self::assertEquals(' asdf', StringUtils::mb_rtrim(' asdf   '));
        self::assertEquals('%asdf', StringUtils::mb_rtrim('%asdf%', '%'));

        self::assertEquals('asdf  ', StringUtils::mb_ltrim(' asdf  '));
        self::assertEquals('asdf%', StringUtils::mb_ltrim('%asdf%', '%'));
    }

    public function testContains() : void
    {
        $string = 'This is a test string.';

        self::assertTrue(StringUtils::contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(StringUtils::contains($string, ['iss', 'nothing', 'false']));

        self::assertTrue(StringUtils::mb_contains($string, ['is', 'nothing', 'string']));
        self::assertFalse(StringUtils::mb_contains($string, ['iss', 'nothing', 'false']));
    }

    public function testCount() : void
    {
        self::assertEquals(5, StringUtils::mb_count_chars('αααααΕεΙιΜμΨψ')['α']);
        self::assertEquals(4, StringUtils::countCharacterFromStart('    Test string', ' '));
        self::assertEquals(0, StringUtils::countCharacterFromStart('    Test string', 's'));
    }

    public function testStringify() : void
    {
        self::assertEquals('"abc"', StringUtils::stringify(new class implements \JsonSerializable {
            public function jsonSerialize()
            {
                return 'abc';
            }
        }));

        self::assertEquals('["abc"]', StringUtils::stringify(['abc']));

        self::assertEquals('abc', StringUtils::stringify(new class implements \Serializable {
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
        self::assertEquals(null, StringUtils::stringify(null));

        $date = new \DateTime('now');
        self::assertEquals($date->format('Y-m-d H:i:s'), StringUtils::stringify($date));

        self::assertEquals('abc', StringUtils::stringify(new class {
            public function __toString()
            {
                return 'abc';
            }
        }));

        self::assertEquals('abc', StringUtils::stringify(new class implements RenderableInterface {
            public function render() : string
            {
                return 'abc';
            }
        }));
    }

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
