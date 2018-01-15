<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\Codebar;

class CodebarTest extends \PHPUnit\Framework\TestCase
{
    public function testImage()
    {
        $path = __DIR__ . '/codebar.png';
        if (file_exists($path)) {
            unlink($path);
        }

        $img = new Codebar('412163', 200, 50);
        $img->saveToPngFile($path);

        self::assertTrue(file_exists($path));
    }
}
