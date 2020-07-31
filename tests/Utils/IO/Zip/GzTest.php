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

namespace phpOMS\tests\Utils\IO\Zip;

use phpOMS\Utils\IO\Zip\Gz;

/**
 * @testdox phpOMS\tests\Utils\IO\Zip\GzTest: Gz archive
 *
 * @internal
 */
class GzTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Data can be gz packed and unpacked
     * @covers phpOMS\Utils\IO\Zip\Gz
     * @group framework
     */
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

    /**
     * @testdox A gz archive cannot be overwritten by default
     * @covers phpOMS\Utils\IO\Zip\Gz
     * @group framework
     */
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

    /**
     * @testdox A none-existing source cannot be unpacked
     * @covers phpOMS\Utils\IO\Zip\Gz
     * @group framework
     */
    public function testInvalidUnpackSource() : void
    {
        self::assertFalse(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test c.txt'));
    }

    /**
     * @testdox A destination cannot be overwritten
     * @covers phpOMS\Utils\IO\Zip\Gz
     * @group framework
     */
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
