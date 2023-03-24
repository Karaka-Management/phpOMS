<?php
/**
 * Karaka
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

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C25;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
final class C25Test extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('gd')) {
            $this->markTestSkipped(
              'The GD extension is not available.'
            );
        }
    }

    /**
     * @covers phpOMS\Utils\Barcode\C25<extended>
     * @group framework
     */
    public function testImagePng() : void
    {
        $path = __DIR__ . '/c25.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 150, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C25<extended>
     * @group framework
     */
    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c25.jpg';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 150, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C25<extended>
     * @group framework
     */
    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c25_vertical.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C25('1234567890', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C25<extended>
     * @group framework
     */
    public function testValidString() : void
    {
        self::assertTrue(C25::isValidString('1234567890'));
        self::assertFalse(C25::isValidString('1234567A890'));
    }

    /**
     * @covers phpOMS\Utils\Barcode\C25<extended>
     * @group framework
     */
    public function testInvalidOrientation() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $img = new C25('45f!a?12');
    }
}
