<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C128b;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
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

    /**
     * @covers phpOMS\Utils\Barcode\C128b<extended>
     * @group framework
     */
    public function testImagePng() : void
    {
        $path = __DIR__ . '/c128b.png';
        if (is_file($path)) {
            unlink($path);
        }

        $img = new C128b('ABcdeFG0123+-!@?', 200, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128b<extended>
     * @group framework
     */
    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c128b.jpg';
        if (is_file($path)) {
            unlink($path);
        }

        $img = new C128b('ABcdeFG0123+-!@?', 200, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128b<extended>
     * @group framework
     */
    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c128b_vertical.png';
        if (is_file($path)) {
            unlink($path);
        }

        $img = new C128b('ABcdeFG0123+-!@?', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128b<extended>
     * @group framework
     */
    public function testValidString() : void
    {
        self::assertTrue(C128b::isValidString('ABCDE~FG0123+-'));
    }
}
