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
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C39;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
class C39Test extends \PHPUnit\Framework\TestCase
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
        $path = __DIR__ . '/c39.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 150, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c39.jpg';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 150, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c39_vertical.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 50, 150, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testValidString() : void
    {
        self::assertTrue(C39::isValidString('ABCDEFG0123+-'));
        self::assertFalse(C39::isValidString('ABC(DEFG0123+-'));
    }
}
