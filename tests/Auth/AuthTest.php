<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Auth;

use phpOMS\Auth\Auth;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Auth\AuthTest: Account and session authentication
 *
 * @internal
 */
final class AuthTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The default http session doesn't authenticate an account
     * @group framework
     */
    public function testAuthWithEmptyHttpSession() : void
    {
        self::assertEquals(0, Auth::authenticate($GLOBALS['httpSession']));

        Auth::logout($GLOBALS['httpSession']);
        self::assertEquals(0, Auth::authenticate($GLOBALS['httpSession']));
    }
}
