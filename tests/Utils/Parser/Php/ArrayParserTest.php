<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Parser\Php;

use phpOMS\Utils\Parser\Php\ArrayParser;

class ArrayParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParser()
    {
        $array = [
            'string' => 'test',
            0 => 1,
            2 => true,
            'string2' => 1.3,
            3 => null,
            4 => [
                0 => 'a',
                1 => 'b',
            ],
        ];

        self::assertEquals($array, eval('return '. ArrayParser::serializeArray($array) . ';'));
    }
}
