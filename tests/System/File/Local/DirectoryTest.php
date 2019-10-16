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
    public function testStatic() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($dirPath));
        self::assertTrue(Directory::exists($dirPath));
        self::assertFalse(Directory::create($dirPath));
        self::assertFalse(Directory::create(__DIR__ . '/test/sub/path'));
        self::assertTrue(Directory::create(__DIR__ . '/test/sub/path', 0755, true));
        self::assertTrue(Directory::exists(__DIR__ . '/test/sub/path'));

        self::assertEquals('test', Directory::name($dirPath));
        self::assertEquals('test', Directory::basename($dirPath));
        self::assertEquals('test', Directory::dirname($dirPath));
        self::assertEquals(\str_replace('\\', '/', \realpath($dirPath . '/../')), Directory::parent($dirPath));
        self::assertEquals($dirPath, Directory::dirpath($dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::created($dirPath)->format('Y-m-d'));
        self::assertEquals($now->format('Y-m-d'), Directory::changed($dirPath)->format('Y-m-d'));

        self::assertTrue(Directory::delete($dirPath));
        self::assertFalse(Directory::exists($dirPath));
        self::assertFalse(Directory::delete(''));

        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size($dirTestPath));
        self::assertGreaterThan(Directory::size($dirTestPath, false), Directory::size($dirTestPath));
        self::assertGreaterThan(0, Directory::permission($dirTestPath));
    }

    public function testStaticMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        self::assertTrue(Directory::delete($dirTestPath));
        self::assertFalse(Directory::exists($dirTestPath));

        self::assertTrue(Directory::move(__DIR__ . '/newdirtest', $dirTestPath));
        self::assertFileExists($dirTestPath . '/sub/path/test3.txt');

        self::assertEquals(4, Directory::count($dirTestPath));
        self::assertEquals(1, Directory::count($dirTestPath, false));

        self::assertCount(6, Directory::list($dirTestPath));
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
