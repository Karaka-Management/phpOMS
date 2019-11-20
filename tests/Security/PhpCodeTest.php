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

namespace phpOMS\tests\Security;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Security\PhpCode;

/**
 * @testdox phpOMS\tests\Security\PhpCodeTest: Basic php source code security inspection
 *
 * @internal
 */
class RouteVerbTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A file with unicode characters gets correctly identified
     * @covers phpOMS\Security\PhpCode
     */
    public function testHasUnicode() : void
    {
        self::assertTrue(
            PhpCode::hasUnicode(
                PhpCode::normalizeSource(
                    \file_get_contents(__DIR__ . '/Sample/hasUnicode.php')
                )
            )
        );
    }

    /**
     * @testdox A file with no unicode characters gets correctly identified
     * @covers phpOMS\Security\PhpCode
     */
    public function testHasNoUnicode() : void
    {
        self::assertFalse(
            PhpCode::hasUnicode(
                PhpCode::normalizeSource(
                    \file_get_contents(__DIR__ . '/Sample/noUnicode.php')
                )
            )
        );
    }

    /**
     * @testdox A file with no disabled functions gets correctly identified
     * @covers phpOMS\Security\PhpCode
     */
    public function testDisabledFunctions() : void
    {
        self::assertFalse(PhpCode::isDisabled(['file_get_contents']));
        self::assertFalse(PhpCode::isDisabled(['eval', 'file_get_contents']));
    }

    /**
     * @testdox A file with deprecated functions gets correctly identified
     * @covers phpOMS\Security\PhpCode
     */
    public function testHasDeprecatedFunction() : void
    {
        self::assertTrue(
            PhpCode::hasDeprecatedFunction(
                PhpCode::normalizeSource(
                    \file_get_contents(__DIR__ . '/Sample/hasDeprecated.php')
                )
            )
        );
    }

    /**
     * @testdox A file with no deprecated functions gets correctly identified
     * @covers phpOMS\Security\PhpCode
     */
    public function testHasNoDeprecatedFunction() : void
    {
        self::assertFalse(
            PhpCode::hasDeprecatedFunction(
                PhpCode::normalizeSource(
                    \file_get_contents(__DIR__ . '/Sample/noDeprecated.php')
                )
            )
        );
    }

    /**
     * @testdox A file hash comparison is successfull if the file generates the same hash
     * @covers phpOMS\Security\PhpCode
     */
    public function testFileIntegrity() : void
    {
        self::assertTrue(PhpCode::validateFileIntegrity(__DIR__ . '/Sample/hasDeprecated.php', \md5_file(__DIR__ . '/Sample/hasDeprecated.php')));
    }

    /**
     * @testdox A file hash comparison is unsuccessfull if the file generates a different hash
     * @covers phpOMS\Security\PhpCode
     */
    public function testFileInvalidIntegrity() : void
    {
        self::assertFalse(PhpCode::validateFileIntegrity(__DIR__ . '/Sample/hasUnicode.php', \md5_file(__DIR__ . '/Sample/hasDeprecated.php')));
    }

    /**
     * @testdox Two equal strings validate as the same
     * @covers phpOMS\Security\PhpCode
     */
    public function testStringIntegrity() : void
    {
        self::assertTrue(PhpCode::validateStringIntegrity('aa', 'aa'));
    }

    /**
     * @testdox Two different strings don't validate as the same
     * @covers phpOMS\Security\PhpCode
     */
    public function testStringInvalidIntegrity() : void
    {
        self::assertFalse(PhpCode::validateStringIntegrity('aa', 'aA'));
    }
}
