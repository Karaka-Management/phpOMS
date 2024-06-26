<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Dispatcher;

class TestController
{
    public function testFunction($req, $resp, $data = null) : bool
    {
        return true;
    }

    public function testFunctionNoPara() : bool
    {
        return true;
    }

    public static function testFunctionStatic($req, $resp, $data = null) : bool
    {
        return true;
    }
}
