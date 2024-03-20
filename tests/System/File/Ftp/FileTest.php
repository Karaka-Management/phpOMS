<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
use phpOMS\Uri\HttpUri;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\File\Ftp\File::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\File\Ftp\FileTest: File handler for a ftp server')]
final class FileTest extends \PHPUnit\Framework\TestCase
{
    public const BASE = 'ftp://test:123456@127.0.0.1:21';

    private static $con = null;

    public static function setUpBeforeClass() : void
    {
        self::$con = File::ftpConnect(new HttpUri(self::BASE));

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
                self::$con = null;

                throw new \Exception();
            }
        } catch (\Throwable $_) {
            self::$con = null;
        }
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
    public function testConnection() : void
    {
        self::assertNotFalse(File::ftpConnect(new HttpUri(self::BASE . '/test')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidConnection() : void
    {
        self::assertNull(File::ftpConnect(new HttpUri('ftp://karaka.app:21')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file without content can be created')]
    public function testStaticCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::create(self::$con, $testFile));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be created if it already exists')]
    public function testInvalidStaticCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::create(self::$con, $testFile));
        self::assertFalse(File::create(self::$con, $testFile));
        self::assertTrue(\is_file($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with content can be created')]
    public function testStaticPut() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('test', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be replaced if it doesn't exists")]
    public function testInvalidStaticCreateReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(File::put(self::$con, $testFile, 'test', ContentPutMode::REPLACE));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be appended if it doesn't exists")]
    public function testInvalidStaticCreateAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(File::put(self::$con, $testFile, 'test', ContentPutMode::APPEND));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be prepended if it doesn't exists")]
    public function testInvalidStaticCreatePrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(File::put(self::$con, $testFile, 'test', ContentPutMode::PREPEND));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be checked for existence')]
    public function testStaticExists() : void
    {
        self::assertTrue(File::exists(self::$con, __DIR__ . '/FileTest.php'));
        self::assertFalse(File::exists(self::$con, __DIR__ . '/invalid/file.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be replaced with a new one')]
    public function testStaticReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put(self::$con, $testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The set alias works like the replace flag')]
    public function testStaticSetAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::set(self::$con, $testFile, 'test2'));

        self::assertEquals('test2', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be appended with additional content')]
    public function testStaticAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put(self::$con, $testFile, 'test2', ContentPutMode::APPEND));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The append alias works like the append flag')]
    public function testStaticAppendAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::append(self::$con, $testFile, 'test2'));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be prepended with additional content')]
    public function testStaticPrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put(self::$con, $testFile, 'test2', ContentPutMode::PREPEND));

        self::assertEquals('test2test', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The prepend alias works like the prepend flag')]
    public function testStaticPrependAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::prepend(self::$con, $testFile, 'test2'));

        self::assertEquals('test2test', \file_get_contents($testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The content of a file can be read')]
    public function testStaticGet() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE));
        self::assertEquals('test', File::get(self::$con, $testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The parent directory of a file can be returned')]
    public function testStaticParent() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\strtr(\realpath(__DIR__ . '/../'), '\\', '/'), File::parent($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The extension of a file can be returned')]
    public function testStaticExtension() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('txt', File::extension($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The name of a file can be returned')]
    public function testStaticName() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test', File::name($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The basename of a file can be returned')]
    public function testStaticBaseName() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test.txt', File::basename($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The file name of a file can be returned')]
    public function testStaticDirname() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\basename(\realpath(__DIR__)), File::dirname($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The file path of a file can be returned')]
    public function testStaticDirectoryPath() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(\realpath(__DIR__), File::dirpath($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The count of a file is always 1')]
    public function testStaticCount() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(1, File::count(self::$con, $testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories creation date can be returned')]
    public function testStaticCreatedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        File::delete(self::$con, $testFile);

        self::assertTrue(File::create(self::$con, $testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::created(self::$con, $testFile)->format('Y-m-d'));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories last change date can be returned')]
    public function testStaticChangedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        File::delete(self::$con, $testFile);

        self::assertTrue(File::create(self::$con, $testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::changed(self::$con, $testFile)->format('Y-m-d'));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be deleted')]
    public function testStaticDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertTrue(File::create(self::$con, $testFile));
        self::assertTrue(File::delete(self::$con, $testFile));
        self::assertFalse(File::exists(self::$con, $testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be deleted')]
    public function testInvalidStaticDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertFalse(File::delete(self::$con, $testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a file can be returned')]
    public function testStaticSize() : void
    {
        $testFile = __DIR__ . '/test.txt';
        File::delete(self::$con, $testFile);

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, File::size(self::$con, $testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a file can be returned')]
    public function testStaticPermission() : void
    {
        $testFile = __DIR__ . '/test.txt';
        File::delete(self::$con, $testFile);

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, File::permission(self::$con, $testFile));

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a none-existing file is negative')]
    public function testInvalidStaticPermission() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertEquals(-1, File::permission(self::$con, $testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be copied to a different location')]
    public function testStaticCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/sub/path/testing.txt';

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(File::copy(self::$con, $testFile, $newPath));
        self::assertTrue(File::exists(self::$con, $newPath));
        self::assertEquals('test', File::get(self::$con, $newPath));

        File::delete(self::$con, $newPath);
        Directory::delete(self::$con, __DIR__ . '/sub/path/');
        Directory::delete(self::$con, __DIR__ . '/sub/');

        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be copied to a different location if the destination already exists')]
    public function testInvalidStaticCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);
        File::put(self::$con, $newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(File::copy(self::$con, $testFile, $newPath));
        self::assertEquals('test2', File::get(self::$con, $newPath));

        File::delete(self::$con, $newPath);
        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be forced to be copied to a different location even if the destination already exists')]
    public function testStaticCopyOverwrite() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);
        File::put(self::$con, $newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(File::copy(self::$con, $testFile, $newPath, true));
        self::assertEquals('test', File::get(self::$con, $newPath));

        File::delete(self::$con, $newPath);
        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be moved to a different location')]
    public function testStaticMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/sub/path/testing.txt';

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(File::move(self::$con, $testFile, $newPath));
        self::assertFalse(File::exists(self::$con, $testFile));
        self::assertTrue(File::exists(self::$con, $newPath));
        self::assertEquals('test', File::get(self::$con, $newPath));

        Directory::delete(self::$con, __DIR__ . '/sub');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be moved to a different location if the destination already exists')]
    public function testInvalidStaticMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);
        File::put(self::$con, $newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(File::move(self::$con, $testFile, $newPath));
        self::assertTrue(File::exists(self::$con, $testFile));
        self::assertEquals('test2', File::get(self::$con, $newPath));

        File::delete(self::$con, $newPath);
        File::delete(self::$con, $testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be forced to be moved to a different location even if the destination already exists')]
    public function testStaticMoveOverwrite() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $newPath  = __DIR__ . '/test2.txt';

        File::put(self::$con, $testFile, 'test', ContentPutMode::CREATE);
        File::put(self::$con, $newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(File::move(self::$con, $testFile, $newPath, true));
        self::assertFalse(File::exists(self::$con, $testFile));
        self::assertEquals('test', File::get(self::$con, $newPath));

        File::delete(self::$con, $newPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticOwner() : void
    {
        $dirTestPath = __DIR__ . '/dirtest/test.txt';
        self::assertTrue(!empty(File::owner(self::$con, $dirTestPath)));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testFileNameSanitizing() : void
    {
        self::assertEquals('/some/test/[path.txt', File::sanitize(':#&^$/some%/test/[path!.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a none-existing file is negative')]
    public function testInvalidSizePath() : void
    {
        self::assertEquals(-1, File::size(self::$con, __DIR__ . '/invalid.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be copied')]
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(File::copy(self::$con, __DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be moved')]
    public function testInvalidMovePath() : void
    {
        self::assertFalse(File::move(self::$con, __DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the content of a none-existing file returns an empty string')]
    public function testInvalidGetPath() : void
    {
        self::assertEquals('', File::get(self::$con, __DIR__ . '/invalid.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the created date of a none-existing file throws a PathException')]
    public function testInvalidCreatedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::created(self::$con, __DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the last change date of a none-existing file throws a PathException')]
    public function testInvalidChangedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::changed(self::$con, __DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the owner of a none-existing file throws a PathException')]
    public function testInvalidOwnerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::owner(self::$con, __DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeInputOutput() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);
        self::assertTrue($file->setContent('test'));
        self::assertEquals('test', $file->getContent());

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->setContent('test2'));
        self::assertEquals('test2', $file->getContent());

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->appendContent('2'));
        self::assertEquals('test2', $file->getContent());

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodePrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->prependContent('2'));
        self::assertEquals('2test', $file->getContent());

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeExtension() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertEquals('txt', $file->getExtension());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCreatedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);

        $file->createNode();

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $file->getCreatedAt()->format('Y-m-d'));

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeChangedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);

        $file->createNode();

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $file->getChangedAt()->format('Y-m-d'));

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeOwner() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertTrue(!empty($file->getOwner()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodePermission() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertGreaterThan(0, $file->getPermission());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirname() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertEquals('dirtest', $file->getDirname());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testName() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertEquals('test', $file->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testBaseame() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertEquals('test.txt', $file->getBasename());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirpath() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertEquals(__DIR__ . '/dirtest', $file->getDirPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testParentOutput() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File(new HttpUri(self::BASE . $testFile), self::$con);

        self::assertEquals(__DIR__ . '/dirtest', $file->getDirPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);

        $file->createNode();
        self::assertTrue(\is_file($testFile));

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);

        $file->createNode();
        self::assertTrue(\is_file($testFile));
        self::assertTrue($file->deleteNode());

        \clearstatcache();
        self::assertFalse(\is_file($testFile));

        if (\is_file($testFile)) {
            \unlink($testFile);
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);

        $file->createNode();
        self::assertTrue($file->copyNode(__DIR__ . '/test2.txt'));
        self::assertTrue(\is_file($testFile));
        self::assertTrue(\is_file(__DIR__ . '/test2.txt'));

        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        if (\is_file(__DIR__ . '/test2.txt')) {
            \unlink(__DIR__ . '/test2.txt');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File(new HttpUri(self::BASE . $testFile), self::$con);

        $file->createNode();
        self::assertTrue($file->moveNode(__DIR__ . '/test2.txt'));
        self::assertFalse(\is_file($testFile));
        self::assertTrue(\is_file(__DIR__ . '/test2.txt'));

        if (\is_file(__DIR__ . '/test2.txt')) {
            \unlink(__DIR__ . '/test2.txt');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeExists() : void
    {
        $file  = new File(new HttpUri(self::BASE . __DIR__ . '/dirtest/test.txt'), self::$con);
        $file2 = new File(new HttpUri(self::BASE . __DIR__ . '/invalid.txt'), self::$con);

        self::assertTrue($file->isExisting());
        self::assertFalse($file2->isExisting());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeParent() : void
    {
        $file = new File(new HttpUri(self::BASE . __DIR__ . '/dirtest/test.txt'), self::$con);

        self::assertEquals('Ftp', $file->getParent()->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeDirectory() : void
    {
        $file = new File(new HttpUri(self::BASE . __DIR__ . '/dirtest/test.txt'), self::$con);

        self::assertEquals('dirtest', $file->getDirectory()->getName());
    }
}
