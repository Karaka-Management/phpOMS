<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C128c;
use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Barcode\C128c::class)]
final class C128cTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('gd')) {
            $this->markTestSkipped(
              'The GD extension is not available.'
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testImagePng() : void
    {
        $path = __DIR__ . '/c128c.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C128c('412163', 200, 50);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testImageJpg() : void
    {
        $path = __DIR__ . '/c128c.jpg';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C128c('412163', 200, 50);
        $img->saveToJpgFile($path);

        self::assertFileExists($path);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testOrientationAndMargin() : void
    {
        $path = __DIR__ . '/c128c_vertical.png';
        if (\is_file($path)) {
            \unlink($path);
        }

        $img = new C128c('412163', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertFileExists($path);
    }
}
