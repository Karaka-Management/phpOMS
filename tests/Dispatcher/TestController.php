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
namespace phpOMS\tests\Dispatcher;

class TestController
{
    public function testFunction($req, $resp, $data = null)
    {
        return true;
    }

    public function testFunctionNoPara()
    {
        return true;
    }

    public static function testFunctionStatic($req, $resp, $data = null)
    {
        return true;
    }
}
