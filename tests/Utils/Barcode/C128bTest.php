<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C128b;
use phpOMS\Utils\Barcode\OrientationType;

class C128bTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('gd')) {
            $this->markTestSkipped(
              'The GD extension is not available.'
            );
        }
    }

    public function testImagePng() : void
    {
        $path = __DIR__ . '/c128b.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128b('ABcdeFG0123+-!@?', 200, 50);
        $img->saveToPngFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c128b.jpg';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128b('ABcdeFG0123+-!@?', 200, 50);
        $img->saveToJpgFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c128b_vertical.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128b('ABcdeFG0123+-!@?', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testValidString() : void
    {
        self::assertTrue(C128b::isValidString('ABCDE~FG0123+-'));
    }
}
