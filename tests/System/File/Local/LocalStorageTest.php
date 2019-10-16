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

namespace phpOMS\tests\System\File\Local;

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Local\LocalStorage;

/**
 * @internal
 */
class LocalStorageTest extends \PHPUnit\Framework\TestCase
{
    public function testFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(LocalStorage::put($testFile, 'test', ContentPutMode::REPLACE));
        self::assertFalse(LocalStorage::exists($testFile));
        self::assertTrue(LocalStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(LocalStorage::exists($testFile));

        self::assertFalse(LocalStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(LocalStorage::put($testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', LocalStorage::get($testFile));
        self::assertTrue(LocalStorage::set($testFile, 'test3'));
        self::assertTrue(LocalStorage::append($testFile, 'test4'));
        self::assertEquals('test3test4', LocalStorage::get($testFile));
        self::assertTrue(LocalStorage::prepend($testFile, 'test5'));
        self::assertEquals('test5test3test4', LocalStorage::get($testFile));

        self::assertEquals(\str_replace('\\', '/', \realpath(\dirname($testFile) . '/../')), LocalStorage::parent($testFile));
        self::assertEquals('txt', LocalStorage::extension($testFile));
        self::assertEquals('test', LocalStorage::name($testFile));
        self::assertEquals('test.txt', LocalStorage::basename($testFile));
        self::assertEquals(\basename(\realpath(__DIR__)), LocalStorage::dirname($testFile));
        self::assertEquals(\realpath(__DIR__), LocalStorage::dirpath($testFile));
        self::assertEquals(1, LocalStorage::count($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), LocalStorage::created($testFile)->format('Y-m-d'));
        self::assertEquals($now->format('Y-m-d'), LocalStorage::changed($testFile)->format('Y-m-d'));

        self::assertGreaterThan(0, LocalStorage::size($testFile));
        self::assertGreaterThan(0, LocalStorage::permission($testFile));

        $newPath = __DIR__ . '/sub/path/testing.txt';
        self::assertTrue(LocalStorage::copy($testFile, $newPath));
        self::assertTrue(LocalStorage::exists($newPath));
        self::assertFalse(LocalStorage::copy($testFile, $newPath));
        self::assertTrue(LocalStorage::copy($testFile, $newPath, true));
        self::assertEquals('test5test3test4', LocalStorage::get($newPath));

        $newPath2 = __DIR__ . '/sub/path/testing2.txt';
        self::assertTrue(LocalStorage::move($testFile, $newPath2));
        self::assertTrue(LocalStorage::exists($newPath2));
        self::assertFalse(LocalStorage::exists($testFile));
        self::assertEquals('test5test3test4', LocalStorage::get($newPath2));

        self::assertTrue(LocalStorage::delete($newPath2));
        self::assertFalse(LocalStorage::exists($newPath2));
        self::assertFalse(LocalStorage::delete($newPath2));

        \unlink($newPath);
        \rmdir(__DIR__ . '/sub/path/');
        \rmdir(__DIR__ . '/sub/');

        self::assertTrue(LocalStorage::create($testFile));
        self::assertFalse(LocalStorage::create($testFile));
        self::assertEquals('', LocalStorage::get($testFile));

        \unlink($testFile);
    }

    public function testDirectory() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(LocalStorage::create($dirPath));
        self::assertTrue(LocalStorage::exists($dirPath));
        self::assertFalse(LocalStorage::create($dirPath));
        self::assertTrue(LocalStorage::create(__DIR__ . '/test/sub/path'));
        self::assertTrue(LocalStorage::exists(__DIR__ . '/test/sub/path'));

        self::assertEquals('test', LocalStorage::name($dirPath));
        self::assertEquals('test', LocalStorage::basename($dirPath));
        self::assertEquals('test', LocalStorage::dirname($dirPath));
        self::assertEquals(\str_replace('\\', '/', \realpath($dirPath . '/../')), LocalStorage::parent($dirPath));
        self::assertEquals($dirPath, LocalStorage::dirpath($dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), LocalStorage::created($dirPath)->format('Y-m-d'));
        self::assertEquals($now->format('Y-m-d'), LocalStorage::changed($dirPath)->format('Y-m-d'));

        self::assertTrue(LocalStorage::delete($dirPath));
        self::assertFalse(LocalStorage::exists($dirPath));

        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, LocalStorage::size($dirTestPath));
        self::assertGreaterThan(LocalStorage::size($dirTestPath, false), LocalStorage::size($dirTestPath));
        self::assertGreaterThan(0, LocalStorage::permission($dirTestPath));
    }

    public function testDirectoryMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(LocalStorage::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        self::assertTrue(LocalStorage::delete($dirTestPath));
        self::assertFalse(LocalStorage::exists($dirTestPath));

        self::assertTrue(LocalStorage::move(__DIR__ . '/newdirtest', $dirTestPath));
        self::assertFileExists($dirTestPath . '/sub/path/test3.txt');

        self::assertEquals(4, LocalStorage::count($dirTestPath));
        self::assertEquals(1, LocalStorage::count($dirTestPath, false));

        self::assertCount(6, LocalStorage::list($dirTestPath));
    }

    public function testInvalidPutPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::put(__DIR__, 'Test');
    }

    public function testInvalidGetPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::get(__DIR__);
    }

    public function testInvalidListPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::list(__DIR__ . '/LocalStorageTest.php');
    }

    public function testInvalidSetPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::set(__DIR__, 'Test');
    }

    public function testInvalidAppendPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::append(__DIR__, 'Test');
    }

    public function testInvalidPrependPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::prepend(__DIR__, 'Test');
    }

    public function testInvalidExtensionPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        LocalStorage::extension(__DIR__);
    }
}
