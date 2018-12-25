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

use phpOMS\Utils\Barcode\Codebar;
use phpOMS\Utils\Barcode\OrientationType;

class CodebarTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped(
              'The GD extension is not available.'
            );
        }
    }

    public function testImagePng()
    {
        $path = __DIR__ . '/codebar.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new Codebar('412163', 200, 50);
        $img->saveToPngFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testImageJpg()
    {
        $path = __DIR__ . '/codebar.jpg';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new Codebar('412163', 200, 50);
        $img->saveToJpgFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testOrientationAndMargin()
    {
        $path = __DIR__ . '/ccodebar_vertical.png';
        if (\file_exists($path)) {
            \unlink($path);
        }

        $img = new Codebar('412163', 50, 200, OrientationType::VERTICAL);
        $img->setMargin(2);
        $img->saveToPngFile($path);

        self::assertTrue(\file_exists($path));
    }

    public function testValidString()
    {
        self::assertTrue(Codebar::isValidString('412163'));
        self::assertFalse(Codebar::isValidString('412163F'));
    }
}
