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

namespace phpOMS\tests\Utils;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\ColorUtils;

/**
 * @testdox phpOMS\tests\Utils\ColorUtilsTest: Color utilities
 *
 * @internal
 */
final class ColorUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A integer can be converted to rgb
     * @covers phpOMS\Utils\ColorUtils
     * @group framework
     */
    public function testIntToRgb() : void
    {
        self::assertEquals(['r' => 0xbc, 'g' => 0x39, 'b' => 0x6c], ColorUtils::intToRgb(12335468));
    }

    /**
     * @testdox Rgb can be converted to a integer
     * @covers phpOMS\Utils\ColorUtils
     * @group framework
     */
    public function testRgbToInt() : void
    {
        self::assertEquals(12335468, ColorUtils::rgbToInt(['r' => 0xbc, 'g' => 0x39, 'b' => 0x6c]));
    }
}
