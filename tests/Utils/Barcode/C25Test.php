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

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C25;

class C25Test extends \PHPUnit\Framework\TestCase
{
    public function testImage()
    {
        $path = __DIR__ . '/c25.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 150, 50);
        $img->saveToPngFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testValidString()
    {
        self::assertTrue(C25::isValidString('1234567890'));
        self::assertFalse(C25::isValidString('1234567A890'));
    }
}
