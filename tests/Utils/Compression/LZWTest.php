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

namespace phpOMS\tests\Utils\Compression;

use phpOMS\Utils\Compression\LZW;


class LZWTest extends \PHPUnit\Framework\TestCase
{
    public function testLZW()
    {
        $expected = 'This is a test';
        $compression = new LZW();
        self::assertEquals($expected, $compression->decompress($compression->compress($expected)));
    }
}
