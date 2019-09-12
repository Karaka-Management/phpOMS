<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C25;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
class C25Test extends \PHPUnit\Framework\TestCase
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
        $path = __DIR__ . '/c25.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 150, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c25.jpg';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 150, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c25_vertical.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testValidString() : void
    {
        self::assertTrue(C25::isValidString('1234567890'));
        self::assertFalse(C25::isValidString('1234567A890'));
    }

    public function testInvalidOrientation() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $img = new C25('45f!a?12');
    }
}
