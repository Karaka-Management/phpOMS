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

use phpOMS\Utils\Barcode\C128c;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
class C128cTest extends \PHPUnit\Framework\TestCase
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
        $path = __DIR__ . '/c128c.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128c('412163', 200, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c128c.jpg';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128c('412163', 200, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c128c_vertical.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new C128c('412163', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }
}
