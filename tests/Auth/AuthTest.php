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

namespace phpOMS\tests\Auth;

use phpOMS\Auth\Auth;

require_once __DIR__ . '/../Autoloader.php';

class AuthTest extends \PHPUnit\Framework\TestCase
{
    public function testWithHttpSession()
    {
        self::assertEquals(0, Auth::authenticate($GLOBALS['httpSession']));

        Auth::logout($GLOBALS['httpSession']);
        self::assertEquals(0, Auth::authenticate($GLOBALS['httpSession']));
    }
}
