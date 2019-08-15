<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\System\File\Ftp;

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Ftp\Directory;
use phpOMS\System\File\Ftp\File;
use phpOMS\Uri\Http;

/**
 * @internal
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    const BASE = 'ftp://test:123456@127.0.0.1:20';

    private $con = null;

    protected function setUp() : void
    {
        if ($this->con === null) {
            $this->con = File::ftpConnect(new Http(self::BASE));
        }
    }

    public function testStatic() : void
    {
        self::assertNotFalse($this->con);

        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(File::put($this->con, $testFile, 'test', ContentPutMode::REPLACE));
        self::assertFalse(File::exists($this->con, $testFile));
        self::assertTrue(File::put($this->con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::exists($this->con, $testFile));

        self::assertFalse(File::put($this->con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($this->con, $testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', File::get($this->con, $testFile));
        self::assertTrue(File::set($this->con, $testFile, 'test3'));
        self::assertTrue(File::append($this->con, $testFile, 'test4'));
        self::assertEquals('test3test4', File::get($this->con, $testFile));
        self::assertTrue(File::prepend($this->con, $testFile, 'test5'));
        self::assertEquals('test5test3test4', File::get($this->con, $testFile));

        self::assertEquals(\str_replace('\\', '/', \realpath(\dirname($testFile))), File::parent($testFile));
        self::assertEquals('txt', File::extension($testFile));
        self::assertEquals('test', File::name($testFile));
        self::assertEquals('test.txt', File::basename($testFile));
        self::assertEquals(\basename(\realpath(__DIR__)), File::dirname($testFile));
        self::assertEquals(\realpath(__DIR__), File::dirpath($testFile));
        self::assertEquals(1, File::count($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::created($this->con, $testFile)->format('Y-m-d'));
        self::assertEquals($now->format('Y-m-d'), File::changed($this->con, $testFile)->format('Y-m-d'));

        self::assertGreaterThan(0, File::size($this->con, $testFile));
        self::assertGreaterThan(0, File::permission($this->con, $testFile));

        $newPath = __DIR__ . '/sub/path/testing.txt';
        self::assertTrue(File::copy($this->con, $testFile, $newPath));
        self::assertTrue(File::exists($this->con, $newPath));
        self::assertFalse(File::copy($this->con, $testFile, $newPath));
        self::assertTrue(File::copy($this->con, $testFile, $newPath, true));
        self::assertEquals('test5test3test4', File::get($this->con, $newPath));

        $newPath2 = __DIR__ . '/sub/path/testing2.txt';
        self::assertTrue(File::move($this->con, $testFile, $newPath2));
        self::assertTrue(File::exists($this->con, $newPath2));
        self::assertFalse(File::exists($this->con, $testFile));
        self::assertEquals('test5test3test4', File::get($this->con, $newPath2));

        self::assertTrue(File::delete($this->con, $newPath2));
        self::assertFalse(File::exists($this->con, $newPath2));
        self::assertFalse(File::delete($this->con, $newPath2));

        File::delete($this->con, $newPath);
        Directory::delete($this->con, __DIR__ . '/sub');

        self::assertTrue(File::create($this->con, $testFile));
        self::assertFalse(File::create($this->con, $testFile));
        self::assertEquals('', File::get($this->con, $testFile));

        \unlink($testFile);
    }

    public function testInvalidGetPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        File::get($this->con, __DIR__ . '/invalid.txt');
    }

    public function testInvalidCopyPath() : void
    {
        self::assertNotFalse($this->con);
        self::assertFalse(File::copy($this->con, __DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    public function testInvalidMovePath() : void
    {
        self::assertNotFalse($this->con);
        self::assertFalse(File::move($this->con, __DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    public function testInvalidCreatedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        File::created($this->con, __DIR__ . '/invalid.txt');
    }

    public function testInvalidChangedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        File::changed($this->con, __DIR__ . '/invalid.txt');
    }

    public function testInvalidSizePath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        File::size($this->con, __DIR__ . '/invalid.txt');
    }

    public function testInvalidPermissionPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        File::permission($this->con, __DIR__ . '/invalid.txt');
    }

    public function testInvalidOwnerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        File::owner($this->con, __DIR__ . '/invalid.txt');
    }
}
