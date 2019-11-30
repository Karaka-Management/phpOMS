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

namespace phpOMS\tests\System\File\Ftp;

use phpOMS\System\File\Ftp\Directory;
use phpOMS\Uri\Http;

/**
 * @testdox phpOMS\tests\System\File\Ftp\DirectoryTest: Directory handler for a ftp server
 *
 * @internal
 */
class DirectoryTest extends \PHPUnit\Framework\TestCase
{
    const BASE = 'ftp://test:123456@127.0.0.1:20';

    private $con = null;

    protected function setUp() : void
    {
        if ($this->con === null) {
            $this->con = Directory::ftpConnect(new Http(self::BASE));
        }

        if ($this->con === false) {
            $this->markTestSkipped(
              'The ftp connection is not available.'
            );
        }
    }

    /**
     * @testdox A directory can be created
     * @covers phpOMS\System\File\Local\Directory
     */
    public function testStaticCreate() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($this->con, $dirPath));
        self::assertTrue(\is_dir($dirPath));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be checked for existence
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticExists() : void
    {
        self::assertTrue(Directory::exists($this->con, __DIR__));
        self::assertFalse(Directory::exists($this->con, __DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox An existing directory cannot be overwritten
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidStaticOverwrite() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($this->con, $dirPath));
        self::assertFalse(Directory::create($this->con, $dirPath));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be forced to be created recursively
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticSubdir() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(Directory::create($this->con, $dirPath, 0755, true));
        self::assertTrue(Directory::exists($this->con, $dirPath));

        Directory::delete($this->con, __DIR__ . '/test/sub/path');
        Directory::delete($this->con, __DIR__ . '/test/sub');
        Directory::delete($this->con, __DIR__ . '/test');
    }

    /**
     * @testdox By default a directory is not created recursively
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidStaticSubdir() : void
    {
        self::assertFalse(Directory::create($this->con, __DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox The name of a directory is just its name without its path
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticName() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::name($dirPath));
    }

    /**
     * @testdox The basename is the same as the name of the directory
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticBasename() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::basename($dirPath));
    }

    /**
     * @testdox The dirname is the same as the name of the directory
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticDirname() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::dirname($dirPath));
    }

    /**
     * @testdox The parent of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticParent() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__)), Directory::parent($dirPath));
    }

    /**
     * @testdox The full absolute path of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticDirectoryPath() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, Directory::dirpath($dirPath));
    }

    /**
     * @testdox The directories creation date can be returned
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticCreatedAt() : void
    {
        self::markTestSkipped();
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($this->con, $dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::created($this->con, $dirPath)->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @testdox The directories last change date can be returned
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticChangedAt() : void
    {
        self::markTestSkipped();

        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($this->con, $dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::changed($this->con, $dirPath)->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be deleted
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($this->con, $dirPath));
        self::assertTrue(Directory::delete($this->con, $dirPath));
        self::assertFalse(Directory::exists($this->con, $dirPath));
    }

    /**
     * @testdox A none-existing directory cannot be deleted
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertFalse(Directory::delete($this->con, $dirPath));
    }

    /**
     * @testdox The size of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size($this->con, $dirTestPath));
    }

    /**
     * @testdox The size of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::size($this->con, $dirTestPath));
    }

    /**
     * @testdox The recursive size of a directory is equals or greater than the size of the same directory none-recursive
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticSize() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(Directory::size($this->con, $dirTestPath, false), Directory::size($this->con, $dirTestPath));
    }

    /**
     * @testdox The permission of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::permission($this->con, $dirTestPath));
    }

    /**
     * @testdox The permission of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::permission($this->con, $dirTestPath));
    }

    /**
     * @testdox A directory can be copied recursively
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticCopy() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($this->con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete($this->con, __DIR__ . '/newdirtest');
    }

    /**
     * @testdox A directory can be moved/renamed to a different path
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move($this->con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::move($this->con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @testdox The amount of files in a directory can be returned recursively
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticCountRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, Directory::count($this->con, $dirTestPath));
    }

    /**
     * @testdox The amount of files in a directory can be returned none-recursively
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, Directory::count($this->con, $dirTestPath, false));
    }

    /**
     * @testdox The amount of files of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(-1, Directory::count($this->con, $dirTestPath, false));
    }

    /**
     * @testdox All files and sub-directories of a directory can be listed
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testStaticListFiles() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, Directory::list($this->con, $dirTestPath));
    }

    /**
     * @testdox A none-existing directory returns a empty list of files and sub-directories
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidListPath() : void
    {
        self::assertEquals([], Directory::list($this->con, __DIR__ . '/invalid.txt'));
    }

    /**
     * @testdox A invalid directory cannot be copied to a new destination
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(Directory::copy($this->con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox A invalid directory cannot be moved to a new destination
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidMovePath() : void
    {
        self::assertFalse(Directory::move($this->con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox Reading the creation date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidCreatedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::created($this->con, __DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the last change date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidChangedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::changed($this->con, __DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the owner of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\Directory
     */
    public function testInvalidOwnerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        Directory::owner($this->con, __DIR__ . '/invalid');
    }
}
