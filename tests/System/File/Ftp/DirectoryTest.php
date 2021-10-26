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

use phpOMS\System\File\Ftp\Directory;
use phpOMS\Uri\HttpUri;

/**
 * @testdox phpOMS\tests\System\File\Ftp\DirectoryTest: Directory handler for a ftp server
 *
 * @internal
 */
final class DirectoryTest extends \PHPUnit\Framework\TestCase
{
    public const BASE = 'ftp://test:123456@127.0.0.1:20';

    private static $con = null;

    public static function setUpBeforeClass() : void
    {
        self::$con = Directory::ftpConnect(new HttpUri(self::BASE));

        if (self::$con === false) {
            self::$con = null;

            return;
        }

        try {
            $mkdir = \ftp_mkdir(self::$con, '0xFF');
            \ftp_rmdir(self::$con, '0xFF');

            $f = \fopen('php://memory', 'r+');
            \fwrite($f, '0x00');
            \rewind($f);

            $put = \ftp_fput(self::$con, '0x00', $f);
            \fclose($f);

            \ftp_delete(self::$con, '0x00');

            if (!$mkdir || !$put) {
                throw new \Exception();
            }
        } catch (\Throwable $t) {
            self::$con = null;

            var_dump($t->getMessage());
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

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testConnection() : void
    {
        self::assertNotFalse(Directory::ftpConnect(new HttpUri(self::BASE . '/test')));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidConnection() : void
    {
        self::assertFalse(Directory::ftpConnect(new HttpUri('ftp://orange-management.org:21')));
    }

    /**
     * @testdox A directory can be created
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticCreate() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create(self::$con, $dirPath));
        self::assertTrue(\is_dir($dirPath));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be checked for existence
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticExists() : void
    {
        self::assertTrue(Directory::exists(self::$con, __DIR__));
        self::assertFalse(Directory::exists(self::$con, __DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox An existing directory cannot be overwritten
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticOverwrite() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create(self::$con, $dirPath));
        self::assertFalse(Directory::create(self::$con, $dirPath));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be forced to be created recursively
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticSubdir() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(Directory::create(self::$con, $dirPath, 0755, true));
        self::assertTrue(Directory::exists(self::$con, $dirPath));

        Directory::delete(self::$con, __DIR__ . '/test/sub/path');
        Directory::delete(self::$con, __DIR__ . '/test/sub');
        Directory::delete(self::$con, __DIR__ . '/test');
    }

    /**
     * @testdox By default a directory is not created recursively
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticSubdir() : void
    {
        self::assertFalse(Directory::create(self::$con, __DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox The name of a directory is just its name without its path
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticName() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::name($dirPath));
    }

    /**
     * @testdox The basename is the same as the name of the directory
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticBasename() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::basename($dirPath));
    }

    /**
     * @testdox The dirname is the same as the name of the directory
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticDirname() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::dirname($dirPath));
    }

    /**
     * @testdox The parent of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticParent() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__)), Directory::parent($dirPath));
    }

    /**
     * @testdox The full absolute path of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticDirectoryPath() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, Directory::dirpath($dirPath));
    }

    /**
     * @testdox The directories creation date can be returned
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create(self::$con, $dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), Directory::created(self::$con, $dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    /**
     * @testdox The directories last change date can be returned
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create(self::$con, $dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), Directory::changed(self::$con, $dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    /**
     * @testdox A directory can be deleted
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create(self::$con, $dirPath));
        self::assertTrue(Directory::delete(self::$con, $dirPath));
        self::assertFalse(Directory::exists(self::$con, $dirPath));
    }

    /**
     * @testdox A none-existing directory cannot be deleted
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertFalse(Directory::delete(self::$con, $dirPath));
    }

    /**
     * @testdox The size of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size(self::$con, $dirTestPath));
    }

    /**
     * @testdox The size of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::size(self::$con, $dirTestPath));
    }

    /**
     * @testdox The recursive size of a directory is equals or greater than the size of the same directory none-recursive
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticSize() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(Directory::size(self::$con, $dirTestPath, false), Directory::size(self::$con, $dirTestPath));
    }

    /**
     * @testdox The permission of a directory can be returned
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::permission(self::$con, $dirTestPath));
    }

    /**
     * @testdox The permission of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::permission(self::$con, $dirTestPath));
    }

    /**
     * @testdox A directory can be copied recursively
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticCopy() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(self::$con, __DIR__ . '/newdirtest');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticCopyOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest', false));
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest', true));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(self::$con, __DIR__ . '/newdirtest');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticInvalidCopyOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest', false));

        Directory::delete(self::$con, __DIR__ . '/newdirtest');
    }

    /**
     * @testdox A directory can be moved/renamed to a different path
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::move(self::$con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticInvalidMoveOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::move(self::$con, __DIR__ . '/newdirtest', __DIR__ . '/newdirtest', false));

        Directory::move(self::$con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticMoveOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));

        self::assertTrue(Directory::copy(self::$con, __DIR__ . '/newdirtest', $dirTestPath));
        self::assertFalse(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest', false));
        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest', true));

        Directory::move(self::$con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @testdox The amount of files in a directory can be returned recursively
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticCountRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, Directory::count(self::$con, $dirTestPath));
    }

    /**
     * @testdox The amount of files in a directory can be returned none-recursively
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, Directory::count(self::$con, $dirTestPath, false));
    }

    /**
     * @testdox The amount of files of a none-existing directory is negative
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(-1, Directory::count(self::$con, $dirTestPath, false));
    }

    /**
     * @testdox All files and sub-directories of a directory can be listed
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticListFiles() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, Directory::list(self::$con, $dirTestPath, '*', true));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticListFilesByExtension() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(3, Directory::listByExtension(self::$con, $dirTestPath, 'txt', '', true));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testStaticOwner() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertNotEmpty(Directory::owner(self::$con, $dirTestPath));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testDirectoryNameSanitizing() : void
    {
        self::assertEquals(':/some/test/[path', Directory::sanitize(':#&^$/some%/test/[path!'));
    }

    /**
     * @testdox A none-existing directory returns a empty list of files and sub-directories
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidListPath() : void
    {
        self::assertEquals([], Directory::list(self::$con, __DIR__ . '/invalid.txt'));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidListFilesByExtension() : void
    {
        self::assertEquals([], Directory::listByExtension(self::$con, __DIR__ . '/invalid/path/here', 'txt'));
    }

    /**
     * @testdox A invalid directory cannot be copied to a new destination
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(Directory::copy(self::$con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox A invalid directory cannot be moved to a new destination
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidMovePath() : void
    {
        self::assertFalse(Directory::move(self::$con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox Reading the creation date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidCreatedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::created(self::$con, __DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the last change date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidChangedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::changed(self::$con, __DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the owner of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testInvalidOwnerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::owner(self::$con, __DIR__ . '/invalid');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testList() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        $dir         = new Directory(new HttpUri(self::BASE . $dirTestPath), '*', true, self::$con);

        self::assertEquals([
            'sub',
            'test.txt',
        ], $dir->getList());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeOutput() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        $dir         = new Directory(new HttpUri(self::BASE . $dirTestPath), '*', true, self::$con);

        self::assertInstanceOf(Directory::class, $dir->getNode('sub'));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeCreate() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');

        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir2'), '*', true, self::$con);
        $dir->createNode();

        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));
        \rmdir(__DIR__ . '/nodedir2');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeDelete() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        self::assertTrue($dir->getNode('nodedir')->deleteNode());

        \clearstatcache();
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeCopy() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        $dir->getNode('nodedir')->copyNode(__DIR__ . '/nodedir2');
        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));

        \rmdir(__DIR__ . '/nodedir');
        \rmdir(__DIR__ . '/nodedir2');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeMove() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        $dir->getNode('nodedir')->moveNode(__DIR__ . '/nodedir2');
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));

        \rmdir(__DIR__ . '/nodedir2');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeExists() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);

        self::assertTrue($dir->isExisting());
        self::assertTrue($dir->isExisting('dirtest'));
        self::assertFalse($dir->isExisting('invalid'));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testParentOutput() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);

        self::assertEquals(__DIR__, $dir->getParent()->getPath());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeNext() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir->next();

        self::assertEquals(__DIR__ . '/dirtest/test.txt', $dir->current()->getPath());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeCurrent() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);

        self::assertEquals(__DIR__ . '/dirtest/sub', $dir->current()->getPath());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeKey() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);

        self::assertEquals('sub', $dir->key());

        $dir->next();
        self::assertEquals('test.txt', $dir->key());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeArrayRead() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);

        self::assertEquals('test', $dir['test.txt']->getName());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeArraySet() : void
    {
        $dir   = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);
        $dir[] = new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');

        $dir['nodedir'] = new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeArrayRemove() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        unset($dir['nodedir']);

        \clearstatcache();
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeArrayExists() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), '*', true, self::$con);

        self::assertTrue(isset($dir['dirtest']));
        self::assertFalse(isset($dir['invalid']));
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';
        $dir     = new Directory(new HttpUri(self::BASE . $dirPath), '*', true, self::$con);

        self::assertTrue($dir->createNode());

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $dir->getCreatedAt()->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';
        $dir     = new Directory(new HttpUri(self::BASE . $dirPath), '*', true, self::$con);

        self::assertTrue($dir->createNode());

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $dir->getChangedAt()->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeOwner() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);

        self::assertNotEmpty($dir->getOwner());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodePermission() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);

        self::assertGreaterThan(0, $dir->getPermission());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testDirname() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir->next();

        self::assertEquals('dirtest', $dir->current()->getDirname());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testName() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir->next();

        self::assertEquals('test', $dir->current()->getName());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testBaseame() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir->next();

        self::assertEquals('test.txt', $dir->current()->getBasename());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testDirpath() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir->next();

        self::assertEquals(__DIR__ . '/dirtest', $dir->current()->getDirPath());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeValid() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir->next();

        self::assertTrue($dir->valid());
    }

    /**
     * @covers phpOMS\System\File\Ftp\Directory<extended>
     * @group framework
     */
    public function testNodeInvalid() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), '*', true, self::$con);
        $dir?->next()?->next()?->next()?->next()?->next()?->next()?->next();

        self::assertFalse($dir->valid());
    }
}
