<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\IO\Gz;

use phpOMS\Utils\IO\Zip\Gz;

class GzTest extends \PHPUnit\Framework\TestCase
{
    public function testGz() : void
    {
        self::assertTrue(Gz::pack(
            __DIR__ . '/test a.txt',
            __DIR__ . '/test.gz'
        ));

        self::assertTrue(\file_exists(__DIR__ . '/test.gz'));

        $a = \file_get_contents(__DIR__ . '/test a.txt');

        \unlink(__DIR__ . '/test a.txt');

        self::assertFalse(\file_exists(__DIR__ . '/test a.txt'));
        self::assertTrue(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test a.txt'));
        self::assertTrue(\file_exists(__DIR__ . '/test a.txt'));
        self::assertEquals($a, \file_get_contents(__DIR__ . '/test a.txt'));

        \unlink(__DIR__ . '/test.gz');
        self::assertFalse(\file_exists(__DIR__ . '/test.gz'));
        self::assertFalse(Gz::unpack(__DIR__ . '/test.gz', __DIR__ . '/test a.txt'));
    }
}
