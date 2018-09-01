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

use phpOMS\Utils\Barcode\C39;

class C39Test extends \PHPUnit\Framework\TestCase
{
    public function testImage()
    {
        $path = __DIR__ . '/c39.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 150, 50);
        $img->saveToPngFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testValidString()
    {
        self::assertTrue(C39::isValidString('ABCDEFG0123+-'));
        self::assertFalse(C39::isValidString('ABC(DEFG0123+-'));
    }
}
