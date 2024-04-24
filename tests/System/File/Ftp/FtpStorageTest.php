<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\System\File\Ftp;

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Ftp\Directory;
use phpOMS\System\File\Ftp\File;
use phpOMS\System\File\Ftp\FtpStorage;
use phpOMS\Uri\HttpUri;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\File\Ftp\FtpStorage::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\File\Ftp\FtpStorageTest: Directory & File handler for local file system')]
final class FtpStorageTest extends \PHPUnit\Framework\TestCase
{
    public const BASE = 'ftp://test:123456@127.0.0.1:21';

    private static $con = null;

    public static function setUpBeforeClass() : void
    {
        self::$con = Directory::ftpConnect(new HttpUri(self::BASE));

        if (self::$con === false) {
            self::$con = null;

            return;
        }

        try {
            $mkdir = \ftp_mkdir(self::$con, __DIR__ . '/0xFF');
            \ftp_rmdir(self::$con, __DIR__ . '/0xFF');

            $f = \fopen('php://memory', 'r+');
            \fwrite($f, __DIR__ . '/0x00');
            \rewind($f);

            $put = \ftp_fput(self::$con, __DIR__ . '/0x00', $f);
            \fclose($f);

            \ftp_delete(self::$con, __DIR__ . '/0x00');

            if (!$mkdir || !$put) {
                throw new \Exception();
            }
        } catch (\Throwable $_) {
            self::$con = null;
        }

        if (self::$con === null) {
            return;
        }

        FtpStorage::with(self::$con);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (self::$con === null) {
            $this->markTestSkipped(
              'The ftp connection is not available.'
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be created')]
    public function testStaticCreateDirectory() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(FtpStorage::create($dirPath));
        self::assertTrue(\is_dir($dirPath));

        Directory::delete(self::$con, $dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be checked for existence')]
    public function testStaticExistsDirectory() : void
    {
        self::assertTrue(FtpStorage::exists(__DIR__));
        self::assertFalse(FtpStorage::exists(__DIR__ . '/invalid/path/here'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An existing directory cannot be overwritten')]
    public function testInvalidStaticOverwriteDirectory() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(FtpStorage::create($dirPath));
        self::assertFalse(FtpStorage::create($dirPath));

        Directory::delete(self::$con, $dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be forced to be created recursively')]
    public function testStaticSubdirDirectory() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(FtpStorage::create($dirPath));
        self::assertTrue(FtpStorage::exists($dirPath));

        Directory::delete(self::$con, __DIR__ . '/test/sub/path');
        Directory::delete(self::$con, __DIR__ . '/test/sub');
        Directory::delete(self::$con, __DIR__ . '/test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The name of a directory is just its name without its path')]
    public function testStaticNameDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', FtpStorage::name($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The basename is the same as the name of the directory')]
    public function testStaticBasenameDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', FtpStorage::basename($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dirname is the same as the name of the directory')]
    public function testStaticDirnameDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', FtpStorage::dirname($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The parent of a directory can be returned')]
    public function testStaticParentDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\strtr(\realpath(__DIR__), '\\', '/'), FtpStorage::parent($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The full absolute path of a directory can be returned')]
    public function testStaticDirectoryPathDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, FtpStorage::dirpath($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories creation date can be returned')]
    public function testStaticCreatedAtDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(FtpStorage::create($dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), FtpStorage::created($dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories last change date can be returned')]
    public function testStaticChangedAtDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(FtpStorage::create($dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), FtpStorage::changed($dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be deleted')]
    public function testStaticDeleteDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(FtpStorage::create($dirPath));
        self::assertTrue(FtpStorage::delete($dirPath));
        self::assertFalse(FtpStorage::exists($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing directory cannot be deleted')]
    public function testInvalidStaticDeleteDirectory() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertFalse(FtpStorage::delete($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a directory can be returned')]
    public function testStaticSizeRecursiveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, FtpStorage::size($dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a none-existing directory is negative')]
    public function testInvalidStaticSizeRecursiveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, FtpStorage::size($dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The recursive size of a directory is equals or greater than the size of the same directory none-recursive')]
    public function testStaticSizeDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(FtpStorage::size($dirTestPath, false), FtpStorage::size($dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a directory can be returned')]
    public function testStaticPermissionDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, FtpStorage::permission($dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a none-existing directory is negative')]
    public function testInvalidStaticPermissionDirectory() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, FtpStorage::permission($dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be copied recursively')]
    public function testStaticCopyDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(FtpStorage::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        FtpStorage::delete(__DIR__ . '/newdirtest');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be moved/renamed to a different path')]
    public function testStaticMoveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(FtpStorage::move($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        FtpStorage::move(__DIR__ . '/newdirtest', $dirTestPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of files in a directory can be returned recursively')]
    public function testStaticCountRecursiveDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, FtpStorage::count($dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of files in a directory can be returned none-recursively')]
    public function testStaticCountDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, FtpStorage::count($dirTestPath, false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of files of a none-existing directory is negative')]
    public function testInvalidStaticCountDirectory() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(-1, FtpStorage::count($dirTestPath, false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All files and sub-directories of a directory can be listed')]
    public function testStaticListFilesDirectory() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, FtpStorage::list($dirTestPath, '*', true));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing directory returns a empty list of files and sub-directories')]
    public function testInvalidListPathDirectory() : void
    {
        self::assertEquals([], FtpStorage::list(__DIR__ . '/invalid/path/here'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid directory cannot be copied to a new destination')]
    public function testInvalidCopyPathDirectory() : void
    {
        self::assertFalse(FtpStorage::copy(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid directory cannot be moved to a new destination')]
    public function testInvalidMovePathDirectory() : void
    {
        self::assertFalse(FtpStorage::move(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the creation date of a none-existing directory throws a PathException')]
    public function testInvalidCreatedPathDirectory() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::created(__DIR__ . '/invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the last change date of a none-existing directory throws a PathException')]
    public function testInvalidChangedPathDirectory() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::changed(__DIR__ . '/invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the owner of a none-existing directory throws a PathException')]
    public function testInvalidOwnerPathDirectory() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::owner(__DIR__ . '/invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file without content can be created')]
    public function testStaticCreateFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be created if it already exists')]
    public function testInvalidStaticCreateFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));
        self::assertFalse(FtpStorage::create($testFile));
        self::assertTrue(\is_file($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with content can be created')]
    public function testStaticPutFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be replaced if it doesn't exists")]
    public function testInvalidStaticCreateReplaceFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(FtpStorage::put($testFile, 'test', ContentPutMode::REPLACE));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be appended if it doesn't exists")]
    public function testInvalidStaticCreateAppendFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(FtpStorage::put($testFile, 'test', ContentPutMode::APPEND));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be prepended if it doesn't exists")]
    public function testInvalidStaticCreatePrependFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(FtpStorage::put($testFile, 'test', ContentPutMode::PREPEND));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be checked for existence')]
    public function testStaticExistsFile() : void
    {
        self::assertTrue(FtpStorage::exists(__DIR__ . '/FileTest.php'));
        self::assertFalse(FtpStorage::exists(__DIR__ . '/invalid/file.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be replaced with a new one')]
    public function testStaticReplaceFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::put($testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The set alias works like the replace flag')]
    public function testStaticSetAliasFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::set($testFile, 'test2'));

        self::assertEquals('test2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be appended with additional content')]
    public function testStaticAppendFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::put($testFile, 'test2', ContentPutMode::APPEND));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The append alias works like the append flag')]
    public function testStaticAppendAliasFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::append($testFile, 'test2'));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be prepended with additional content')]
    public function testStaticPrependFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::put($testFile, 'test2', ContentPutMode::PREPEND));

        self::assertEquals('test2test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The prepend alias works like the prepend flag')]
    public function testStaticPrependAliasFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(FtpStorage::prepend($testFile, 'test2'));

        self::assertEquals('test2test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The content of a file can be read')]
    public function testStaticGetFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertEquals('test', FtpStorage::get($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The parent directory of a file can be returned')]
    public function testStaticParentFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\strtr(\realpath(__DIR__ . '/../'), '\\', '/'), FtpStorage::parent($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The extension of a file can be returned')]
    public function testStaticExtensionFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('txt', FtpStorage::extension($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The name of a file can be returned')]
    public function testStaticNameFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test', FtpStorage::name($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The basename of a file can be returned')]
    public function testStaticBaseNameFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test.txt', FtpStorage::basename($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The file name of a file can be returned')]
    public function testStaticDirnameFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\basename(\realpath(__DIR__)), FtpStorage::dirname($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The file path of a file can be returned')]
    public function testStaticDirectoryPathFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\realpath(__DIR__), FtpStorage::dirpath($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The count of a file is always 1')]
    public function testStaticCountFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(1, FtpStorage::count($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories creation date can be returned')]
    public function testStaticCreatedAtFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), FtpStorage::created($testFile)->format('Y-m-d'));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories last change date can be returned')]
    public function testStaticChangedAtFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(FtpStorage::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), FtpStorage::changed($testFile)->format('Y-m-d'));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be deleted')]
    public function testStaticDeleteFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertTrue(FtpStorage::create($testFile));
        self::assertTrue(FtpStorage::delete($testFile));
        self::assertFalse(FtpStorage::exists($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be deleted')]
    public function testInvalidStaticDeleteFile() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertFalse(FtpStorage::delete($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a file can be returned')]
    public function testStaticSizeFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, FtpStorage::size($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a file can be returned')]
    public function testStaticPermissionFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        FtpStorage::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, FtpStorage::permission($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a none-existing file is negative')]
    public function testInvalidStaticPermissionFile() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertEquals(-1, FtpStorage::permission($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be copied to a different location')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be copied to a different location if the destination already exists')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be forced to be copied to a different location even if the destination already exists')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be moved to a different location')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be moved to a different location if the destination already exists')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be forced to be moved to a different location even if the destination already exists')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSanitize() : void
    {
        self::assertEquals(':/some/test/[path', FtpStorage::sanitize(':#&^$/some%/test/[path!'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a none-existing file is negative')]
    public function testInvalidSizePathFile() : void
    {
        self::assertEquals(-1, FtpStorage::size(__DIR__ . '/invalid.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be copied')]
    public function testInvalidCopyPathFile() : void
    {
        self::assertFalse(FtpStorage::copy(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be moved')]
    public function testInvalidMovePathFile() : void
    {
        self::assertFalse(FtpStorage::move(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the content of a none-existing file returns an empty result')]
    public function testInvalidGetPathFile() : void
    {
        self::assertEquals('', FtpStorage::get(__DIR__ . '/invalid.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the created date of a none-existing file throws a PathException')]
    public function testInvalidCreatedPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::created(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the last change date of a none-existing file throws a PathException')]
    public function testInvalidChangedPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::changed(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the owner of a none-existing file throws a PathException')]
    public function testInvalidOwnerPathFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::owner(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Writing data to a destination which looks like a directory throws a PathException')]
    public function testInvalidPutPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::put(__DIR__, 'Test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading data from a directory throws a PathException')]
    public function testInvalidGetPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::get(__DIR__);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Trying to run list on a file throws a PathException')]
    public function testInvalidListPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::list(__DIR__ . '/FtpStorageTest.php');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Setting data to a destination which looks like a directory throws a PathException')]
    public function testInvalidSetPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::set(__DIR__, 'Test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Appending data to a destination which looks like a directory throws a PathException')]
    public function testInvalidAppendPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::append(__DIR__, 'Test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Prepending data to a destination which looks like a directory throws a PathException')]
    public function testInvalidPrependPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::prepend(__DIR__, 'Test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the extension of a destination which looks like a directory throws a PathException')]
    public function testInvalidExtensionPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        FtpStorage::extension(__DIR__);
    }
}
