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

use phpOMS\Utils\IO\Zip\Zip;

/**
 * @testdox phpOMS\tests\Utils\IO\Zip\ZipTest: Zip archive
 *
 * @internal
 */
class ZipTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (!\extension_loaded('zip')) {
            $this->markTestSkipped(
              'The ZIP extension is not available.'
            );
        }
    }

    /**
     * @testdox Data can be zip packed and unpacked
     * @covers phpOMS\Utils\IO\Zip\Zip
     * @group framework
     */
    public function testZip() : void
    {
        self::assertTrue(Zip::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/invalid.so' => 'invalid.so',
                __DIR__ . '/test'       => 'test',
                __DIR__ . '/invalid.txt',
            ],
            __DIR__ . '/test.zip'
        ));

        self::assertFileExists(__DIR__ . '/test.zip');

        $a = file_get_contents(__DIR__ . '/test a.txt');
        $b = file_get_contents(__DIR__ . '/test b.md');
        $c = file_get_contents(__DIR__ . '/test/test c.txt');
        $d = file_get_contents(__DIR__ . '/test/test d.txt');
        $e = file_get_contents(__DIR__ . '/test/sub/test e.txt');

        unlink(__DIR__ . '/test a.txt');
        unlink(__DIR__ . '/test b.md');
        unlink(__DIR__ . '/test/test c.txt');
        unlink(__DIR__ . '/test/test d.txt');
        unlink(__DIR__ . '/test/sub/test e.txt');
        rmdir(__DIR__ . '/test/sub');
        rmdir(__DIR__ . '/test');

        self::assertTrue(Zip::unpack(__DIR__ . '/test.zip', __DIR__));

        self::assertFileExists(__DIR__ . '/test a.txt');
        self::assertFileExists(__DIR__ . '/test b.md');
        self::assertFileExists(__DIR__ . '/test/test c.txt');
        self::assertFileExists(__DIR__ . '/test/test d.txt');
        self::assertFileExists(__DIR__ . '/test/sub/test e.txt');
        self::assertFileExists(__DIR__ . '/test/sub');
        self::assertFileExists(__DIR__ . '/test');

        self::assertEquals($a, file_get_contents(__DIR__ . '/test a.txt'));
        self::assertEquals($b, file_get_contents(__DIR__ . '/test b.md'));
        self::assertEquals($c, file_get_contents(__DIR__ . '/test/test c.txt'));
        self::assertEquals($d, file_get_contents(__DIR__ . '/test/test d.txt'));
        self::assertEquals($e, file_get_contents(__DIR__ . '/test/sub/test e.txt'));

        unlink(__DIR__ . '/test.zip');

        // second test
        self::assertTrue(Zip::pack(
            __DIR__ . '/test',
            __DIR__ . '/test.zip'
        ));

        self::assertTrue(Zip::unpack(__DIR__ . '/test.zip', __DIR__ . '/new_dir'));
        self::assertFileExists(__DIR__ . '/new_dir');
        self::assertEquals($c, file_get_contents(__DIR__ . '/new_dir/test c.txt'));

        unlink(__DIR__ . '/new_dir/test c.txt');
        unlink(__DIR__ . '/new_dir/test d.txt');
        unlink(__DIR__ . '/new_dir/sub/test e.txt');
        rmdir(__DIR__ . '/new_dir/sub');
        rmdir(__DIR__ . '/new_dir');

        unlink(__DIR__ . '/test.zip');
    }

    /**
     * @testdox The output of the zip archive needs to be properly defined
     * @covers phpOMS\Utils\IO\Zip\Zip
     * @group framework
     */
    public function testInvalidZipDestination() : void
    {
        self::assertFalse(Zip::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__
        ));
    }

    /**
     * @testdox Extracting invalid zip files fail
     * @covers phpOMS\Utils\IO\Zip\Zip
     * @group framework
     */
    public function testInvalidZipUnpack() : void
    {
        self::assertFalse(Zip::unpack(
            __DIR__ . '/invalid.zip',
            __DIR__
        ));

        self::assertFalse(Zip::unpack(
            __DIR__ . '/test a.txt',
            __DIR__
        ));
    }

    /**
     * @testdox A zip archive cannot be overwritten by default
     * @covers phpOMS\Utils\IO\Zip\Zip
     * @group framework
     */
    public function testInvalidZip() : void
    {
        Zip::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test2.zip'
        );

        self::assertFalse(Zip::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test2.zip'
        ));

        unlink(__DIR__ . '/test2.zip');
    }

    /**
     * @testdox A none-existing source cannot be unpacked
     * @covers phpOMS\Utils\IO\Zip\Zip
     * @group framework
     */
    public function testInvalidUnpackSource() : void
    {
        self::assertFalse(Zip::unpack(__DIR__ . '/test.zip', __DIR__));
    }

    /**
     * @testdox A destination cannot be overwritten
     * @covers phpOMS\Utils\IO\Zip\Zip
     * @group framework
     */
    public function testInvalidUnpackDestination() : void
    {
        self::assertTrue(Zip::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md'  => 'test b.md',
                __DIR__ . '/test'       => 'test',
            ],
            __DIR__ . '/test3.zip'
        ));

        Zip::unpack(__DIR__ . '/abc/test3.zip', __DIR__);
        self::assertFalse(Zip::unpack(__DIR__ . '/abc/test3.zip', __DIR__));

        unlink(__DIR__ . '/test3.zip');
    }
}
