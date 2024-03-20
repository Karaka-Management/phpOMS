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

namespace phpOMS\tests\Utils;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\ColorUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\ColorUtils::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\ColorUtilsTest: Color utilities')]
final class ColorUtilsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A integer can be converted to rgb')]
    public function testIntToRgb() : void
    {
        self::assertEquals(['r' => 0xbc, 'g' => 0x39, 'b' => 0x6c], ColorUtils::intToRgb(12335468));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Rgb can be converted to a integer')]
    public function testRgbToInt() : void
    {
        self::assertEquals(12335468, ColorUtils::rgbToInt(['r' => 0xbc, 'g' => 0x39, 'b' => 0x6c]));
    }
}
