<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Security;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Security\PhpCode;

class RouteVerbTest extends \PHPUnit\Framework\TestCase
{
    public function testHasUnicode()
    {
        self::assertTrue(
            PhpCode::hasUnicode(
                PhpCode::normalizeSource(
                    file_get_contents(__DIR__ . '/Sample/hasUnicode.php')
                )
            )
        );

        self::assertFalse(
            PhpCode::hasUnicode(
                PhpCode::normalizeSource(
                    file_get_contents(__DIR__ . '/Sample/noUnicode.php')
                )
            )
        );
    }

    public function testDisabledFunctions()
    {
        self::assertFalse(PhpCode::isDisabled(['file_get_contents']));
        self::assertFalse(PhpCode::isDisabled(['eval', 'file_get_contents']));
    }

    public function testHasDeprecatedFunction()
    {
        self::assertTrue(
            PhpCode::hasDeprecatedFunction(
                PhpCode::normalizeSource(
                    file_get_contents(__DIR__ . '/Sample/hasDeprecated.php')
                )
            )
        );

        self::assertFalse(
            PhpCode::hasDeprecatedFunction(
                PhpCode::normalizeSource(
                    file_get_contents(__DIR__ . '/Sample/noDeprecated.php')
                )
            )
        );
    }
}
