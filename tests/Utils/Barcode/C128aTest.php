<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C128a;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
class C128aTest extends \PHPUnit\Framework\TestCase
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
        $path = __DIR__ . '/c128a.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128a('ABCDEFG0123()+-', 200, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c128a.jpg';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128a('ABCDEFG0123()+-', 200, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c128a_vertical.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128a('ABCDEFG0123()+-', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testValidString() : void
    {
        self::assertTrue(C128a::isValidString('ABCDEFG0123+-'));
        self::assertFalse(C128a::isValidString('ABCDE~FG0123+-'));
    }
}
