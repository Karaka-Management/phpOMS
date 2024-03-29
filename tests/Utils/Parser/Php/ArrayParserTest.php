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

namespace phpOMS\tests\Utils\Parser\Php;

use phpOMS\Contract\SerializableInterface;
use phpOMS\Utils\Parser\Php\ArrayParser;

/**
 * @testdox phpOMS\tests\Utils\Parser\Php\ArrayParserTest: Array data serializer as code
 *
 * @internal
 */
final class ArrayParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox An array can be encoded and decoded as php code
     * @covers phpOMS\Utils\Parser\Php\ArrayParser
     * @group framework
     */
    public function testParser() : void
    {
        $serializable = new class() implements SerializableInterface {
            public function serialize() : string { return '2'; }

            public function unserialize(mixed $raw) : void {}
        };

        $jsonSerialize = new class() implements \JsonSerializable {
            public function jsonSerialize() : mixed { return [6, 7]; }
        };

        $array = [
            'string'  => 'test',
            0         => 1,
            2         => true,
            'string2' => 1.3,
            3         => null,
            4         => [
                0 => 'a',
                1 => 'b',
            ],
            5 => $serializable,
            6 => $jsonSerialize,
        ];

        $expected = [
            'string'  => 'test',
            0         => 1,
            2         => true,
            'string2' => 1.3,
            3         => null,
            4         => [
                0 => 'a',
                1 => 'b',
            ],
            5 => $serializable->serialize(),
            6 => $jsonSerialize->jsonSerialize(),
        ];

        self::assertEquals($expected, eval('return '. ArrayParser::serializeArray($array) . ';'));
    }

    /**
     * @testdox A value can be encoded and decoded into php code
     * @covers phpOMS\Utils\Parser\Php\ArrayParser
     * @group framework
     */
    public function testInvalidValueType() : void
    {
        $this->expectException(\UnexpectedValueException::class);

        ArrayParser::parseVariable(new class() {});
    }
}
