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

namespace phpOMS\tests\Utils\IO\Zip;

use phpOMS\Utils\IO\Zip\Gz;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\IO\Zip\Gz::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\IO\Zip\GzTest: Gz archive')]
final class GzTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be gz packed and unpacked')]
    public function testGz() : void
    {
        self::assertTrue(Gz::pack(
            __DIR__ . '/test a.txt',
            __DIR__ . '/test.gz'
        ));

        self::assertFileExists(__DIR__ . '/test.gz');

        $a = \file_get_contents(__DIR__ . '/test a.txt');

        \unlink(__DIR__ . '/test a.txt');
        self::assertFileDoesNotExist(__DIR__ . '/test a.txt');

        self::assertTrue(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test a.txt'));
        self::assertFileExists(__DIR__ . '/test a.txt');
        self::assertEquals($a, \file_get_contents(__DIR__ . '/test a.txt'));

        \unlink(__DIR__ . '/test.gz');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A gz archive cannot be overwritten by default')]
    public function testInvalidGz() : void
    {
        Gz::pack(
            __DIR__ . '/test a.txt',
            __DIR__ . '/test.gz'
        );

        self::assertFalse(Gz::pack(
            __DIR__ . '/test a.txt',
            __DIR__ . '/test.gz'
        ));

        \unlink(__DIR__ . '/test.gz');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing source cannot be unpacked')]
    public function testInvalidUnpackSource() : void
    {
        self::assertFalse(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test c.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A destination cannot be overwritten')]
    public function testInvalidUnpackDestination() : void
    {
        self::assertTrue(Gz::pack(
            __DIR__ . '/test a.txt',
            __DIR__ . '/test.gz'
        ));

        self::assertFalse(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test a.txt'));

        \unlink(__DIR__ . '/test.gz');
    }
}
