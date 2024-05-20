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

use phpOMS\Utils\Encoding\Caesar;
use phpOMS\Utils\RnG\StringUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Encoding\Caesar::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Encoding\CaesarTest: Caesar text encoding/decoding')]
final class CaesarTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Text can be encoded and decoded with the ceasar encoding')]
    public function testEncoding() : void
    {
        $raw = StringUtils::generateString(11, 100);
        $key = StringUtils::generateString(5, 10);

        self::assertNotEquals($raw, Caesar::encode($raw, $key));
        self::assertEquals($raw, Caesar::decode(Caesar::encode($raw, $key), $key));

        $raw = StringUtils::generateString(5, 10);
        $key = StringUtils::generateString(11, 100);

        self::assertNotEquals($raw, Caesar::encode($raw, $key));
        self::assertEquals($raw, Caesar::decode(Caesar::encode($raw, $key), $key));
    }
}
