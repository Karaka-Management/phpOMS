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

namespace phpOMS\tests\System\File\Ftp;

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Ftp\Directory;
use phpOMS\System\File\Ftp\File;
use phpOMS\System\File\Ftp\FtpStorage;
use phpOMS\Uri\HttpUri;

/**
 * @testdox phpOMS\tests\System\File\Ftp\FtpStorageTest: Directory & File handler for local file system
 *
 * @internal
 */
class FtpStorageTest extends \PHPUnit\Framework\TestCase
{
    const BASE = 'ftp://test:123456@127.0.0.1:20';

    private static $con = null;

    public static function setUpBeforeClass() : void
    {
        self::$con = Directory::ftpConnect(new HttpUri(self::BASE));
    }

    protected function setUp() : void
    {
        if (self::$con == false) {
            $this->markTestSkipped(
              'The ftp connection is not available.'
            );
        }

        FtpStorage::with(self::$con);
    }

    /**
     * @testdox A directory can be created
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCreateDirectory() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(FtpStorage::create($dirPath));
        self::assertTrue(\is_dir($dirPath));

        Directory::delete(self::$con, $dirPath);
    }

    /**
     * @testdox A directory can be checked for existence
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticExistsDirectory() : void
    {
        self::assertTrue(FtpStorage::exists(__DIR__));
        self::assertFalse(FtpStorage::exists(__DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox An existing directory cannot be overwritten
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticOverwriteDirectory() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(FtpStorage::create($dirPath));
        self::assertFalse(FtpStorage::create($dirPath));

        Directory::delete(self::$con, $dirPath);
    }

    /**
     * @testdox A directory can be forced to be created recursively
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticSubdirDirectory() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(FtpStorage::create($dirPath, 0755, true));
        self::assertTrue(FtpStorage::exists($dirPath));

        Directory::delete(self::$con, __DIR__ . '/test/sub/path');
        Directory::delete(self::$con, __DIR__ . '/test/sub');
        Directory::delete(self::$con, __DIR__ . '/test');
    }

    /**
     * @testdox The name of a directory is just its name without its path
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticNameDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', FtpStorage::name($dirPath));
    }

    /**
     * @testdox The basename is the same as the name of the directory
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticBasenameDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', FtpStorage::basename($dirPath));
    }

    /**
     * @testdox The dirname is the same as the name of the directory
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticDirnameDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', FtpStorage::dirname($dirPath));
    }

    /**
     * @testdox The parent of a directory can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticParentDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__)), FtpStorage::parent($dirPath));
    }

    /**
     * @testdox The full absolute path of a directory can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticDirectoryPathDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, FtpStorage::dirpath($dirPath));
    }

    /**
     * @testdox The directories creation date can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCreatedAtDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(FtpStorage::create($dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), FtpStorage::created($dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    /**
     * @testdox The directories last change date can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticChangedAtDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(FtpStorage::create($dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), FtpStorage::changed($dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    /**
     * @testdox A directory can be deleted
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticDeleteDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(FtpStorage::create($dirPath));
        self::assertTrue(FtpStorage::delete($dirPath));
        self::assertFalse(FtpStorage::exists($dirPath));
    }

    /**
     * @testdox A none-existing directory cannot be deleted
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticDeleteDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertFalse(FtpStorage::delete($dirPath));
    }

    /**
     * @testdox The size of a directory can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticSizeRecursiveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, FtpStorage::size($dirTestPath));
    }

    /**
     * @testdox The size of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticSizeRecursiveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, FtpStorage::size($dirTestPath));
    }

    /**
     * @testdox The recursive size of a directory is equals or greater than the size of the same directory none-recursive
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticSizeDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(FtpStorage::size($dirTestPath, false), FtpStorage::size($dirTestPath));
    }

    /**
     * @testdox The permission of a directory can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticPermissionDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, FtpStorage::permission($dirTestPath));
    }

    /**
     * @testdox The permission of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticPermissionDirectory() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, FtpStorage::permission($dirTestPath));
    }

    /**
     * @testdox A directory can be copied recursively
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCopyDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(FtpStorage::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        FtpStorage::delete(__DIR__ . '/newdirtest');
    }

    /**
     * @testdox A directory can be moved/renamed to a different path
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticMoveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(FtpStorage::move($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        FtpStorage::move(__DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @testdox The amount of files in a directory can be returned recursively
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCountRecursiveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, FtpStorage::count($dirTestPath));
    }

    /**
     * @testdox The amount of files in a directory can be returned none-recursively
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCountDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, FtpStorage::count($dirTestPath, false));
    }

    /**
     * @testdox The amount of files of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticCountDirectory() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(-1, FtpStorage::count($dirTestPath, false));
    }

    /**
     * @testdox All files and sub-directories of a directory can be listed
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticListFilesDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, FtpStorage::list($dirTestPath, '*', true));
    }

    /**
     * @testdox A none-existing directory returns a empty list of files and sub-directories
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidListPathDirectory() : void
    {
        self::assertEquals([], FtpStorage::list(__DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox A invalid directory cannot be copied to a new destination
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidCopyPathDirectory() : void
    {
        self::assertFalse(FtpStorage::copy(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox A invalid directory cannot be moved to a new destination
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidMovePathDirectory() : void
    {
        self::assertFalse(FtpStorage::move(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox Reading the creation date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidCreatedPathDirectory() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::created(__DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the last change date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidChangedPathDirectory() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::changed(__DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the owner of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidOwnerPathDirectory() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::owner(__DIR__ . '/invalid');
    }

    /**
     * @testdox A file without content can be created
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCreateFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox A file cannot be created if it already exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticCreateFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));
        self::assertFalse(FtpStorage::create($testFile));
        self::assertTrue(\is_file($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox A file with content can be created
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticPutFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox A file cannot be replaced if it doesn't exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticCreateReplaceFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(FtpStorage::put($testFile, 'test', ContentPutMode::REPLACE));
        self::assertfalse(\is_file($testFile));
    }

    /**
     * @testdox A file cannot be appended if it doesn't exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticCreateAppendFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(FtpStorage::put($testFile, 'test', ContentPutMode::APPEND));
        self::assertfalse(\is_file($testFile));
    }

    /**
     * @testdox A file cannot be prepended if it doesn't exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticCreatePrependFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(FtpStorage::put($testFile, 'test', ContentPutMode::PREPEND));
        self::assertfalse(\is_file($testFile));
    }

    /**
     * @testdox A file can be checked for existence
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticExistsFile() : void
    {
        self::assertTrue(FtpStorage::exists(__DIR__ . '/FileTest.php'));
        self::assertFalse(FtpStorage::exists(__DIR__ . '/invalid/file.txt'));
    }

    /**
     * @testdox A file can be replaced with a new one
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticReplaceFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::put($testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The set alias works like the replace flag
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticSetAliasFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::set($testFile, 'test2'));

        self::assertEquals('test2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox A file can be appended with additional content
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticAppendFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::put($testFile, 'test2', ContentPutMode::APPEND));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The append alias works like the append flag
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticAppendAliasFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::append($testFile, 'test2'));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox A file can be prepended with additional content
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticPrependFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::put($testFile, 'test2', ContentPutMode::PREPEND));

        self::assertEquals('test2test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The prepend alias works like the prepend flag
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticPrependAliasFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::prepend($testFile, 'test2'));

        self::assertEquals('test2test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The content of a file can be read
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticGetFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertEquals('test', FtpStorage::get($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The parent directory of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticParentFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__ . '/../')), FtpStorage::parent($testFile));
    }

    /**
     * @testdox The extension of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticExtensionFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('txt', FtpStorage::extension($testFile));
    }

    /**
     * @testdox The name of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticNameFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test', FtpStorage::name($testFile));
    }

    /**
     * @testdox The basename of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticBaseNameFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test.txt', FtpStorage::basename($testFile));
    }

    /**
     * @testdox The file name of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticDirnameFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\basename(\realpath(__DIR__)), FtpStorage::dirname($testFile));
    }

    /**
     * @testdox The file path of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticDirectoryPathFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\realpath(__DIR__), FtpStorage::dirpath($testFile));
    }

    /**
     * @testdox The count of a file is always 1
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCountFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(1, FtpStorage::count($testFile));
    }

    /**
     * @testdox The directories creation date can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCreatedAtFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), FtpStorage::created($testFile)->format('Y-m-d'));

        \unlink($testFile);
    }

    /**
     * @testdox The directories last change date can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticChangedAtFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), FtpStorage::changed($testFile)->format('Y-m-d'));

        \unlink($testFile);
    }

    /**
     * @testdox A file can be deleted
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticDeleteFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertTrue(FtpStorage::create($testFile));
        self::assertTrue(FtpStorage::delete($testFile));
        self::assertFalse(FtpStorage::exists($testFile));
    }

    /**
     * @testdox A none-existing file cannot be deleted
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticDeleteFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertFalse(FtpStorage::delete($testFile));
    }

    /**
     * @testdox The size of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticSizeFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, FtpStorage::size($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The permission of a file can be returned
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticPermissionFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, FtpStorage::permission($testFile));

        \unlink($testFile);
    }

    /**
     * @testdox The permission of a none-existing file is negative
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticPermissionFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertEquals(-1, FtpStorage::permission($testFile));
    }

    /**
     * @testdox A file can be copied to a different location
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCopyFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/sub/path/testing.txt';

        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(FtpStorage::copy($testFile, $newPath));
        self::assertTrue(FtpStorage::exists($newPath));
        self::assertEquals('test', FtpStorage::get($newPath));

        File::delete(self::$con, $newPath);
        Directory::delete(self::$con, __DIR__ . '/sub/path/');
        Directory::delete(self::$con, __DIR__ . '/sub/');

        File::delete(self::$con, $testFile);
    }

    /**
     * @testdox A file cannot be copied to a different location if the destination already exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticCopyFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);
        FtpStorage::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(FtpStorage::copy($testFile, $newPath));
        self::assertEquals('test2', FtpStorage::get($newPath));

        File::delete(self::$con, $newPath);
        File::delete(self::$con, $testFile);
    }

    /**
     * @testdox A file can be forced to be copied to a different location even if the destination already exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticCopyOverwriteFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);
        FtpStorage::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(FtpStorage::copy($testFile, $newPath, true));
        self::assertEquals('test', FtpStorage::get($newPath));

        File::delete(self::$con, $newPath);
        File::delete(self::$con, $testFile);
    }

    /**
     * @testdox A file can be moved to a different location
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticMoveFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/sub/path/testing.txt';

        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(FtpStorage::move($testFile, $newPath));
        self::assertFalse(FtpStorage::exists($testFile));
        self::assertTrue(FtpStorage::exists($newPath));
        self::assertEquals('test', FtpStorage::get($newPath));

        File::delete(self::$con, $newPath);
        Directory::delete(self::$con, __DIR__ . '/sub/path/');
        Directory::delete(self::$con, __DIR__ . '/sub/');
    }

    /**
     * @testdox A file cannot be moved to a different location if the destination already exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidStaticMoveFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);
        FtpStorage::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(FtpStorage::move($testFile, $newPath));
        self::assertTrue(FtpStorage::exists($testFile));
        self::assertEquals('test2', FtpStorage::get($newPath));

        File::delete(self::$con, $newPath);
        File::delete(self::$con, $testFile);
    }

    /**
     * @testdox A file can be forced to be moved to a different location even if the destination already exists
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testStaticMoveOverwriteFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);
        FtpStorage::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(FtpStorage::move($testFile, $newPath, true));
        self::assertFalse(FtpStorage::exists($testFile));
        self::assertEquals('test', FtpStorage::get($newPath));

        File::delete(self::$con, $testFile);
        File::delete(self::$con, $newPath);
    }

    /**
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testSanitize() : void
    {
        self::assertEquals(':/some/test/[path', FtpStorage::sanitize(':#&^$/some%/test/[path!'));
    }

    /**
     * @testdox The size of a none-existing file is negative
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidSizePathFile() : void
    {
        self::assertEquals(-1, FtpStorage::size(__DIR__ . '/invalid.txt'));
    }

    /**
     * @testdox A none-existing file cannot be copied
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidCopyPathFile() : void
    {
        self::assertFalse(FtpStorage::copy(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    /**
     * @testdox A none-existing file cannot be moved
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidMovePathFile() : void
    {
        self::assertFalse(FtpStorage::move(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    /**
     * @testdox Reading the content of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidGetPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::get(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Reading the created date of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidCreatedPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::created(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Reading the last change date of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidChangedPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::changed(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Reading the owner of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidOwnerPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::owner(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Writing data to a destination which looks like a directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidPutPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::put(__DIR__, 'Test');
    }

    /**
     * @testdox Reading data from a directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidGetPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::get(__DIR__);
    }

    /**
     * @testdox Trying to run list on a file throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidListPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::list(__DIR__ . '/FtpStorageTest.php');
    }

    /**
     * @testdox Setting data to a destination which looks like a directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidSetPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::set(__DIR__, 'Test');
    }

    /**
     * @testdox Appending data to a destination which looks like a directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidAppendPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::append(__DIR__, 'Test');
    }

    /**
     * @testdox Prepending data to a destination which looks like a directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidPrependPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::prepend(__DIR__, 'Test');
    }

    /**
     * @testdox Reading the extension of a destination which looks like a directory throws a PathException
     * @covers phpOMS\System\File\Ftp\FtpStorage<extended>
     * @group framework
     */
    public function testInvalidExtensionPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::extension(__DIR__);
    }
}
