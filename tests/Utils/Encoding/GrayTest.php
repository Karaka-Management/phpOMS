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

use phpOMS\Utils\Encoding\Gray;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Encoding\Gray::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Encoding\GrayTest: Gray text encoding/decoding')]
final class GrayTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Text can be encoded and decoded with the gray encoding')]
    public function testEncoding() : void
    {
        self::assertEquals(55, Gray::encode(37));
        self::assertEquals(37, Gray::decode(55));
    }
}
