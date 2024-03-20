<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Compression;

use phpOMS\Utils\Compression\LZW;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Compression\LZW::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Compression\LZWTest: LZW compression')]
final class LZWTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be LZW compressed and uncompressed')]
    public function testLZW() : void
    {
        $expected    = 'This is a test';
        $compression = new LZW();
        self::assertEquals($expected, $compression->decompress($compression->compress($expected)));
        self::assertEquals('', $compression->decompress(''));
    }
}
