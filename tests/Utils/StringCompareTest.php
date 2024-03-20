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

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\StringCompare;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\StringCompare::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\StringCompareTest: String comparison / dictionary')]
final class StringCompareTest extends \PHPUnit\Framework\TestCase
{
    private StringCompare $dict;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->dict = new StringCompare(
            [
                'Table Airplane Snowflake',
                'Football Pancake Doghouse Bathtub',
                'Spaceship Cowboy Spaceship Cowboy',
                'Snowflake Bathtub Snowflake Toothbrush Sidewalk',
                'Rocket Spaceship Table',
                'Cowboy Snowflake Bathtub',
                'Spaceship Classroom Apple',
                'Bathtub Sidewalk Table',
                'Teacher Bathtub Paper',
                'Cartoon',
                'Cowboy Table Pencil Candy Snowflake',
                'Apple Apple Cowboy Rocket',
                'Sidewalk Tiger Snowflake Spider',
                'Zebra Apple Magnet Sidewal',
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be matched with a dictionary entry')]
    public function testDictionaryMatch() : void
    {
        self::assertEquals('Cartoon', $this->dict->matchDictionary('Carton'));
        self::assertEquals('Bathtub Sidewalk Table', $this->dict->matchDictionary('Sidewalk Table'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A string doesn't match a dictionary entry if it is very different")]
    public function testInvalidDictionary() : void
    {
        self::assertNotEquals('Snowflake Bathtub Snowflake Toothbrush Sidewalk', $this->dict->matchDictionary('Toothbrush'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A new dictionary entry can be created and returned')]
    public function testDictionaryAdd() : void
    {
        $this->dict->add('Carton');
        self::assertEquals('Carton', $this->dict->matchDictionary('carton'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Two texts can be compared on a per word basis for similarity')]
    public function testValueWords() : void
    {
        // every word in s1 is found in s2, therefore a "perfect" match
        self::assertEquals(0, StringCompare::valueWords('This is a test', 'This is not a test'));

        // a is compared to is which has a distance of 2
        self::assertEquals(2, StringCompare::valueWords('This is a test', 'This is not test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testJaro() : void
    {
        self::assertEqualsWithDelta(0.944444, StringCompare::jaro('MARTHA', 'MARHTA'), 0.01);
        self::assertEqualsWithDelta(0.766667, StringCompare::jaro('DIXON', 'DICKSONX'), 0.01);
        self::assertEqualsWithDelta(0.896296, StringCompare::jaro('JELLYFISH', 'SMELLYFISH'), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testJaroEmpty() : void
    {
        self::assertEquals(1.0, StringCompare::jaro('', ''));
        self::assertEquals(0.0, StringCompare::jaro('', 'test'));
        self::assertEquals(0.0, StringCompare::jaro('test', ''));
    }
}
