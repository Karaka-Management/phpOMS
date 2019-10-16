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

namespace phpOMS\tests\Utils\IO\Gz;

use phpOMS\Utils\IO\Zip\Gz;

/**
 * @internal
 */
class GzTest extends \PHPUnit\Framework\TestCase
{
    public function testGz() : void
    {
        self::assertTrue(Gz::pack(
            __DIR__ . '/test a.txt',
            __DIR__ . '/test.gz'
        ));

        self::assertFileExists(__DIR__ . '/test.gz');

        $a = \file_get_contents(__DIR__ . '/test a.txt');

        \unlink(__DIR__ . '/test a.txt');

        self::assertFileNotExists(__DIR__ . '/test a.txt');
        self::assertTrue(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test a.txt'));
        self::assertFileExists(__DIR__ . '/test a.txt');
        self::assertEquals($a, \file_get_contents(__DIR__ . '/test a.txt'));

        \unlink(__DIR__ . '/test.gz');
        self::assertFileNotExists(__DIR__ . '/test.gz');
        self::assertFalse(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test a.txt'));
    }
}
