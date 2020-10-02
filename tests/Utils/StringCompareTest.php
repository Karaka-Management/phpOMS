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

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\StringCompare;

/**
 * @testdox phpOMS\tests\Utils\StringCompareTest: String comparison / dictionary
 *
 * @internal
 */
class StringCompareTest extends \PHPUnit\Framework\TestCase
{
    private StringCompare $dict;

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

    /**
     * @testdox A string can be matched with a dictionary entry
     * @covers phpOMS\Utils\StringCompare
     * @group framework
     */
    public function testDictionaryMatch() : void
    {
        self::assertEquals('Cartoon', $this->dict->matchDictionary('Carton'));
        self::assertEquals('Bathtub Sidewalk Table', $this->dict->matchDictionary('Sidewalk Table'));
    }

    /**
     * @testdox A string doesn't match a dictionary entry if it is very different
     * @covers phpOMS\Utils\StringCompare
     * @group framework
     */
    public function testInvalidDictionary() : void
    {
        self::assertNotEquals('Snowflake Bathtub Snowflake Toothbrush Sidewalk', $this->dict->matchDictionary('Toothbrush'));
    }

    /**
     * @testdox A new dictionary entry can be created and returned
     * @covers phpOMS\Utils\StringCompare
     * @group framework
     */
    public function testDictionaryAdd() : void
    {
        $this->dict->add('Carton');
        self::assertEquals('Carton', $this->dict->matchDictionary('carton'));
    }

    /**
     * @testdox Two texts can be compared on a per word basis for similarity
     * @covers phpOMS\Utils\StringCompare
     * @group framework
     */
    public function testValueWords() : void
    {
        // every word in s1 is found in s2, therefore a "perfect" match
        self::assertEquals(0, StringCompare::valueWords('This is a test', 'This is not a test'));

        // a is compared to is which has a distance of 2
        self::assertEquals(2, StringCompare::valueWords('This is a test', 'This is not test'));
    }
}
