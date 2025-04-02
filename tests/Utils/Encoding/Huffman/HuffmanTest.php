<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Encoding\Huffman;

use phpOMS\Utils\Encoding\Huffman\Huffman;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Encoding\Huffman\HuffmanTest: Data can be encoded with huffman')]
final class HuffmanTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Encoding and decoding empty data results in an empty output')]
    public function testEmpty() : void
    {
        $huff = new Huffman();
        self::assertEquals('', $huff->encode(''));
        self::assertEquals('', $huff->decode(''));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be huffman encoded and decoded')]
    public function testHuffman() : void
    {
        $huff = new Huffman();

        self::assertEquals(
            \hex2bin('a42f5debafd35bee6a940f78f38638fb3f4d6fd13cc672cf01d61bb1ce59e03cdbe89e8e56b5d63aa61387d1ba10'),
            $huff->encode('This is a test message in order to test the encoding and decoding of the Huffman algorithm.')
        );

        $man = new Huffman();
        $man->setDictionary($huff->getDictionary());

        self::assertEquals(
            'This is a test message in order to test the encoding and decoding of the Huffman algorithm.',
            $man->decode(\hex2bin('a42f5debafd35bee6a940f78f38638fb3f4d6fd13cc672cf01d61bb1ce59e03cdbe89e8e56b5d63aa61387d1ba10'))
        );
    }
}
