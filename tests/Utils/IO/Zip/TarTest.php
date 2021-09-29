<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\IO\Zip;

use phpOMS\Utils\IO\Zip\Tar;

/**
 * @testdox phpOMS\tests\Utils\IO\Zip\TarTest: Tar archive
 *
 * @internal
 */
class TarTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('phar')) {
            $this->markTestSkipped(
              'The Phar extension is not available.'
            );
        }
    }

    /**
     * @testdox Data can be tar packed and unpacked
     * @covers phpOMS\Utils\IO\Zip\Tar
     * @group framework
     */
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

        /* @todo: fix this, this is not working "cannot open test.tar"
        // second test
        self::assertTrue(Tar::pack(
            [__DIR__ . '/test' => 'test'],
            __DIR__ . '/test.tar'
        ));

        self::assertTrue(Tar::unpack(__DIR__ . '/test.tar', __DIR__ . '/new_dir'));
        self::assertFileExists(__DIR__ . '/new_dir/test');
        self::assertEquals($c, \file_get_contents(__DIR__ . '/new_dir/test/test c.txt'));

        \unlink(__DIR__ . '/new_dir/test/test c.txt');
        \unlink(__DIR__ . '/new_dir/test/test d.txt');
        \unlink(__DIR__ . '/new_dir/test/sub/test e.txt');
        \rmdir(__DIR__ . '/new_dir/test/sub');
        \rmdir(__DIR__ . '/new_dir/test');
        \rmdir(__DIR__ . '/new_dir');

        \unlink(__DIR__ . '/test.tar');
        */
    }

    /**
     * @testdox Extracting invalid tar files fail
     * @covers phpOMS\Utils\IO\Zip\Tar
     * @group framework
     */
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

    /**
     * @testdox A tar archive cannot be overwritten by default
     * @covers phpOMS\Utils\IO\Zip\Tar
     * @group framework
     */
    public function testInvalidTar() : void
    {
        Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test2.tar'
        );

        self::assertFalse(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test2.tar'
        ));

        \unlink(__DIR__ . '/test2.tar');
    }

    /**
     * @testdox A none-existing source cannot be unpacked
     * @covers phpOMS\Utils\IO\Zip\Tar
     * @group framework
     */
    public function testInvalidUnpackSource() : void
    {
        self::assertFalse(Tar::unpack(__DIR__ . '/test.tar', __DIR__));
    }

    /**
     * @testdox A destination cannot be overwritten
     * @covers phpOMS\Utils\IO\Zip\Tar
     * @group framework
     */
    public function testInvalidUnpackDestination() : void
    {
        self::assertTrue(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test3.tar'
        ));

        Tar::unpack(__DIR__ . '/abc/test3.tar', __DIR__);
        self::assertFalse(Tar::unpack(__DIR__ . '/abc/test3.tar', __DIR__));

        \unlink(__DIR__ . '/test3.tar');
    }
}
