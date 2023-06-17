<?php
/**
 * Jingga
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

use phpOMS\Utils\Barcode\QR;

/**
 * @internal
 */
final class QRTest extends \PHPUnit\Framework\TestCase
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
     * @covers phpOMS\Utils\Barcode\QR<extended>
     * @group framework
     */
    public function testImagePng() : void
    {
        $path = __DIR__ . '/qr.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new QR('https://jingga.app', 200, 200);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    /**
     * @covers phpOMS\Utils\Barcode\QR<extended>
     * @group framework
     */
    public function testImageJpg() : void
    {
        $path = __DIR__ . '/qr.jpg';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new QR('https://jingga.app', 200, 200);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }
}
