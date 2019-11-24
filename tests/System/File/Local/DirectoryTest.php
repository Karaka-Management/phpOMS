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

use phpOMS\System\File\Local\Directory;

/**
 * @internal
 */
class DirectoryTest extends \PHPUnit\Framework\TestCase
{
    public function testStaticCreate() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($dirPath));
        self::assertTrue(Directory::exists($dirPath));
        self::assertTrue(\is_dir($dirPath));

        \unlink($dirPath);
    }

    public function testStaticExists() : void
    {
        $dirPath = __DIR__;
        self::assertTrue(Directory::exists($dirPath));
    }

    public function testInvalidStaticExists() : void
    {
        $dirPath = __DIR__ . '/invalid/path/here';
        self::assertFalse(Directory::exists($dirPath));
    }

    public function testInvalidStaticOverwrite() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($dirPath));
        self::assertFalse(Directory::create($dirPath));

        \unlink($dirPath);
    }

    public function testStaticSubdir() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(Directory::create($dirPath, 0755, true));
        self::assertTrue(Directory::exists($dirPath));

        \unlink($dirPath);
    }

    public function testInvalidStaticSubdir() : void
    {
        self::assertFalse(Directory::create(__DIR__ . '/test/sub/path'));
    }

    public function testStaticNames() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::name($dirPath));
        self::assertEquals('test', Directory::basename($dirPath));
        self::assertEquals('test', Directory::dirname($dirPath));
    }

    public function testStaticParent() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\str_replace('\\', '/', \realpath($dirPath . '/../')), Directory::parent($dirPath));
    }

    public function testStaticDirectoryPath() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, Directory::dirpath($dirPath));
    }

    public function testStaticCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::created($dirPath)->format('Y-m-d'));

        \unlink($dirPath);
    }

    public function testStaticChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::changed($dirPath)->format('Y-m-d'));

        \unlink($dirPath);
    }

    public function testStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($dirPath));
        self::assertTrue(Directory::delete($dirPath));
        self::assertFalse(Directory::exists($dirPath));
    }

    public function testStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size($dirTestPath));
    }

    public function testInvalidStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(0, Directory::size($dirTestPath));
    }

    public function testStaticSize() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(Directory::size($dirTestPath, false), Directory::size($dirTestPath));
    }

    public function testStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::permission($dirTestPath));
    }

    public function testInvalidStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(0, Directory::permission($dirTestPath));
    }

    public function testStaticCopy() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(__DIR__ . '/newdirtest');
    }

    public function testStaticMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::move(__DIR__ . '/newdirtest', $dirTestPath);
    }

    public function testStaticCountRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, Directory::count($dirTestPath));
    }

    public function testStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, Directory::count($dirTestPath, false));
    }

    public function testInvalidStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(0, Directory::count($dirTestPath, false));
    }

    public function testStaticListFiles() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, Directory::list($dirTestPath));
    }

    public function testStaticListFilesByExtension() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(3, Directory::listByExtension($dirTestPath, 'txt'));
    }

    public function testInvalidListPath() : void
    {
        self::assertEquals([], Directory::list(__DIR__ . '/invalid.txt'));
    }

    public function testInvalidCopyPath() : void
    {
        self::assertFalse(Directory::copy(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    public function testInvalidMovePath() : void
    {
        self::assertFalse(Directory::move(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    public function testInvalidCreatedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::created(__DIR__ . '/invalid');
    }

    public function testInvalidChangedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::changed(__DIR__ . '/invalid');
    }

    public function testInvalidSizePath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::size(__DIR__ . '/invalid');
    }

    public function testInvalidPermissionPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::permission(__DIR__ . '/invalid');
    }

    public function testInvalidOwnerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::owner(__DIR__ . '/invalid');
    }
}
