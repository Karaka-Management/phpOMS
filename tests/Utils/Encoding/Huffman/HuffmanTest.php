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

namespace phpOMS\tests\Utils\Encoding\Huffman;

use phpOMS\Utils\Encoding\Huffman\Huffman;
use phpOMS\Utils\Encoding\Huffman\Dictionary;

class HuffmanTest extends \PHPUnit\Framework\TestCase
{
    public function testHuffman()
    {
        $huff = new Huffman();

        self::assertEquals(
            hex2bin('a42f5debafd35bee6a940f78f38638fb3f4d6fd13cc672cf01d61bb1ce59e03cdbe89e8e56b5d63aa61387d1ba10'),
            $huff->encode('This is a test message in order to test the encoding and decoding of the Huffman algorithm.')
        );

        self::assertEquals('', $huff->encode(''));

        $man = new Huffman();
        $man->setDictionary($huff->getDictionary());

        self::assertEquals(
            'This is a test message in order to test the encoding and decoding of the Huffman algorithm.',
            $man->decode(hex2bin('a42f5debafd35bee6a940f78f38638fb3f4d6fd13cc672cf01d61bb1ce59e03cdbe89e8e56b5d63aa61387d1ba10'))
        );

        self::assertEquals('', $man->decode(''));
    }
}
