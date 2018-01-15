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

namespace phpOMS\tests;

use phpOMS\Autoloader;

class AutoloaderTest extends \PHPUnit\Framework\TestCase
{
    public function testAutoloader()
    {
        self::assertTrue(Autoloader::exists('\phpOMS\Autoloader'));
        self::assertFalse(Autoloader::exists('\Does\Not\Exist'));
    }
}
