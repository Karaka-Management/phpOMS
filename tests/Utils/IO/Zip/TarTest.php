<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\IO\Zip;

use phpOMS\Utils\IO\Zip\Tar;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\IO\Zip\Tar::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\IO\Zip\TarTest: Tar archive')]
final class TarTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('phar')) {
            $this->markTestSkipped(
              'The Phar extension is not available.'
            );
        }

        if (\is_dir('new_dir')) {
            \rmdir('new_dir');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be tar packed and unpacked')]
    public function testTar() : void
    {
        self::assertTrue(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
                __DIR__ . '/invalid.txt',
            ],
            __DIR__ . '/test.tar'
        ));

        self::assertFileExists(__DIR__ . '/test.tar');

        $a = \file_get_contents(__DIR__ . '/test a.txt');
        $b = \file_get_contents(__DIR__ . '/test b.md');
        $c = \file_get_contents(__DIR__ . '/test/test c.txt');
        $d = \file_get_contents(__DIR__ . '/test/test d.txt');
        $e = \file_get_contents(__DIR__ . '/test/sub/test e.txt');

        \unlink(__DIR__ . '/test a.txt');
        \unlink(__DIR__ . '/test b.md');
        \unlink(__DIR__ . '/test/test c.txt');
        \unlink(__DIR__ . '/test/test d.txt');
        \unlink(__DIR__ . '/test/sub/test e.txt');
        \rmdir(__DIR__ . '/test/sub');
        \rmdir(__DIR__ . '/test');

        self::assertTrue(Tar::unpack(__DIR__ . '/test.tar', __DIR__));

        self::assertFileExists(__DIR__ . '/test a.txt');
        self::assertFileExists(__DIR__ . '/test b.md');
        self::assertFileExists(__DIR__ . '/test/test c.txt');
        self::assertFileExists(__DIR__ . '/test/test d.txt');
        self::assertFileExists(__DIR__ . '/test/sub/test e.txt');
        self::assertFileExists(__DIR__ . '/test/sub');
        self::assertFileExists(__DIR__ . '/test');

        self::assertEquals($a, \file_get_contents(__DIR__ . '/test a.txt'));
        self::assertEquals($b, \file_get_contents(__DIR__ . '/test b.md'));
        self::assertEquals($c, \file_get_contents(__DIR__ . '/test/test c.txt'));
        self::assertEquals($d, \file_get_contents(__DIR__ . '/test/test d.txt'));
        self::assertEquals($e, \file_get_contents(__DIR__ . '/test/sub/test e.txt'));

        \unlink(__DIR__ . '/test.tar');

        // second test
        self::assertTrue(Tar::pack(
            __DIR__ . '/test',
            __DIR__ . '/test2.tar'
        ));

        self::assertTrue(Tar::unpack(__DIR__ . '/test2.tar', __DIR__ . '/new_dir'));
        self::assertFileExists(__DIR__ . '/new_dir');
        self::assertEquals($c, \file_get_contents(__DIR__ . '/new_dir/test c.txt'));

        \unlink(__DIR__ . '/new_dir/test c.txt');
        \unlink(__DIR__ . '/new_dir/test d.txt');
        \unlink(__DIR__ . '/new_dir/sub/test e.txt');
        \rmdir(__DIR__ . '/new_dir/sub');
        \rmdir(__DIR__ . '/new_dir');

        \unlink(__DIR__ . '/test2.tar');
    }

    public function testInvalidArchiveUnpack() : void
    {
        self::assertFalse(Tar::unpack(__DIR__ . '/malformed.tar', __DIR__));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Extracting invalid tar files fail')]
    public function testInvalidTarUnpack() : void
    {
        self::assertFalse(Tar::unpack(
            __DIR__ . '/invalid.tar',
            __DIR__
        ));

        self::assertFalse(Tar::unpack(
            __DIR__ . '/test a.txt',
            __DIR__
        ));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A tar archive cannot be overwritten by default')]
    public function testInvalidTar() : void
    {
        Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test3.tar'
        );

        self::assertFalse(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test3.tar'
        ));

        \unlink(__DIR__ . '/test3.tar');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing source cannot be unpacked')]
    public function testInvalidUnpackSource() : void
    {
        self::assertFalse(Tar::unpack(__DIR__ . '/test.tar', __DIR__));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A destination cannot be overwritten')]
    public function testInvalidUnpackDestination() : void
    {
        self::assertTrue(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test4.tar'
        ));

        Tar::unpack(__DIR__ . '/abc/test4.tar', __DIR__);
        self::assertFalse(Tar::unpack(__DIR__ . '/abc/test4.tar', __DIR__));

        \unlink(__DIR__ . '/test4.tar');
    }
}
