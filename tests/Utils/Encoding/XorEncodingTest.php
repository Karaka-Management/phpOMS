<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Encoding;

use phpOMS\Utils\Encoding\XorEncoding;

/**
 * @testdox phpOMS\tests\Utils\Encoding\XorEncodingTest: XOR text encoding/decoding
 *
 * @internal
 */
final class XorEncodingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Text can be encoded and decoded with the xor encoding
     * @covers phpOMS\Utils\Encoding\XorEncoding
     * @group framework
     */
    public function testEncoding() : void
    {
        $test = XorEncoding::encode('This is a test.', 'abcd');
        self::assertEquals(\hex2bin('350a0a17410b10440042170112164d'), XorEncoding::encode('This is a test.', 'abcd'));
        self::assertEquals('This is a test.', XorEncoding::decode(\hex2bin('350a0a17410b10440042170112164d'), 'abcd'));
    }
}
