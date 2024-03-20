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

namespace phpOMS\tests\Auth;

use phpOMS\Auth\Auth;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Auth\AuthTest: Account and session authentication')]
final class AuthTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("The default http session doesn't authenticate an account")]
    public function testAuthWithEmptyHttpSession() : void
    {
        self::assertEquals(0, Auth::authenticate($GLOBALS['session']));

        Auth::logout($GLOBALS['session']);
        self::assertEquals(0, Auth::authenticate($GLOBALS['session']));
    }
}
