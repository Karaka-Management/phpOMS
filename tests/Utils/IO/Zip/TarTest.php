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

namespace phpOMS\tests\Utils\IO\Tar;

use phpOMS\Utils\IO\Zip\Tar;

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

    public function testTar() : void
    {
        self::assertTrue(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.md' => 'test b.md',
                __DIR__ . '/test' => 'test',
            ],
            __DIR__ . '/test.tar'
        ));

        self::assertTrue(\file_exists(__DIR__ . '/test.tar'));

        self::assertFalse(Tar::pack(
            [
                __DIR__ . '/test a.txt' => 'test a.txt',
                __DIR__ . '/test b.txt' => 'test b.txt',
            ],
            __DIR__ . '/test.tar',
            false
        ));

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

        self::assertFalse(\file_exists(__DIR__ . '/test a.txt'));
        self::assertFalse(\file_exists(__DIR__ . '/test b.md'));
        self::assertFalse(\file_exists(__DIR__ . '/test/test c.txt'));
        self::assertFalse(\file_exists(__DIR__ . '/test/test d.txt'));
        self::assertFalse(\file_exists(__DIR__ . '/test/sub/test e.txt'));
        self::assertFalse(\file_exists(__DIR__ . '/test/sub'));
        self::assertFalse(\file_exists(__DIR__ . '/test'));

        self::assertTrue(Tar::unpack(__DIR__ . '/test.tar', __DIR__));

        self::assertTrue(\file_exists(__DIR__ . '/test a.txt'));
        self::assertTrue(\file_exists(__DIR__ . '/test b.md'));
        self::assertTrue(\file_exists(__DIR__ . '/test/test c.txt'));
        self::assertTrue(\file_exists(__DIR__ . '/test/test d.txt'));
        self::assertTrue(\file_exists(__DIR__ . '/test/sub/test e.txt'));
        self::assertTrue(\file_exists(__DIR__ . '/test/sub'));
        self::assertTrue(\file_exists(__DIR__ . '/test'));

        self::assertEquals($a, \file_get_contents(__DIR__ . '/test a.txt'));
        self::assertEquals($b, \file_get_contents(__DIR__ . '/test b.md'));
        self::assertEquals($c, \file_get_contents(__DIR__ . '/test/test c.txt'));
        self::assertEquals($d, \file_get_contents(__DIR__ . '/test/test d.txt'));
        self::assertEquals($e, \file_get_contents(__DIR__ . '/test/sub/test e.txt'));

        \unlink(__DIR__ . '/test.tar');
        self::assertFalse(\file_exists(__DIR__ . '/test.tar'));
        self::assertFalse(Tar::unpack(__DIR__ . '/test.tar', __DIR__));
    }
}
