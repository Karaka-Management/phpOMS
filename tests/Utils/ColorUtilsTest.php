<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\ColorUtils;

class ColorUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testColor()
    {
        self::assertEquals(['r' => 0xbc, 'g' => 0x39, 'b' => 0x6c], ColorUtils::intToRgb(12335468));
        self::assertEquals(12335468, ColorUtils::rgbToInt(['r' => 0xbc, 'g' => 0x39, 'b' => 0x6c]));
    }
}
