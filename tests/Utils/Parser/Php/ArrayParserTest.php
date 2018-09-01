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
        $serializable = new class implements \Serializable { 
            public function serialize() { return 2; } 
            public function unserialize($raw) {}
        };

        $jsonSerialize = new class implements \jsonSerializable {
            public function jsonSerialize() { return [6, 7]; }
        };

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
            5 => $serializable,
            6 => $jsonSerialize,
        ];

        $expected = [
            'string' => 'test',
            0 => 1,
            2 => true,
            'string2' => 1.3,
            3 => null,
            4 => [
                0 => 'a',
                1 => 'b',
            ],
            5 => $serializable->serialize(),
            6 => $jsonSerialize->jsonSerialize(),
        ];

        self::assertEquals($expected, eval('return '. ArrayParser::serializeArray($array) . ';'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidValueType()
    {
        ArrayParser::parseVariable(new class {});
    }
}
