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

namespace phpOMS\tests\Security;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Security\PhpCode;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Security\PhpCode::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Security\PhpCodeTest: Basic php source code security inspection')]
final class PhpCodeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with unicode characters gets correctly identified')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with no unicode characters gets correctly identified')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with no disabled functions gets correctly identified')]
    public function testDisabledFunctions() : void
    {
        self::assertFalse(PhpCode::isDisabled(['file_get_contents']));
        self::assertFalse(PhpCode::isDisabled(['eval', 'file_get_contents']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with deprecated functions gets correctly identified')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with no deprecated functions gets correctly identified')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file hash comparison is successful if the file generates the same hash')]
    public function testFileIntegrity() : void
    {
        self::assertTrue(PhpCode::validateFileIntegrity(__DIR__ . '/Sample/hasDeprecated.php', \md5_file(__DIR__ . '/Sample/hasDeprecated.php')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file hash comparison is unsuccessful if the file generates a different hash')]
    public function testFileInvalidIntegrity() : void
    {
        self::assertFalse(PhpCode::validateFileIntegrity(__DIR__ . '/Sample/hasUnicode.php', \md5_file(__DIR__ . '/Sample/hasDeprecated.php')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Two equal strings validate as the same')]
    public function testStringIntegrity() : void
    {
        self::assertTrue(PhpCode::validateStringIntegrity('aa', 'aa'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Two different strings don't validate as the same")]
    public function testStringInvalidIntegrity() : void
    {
        self::assertFalse(PhpCode::validateStringIntegrity('aa', 'aA'));
    }
}
