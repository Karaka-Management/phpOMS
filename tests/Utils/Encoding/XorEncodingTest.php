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

namespace phpOMS\tests\Utils\Encoding;

use phpOMS\Utils\Encoding\XorEncoding;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Encoding\XorEncoding::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Encoding\XorEncodingTest: XOR text encoding/decoding')]
final class XorEncodingTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Text can be encoded and decoded with the xor encoding')]
    public function testEncoding() : void
    {
        $test = XorEncoding::encode('This is a test.', 'abcd');
        self::assertEquals(\hex2bin('350a0a17410b10440042170112164d'), XorEncoding::encode('This is a test.', 'abcd'));
        self::assertEquals('This is a test.', XorEncoding::decode(\hex2bin('350a0a17410b10440042170112164d'), 'abcd'));
    }
}
