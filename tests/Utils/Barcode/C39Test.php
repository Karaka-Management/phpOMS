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

    /**
     * @covers phpOMS\Utils\Barcode\C39<extended>
     * @group framework
     */
    public function testImagePng() : void
    {
        $path = __DIR__ . '/c39.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 150, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C39<extended>
     * @group framework
     */
    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c39.jpg';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 150, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C39<extended>
     * @group framework
     */
    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c39_vertical.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C39('ABCDEFG0123+-', 50, 150, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C39<extended>
     * @group framework
     */
    public function testValidString() : void
    {
        self::assertTrue(C39::isValidString('ABCDEFG0123+-'));
        self::assertFalse(C39::isValidString('ABC(DEFG0123+-'));
    }
}
