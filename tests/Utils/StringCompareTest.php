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

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\StringCompare;

class StringCompareTest extends \PHPUnit\Framework\TestCase
{
    public function testDictionary() : void
    {
        $dict = new StringCompare(
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

        self::assertEquals('Cartoon', $dict->matchDictionary('Cartoon'));
        self::assertEquals('Bathtub Sidewalk Table', $dict->matchDictionary('Sidewalk Table'));

        // too far apart
        self::assertNotEquals('Snowflake Bathtub Snowflake Toothbrush Sidewalk', $dict->matchDictionary('Toothbrush'));

        $dict->add('Carton');
        self::assertEquals('Carton', $dict->matchDictionary('carton'));
    }
}
