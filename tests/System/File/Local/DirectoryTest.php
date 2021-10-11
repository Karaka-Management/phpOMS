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

namespace phpOMS\tests\System\File\Local;

use phpOMS\System\File\Local\Directory;

/**
 * @testdox phpOMS\tests\System\File\Local\DirectoryTest: Directory handler for local file system
 *
 * @internal
 */
class DirectoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A directory can be created
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticCreate() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($dirPath));
        self::assertTrue(\is_dir($dirPath));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be checked for existence
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticExists() : void
    {
        self::assertTrue(Directory::exists(__DIR__));
        self::assertFalse(Directory::exists(__DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox An existing directory cannot be overwritten
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticOverwrite() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($dirPath));
        self::assertFalse(Directory::create($dirPath));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be forced to be created recursively
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticSubdir() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(Directory::create($dirPath, 0755, true));
        self::assertTrue(Directory::exists($dirPath));

        \rmdir(__DIR__ . '/test/sub/path');
        \rmdir(__DIR__ . '/test/sub');
        \rmdir(__DIR__ . '/test');
    }

    /**
     * @testdox By default a directory is not created recursively
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticSubdir() : void
    {
        self::assertFalse(Directory::create(__DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox The name of a directory is just its name without its path
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticName() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::name($dirPath));
    }

    /**
     * @testdox The basename is the same as the name of the directory
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticBasename() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::basename($dirPath));
    }

    /**
     * @testdox The dirname is the same as the name of the directory
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticDirname() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::dirname($dirPath));
    }

    /**
     * @testdox The parent of a directory can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticParent() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\str_replace('\\', '/', \realpath(__DIR__)), Directory::parent($dirPath));
    }

    /**
     * @testdox The full absolute path of a directory can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticDirectoryPath() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, Directory::dirpath($dirPath));
    }

    /**
     * @testdox The directories creation date can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::created($dirPath)->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @testdox The directories last change date can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($dirPath));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), Directory::changed($dirPath)->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @testdox A directory can be deleted
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create($dirPath));
        self::assertTrue(Directory::delete($dirPath));
        self::assertFalse(Directory::exists($dirPath));
    }

    /**
     * @testdox A none-existing directory cannot be deleted
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertFalse(Directory::delete($dirPath));
    }

    /**
     * @testdox The size of a directory can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size($dirTestPath));
    }

    /**
     * @testdox The size of a none-existing directory is negative
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::size($dirTestPath));
    }

    /**
     * @testdox The recursive size of a directory is equals or greater than the size of the same directory none-recursive
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticSize() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(Directory::size($dirTestPath, false), Directory::size($dirTestPath));
    }

    /**
     * @testdox The permission of a directory can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::permission($dirTestPath));
    }

    /**
     * @testdox The permission of a none-existing directory is negative
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::permission($dirTestPath));
    }

    /**
     * @testdox A directory can be copied recursively
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticCopy() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(__DIR__ . '/newdirtest');
    }

    /**
     * @testdox A directory can be forced to be copied to a different location even if the destination already exists
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticCopyOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::copy($dirTestPath, __DIR__ . '/newdirtest', false));
        self::assertTrue(Directory::copy($dirTestPath, __DIR__ . '/newdirtest', true));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(__DIR__ . '/newdirtest');
    }

    /**
     * @testdox By default a directory is not overwritten on copy
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticInvalidCopyOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::copy($dirTestPath, __DIR__ . '/newdirtest', false));

        Directory::delete(__DIR__ . '/newdirtest');
    }

    /**
     * @testdox A directory can be moved/renamed to a different path
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move($dirTestPath, __DIR__ . '/parent/newdirtest'));
        self::assertFileExists(__DIR__ . '/parent/newdirtest/sub/path/test3.txt');

        Directory::move(__DIR__ . '/parent/newdirtest', $dirTestPath);
        \rmdir(__DIR__ . '/parent');
    }

    /**
     * @testdox By default a directory is not overwritten on move
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticInvalidMoveOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move($dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::move(__DIR__ . '/newdirtest', __DIR__ . '/newdirtest', false));

        Directory::move(__DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @testdox A directory can be forced to be moved/renamed to a different path even if the destination already exists
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticMoveOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move($dirTestPath, __DIR__ . '/newdirtest'));

        self::assertTrue(Directory::copy(__DIR__ . '/newdirtest', $dirTestPath));
        self::assertFalse(Directory::move($dirTestPath, __DIR__ . '/newdirtest', false));
        self::assertTrue(Directory::move($dirTestPath, __DIR__ . '/newdirtest', true));

        Directory::move(__DIR__ . '/newdirtest', $dirTestPath);
    }

    /**
     * @testdox The amount of files in a directory can be returned recursively
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticCountRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, Directory::count($dirTestPath));
    }

    /**
     * @testdox The amount of files in a directory can be returned none-recursively
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, Directory::count($dirTestPath, false));
    }

    /**
     * @testdox The amount of files of a none-existing directory is negative
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(-1, Directory::count($dirTestPath, false));
    }

    /**
     * @testdox All files and sub-directories of a directory can be listed
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticListFiles() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, Directory::list($dirTestPath, '*', true));
        self::assertEquals([], \array_diff(['sub/test2.txt', 'sub/test4.md', 'sub/path/test3.txt'], Directory::list($dirTestPath, 'test[0-9]+.*', true)));

        self::assertCount(2, Directory::list($dirTestPath, '*', false));
    }

    /**
     * @testdox All files of a directory can be listed by file extension
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticListFilesByExtension() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(3, Directory::listByExtension($dirTestPath, 'txt'));
    }

    /**
     * @testdox The owner of a directory can be returned
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testStaticOwner() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertNotEmpty(Directory::owner($dirTestPath));
    }

    /**
     * @testdox Invalid directory names and paths can be sanitized
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testDirectoryNameSanitizing() : void
    {
        self::assertEquals(':/some/test/[path', Directory::sanitize(':#&^$/some%/test/[path!'));
    }

    /**
     * @testdox A none-existing directory returns a empty list of files and sub-directories
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidListPath() : void
    {
        self::assertEquals([], Directory::list(__DIR__ . '/invalid/path/here'));
    }

    /**
     * @testdox A none-existing directory returns a empty list of files for the extension
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidListFilesByExtension() : void
    {
        self::assertEquals([], Directory::listByExtension(__DIR__ . '/invalid/path/here', 'txt'));
    }

    /**
     * @testdox A invalid directory cannot be copied to a new destination
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(Directory::copy(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox A invalid directory cannot be moved to a new destination
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidMovePath() : void
    {
        self::assertFalse(Directory::move(__DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    /**
     * @testdox Reading the creation date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidCreatedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::created(__DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the last change date of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidChangedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::changed(__DIR__ . '/invalid');
    }

    /**
     * @testdox Reading the owner of a none-existing directory throws a PathException
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testInvalidOwnerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::owner(__DIR__ . '/invalid');
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testList() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        $dir         = new Directory($dirTestPath);

        self::assertEquals([
            'sub',
            'test.txt',
        ], $dir->getList());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeOutput() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        $dir         = new Directory($dirTestPath);

        self::assertInstanceOf(Directory::class, $dir->getNode('sub'));
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeCreate() : void
    {
        $dir = new Directory(__DIR__);
        $dir->addNode(new Directory(__DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');

        $dir = new Directory(__DIR__ . '/nodedir2');
        $dir->createNode();

        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));
        \rmdir(__DIR__ . '/nodedir2');
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeDelete() : void
    {
        $dir = new Directory(__DIR__);
        $dir->addNode(new Directory(__DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        self::assertTrue($dir->getNode('nodedir')->deleteNode());
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeCopy() : void
    {
        $dir = new Directory(__DIR__);
        $dir->addNode(new Directory(__DIR__ . '/nodedir'));

        $dir->getNode('nodedir')->copyNode(__DIR__ . '/nodedir2');
        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));

        \rmdir(__DIR__ . '/nodedir');
        \rmdir(__DIR__ . '/nodedir2');
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeMove() : void
    {
        $dir = new Directory(__DIR__);
        $dir->addNode(new Directory(__DIR__ . '/nodedir'));

        $dir->getNode('nodedir')->moveNode(__DIR__ . '/nodedir2');
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));

        \rmdir(__DIR__ . '/nodedir2');
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeExists() : void
    {
        $dir = new Directory(__DIR__);

        self::assertTrue($dir->isExisting());
        self::assertTrue($dir->isExisting('dirtest'));
        self::assertFalse($dir->isExisting('invalid'));
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testParentOutput() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');

        self::assertEquals(__DIR__, $dir->getParent()->getPath());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeNext() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');
        $dir->next();

        self::assertEquals(__DIR__ . '/dirtest/test.txt', $dir->current()->getPath());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeCurrent() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');

        self::assertEquals(__DIR__ . '/dirtest/sub', $dir->current()->getPath());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeKey() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');

        self::assertEquals('sub', $dir->key());

        $dir->next();
        self::assertEquals('test.txt', $dir->key());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeArrayRead() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');

        self::assertEquals('test', $dir['test.txt']->getName());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeArraySet() : void
    {
        $dir   = new Directory(__DIR__);
        $dir[] = new Directory(__DIR__ . '/nodedir');

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');

        $dir['nodedir'] = new Directory(__DIR__ . '/nodedir');

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeArrayRemove() : void
    {
        $dir = new Directory(__DIR__);
        $dir->addNode(new Directory(__DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        unset($dir['nodedir']);
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeArrayExists() : void
    {
        $dir = new Directory(__DIR__);

        self::assertTrue(isset($dir['dirtest']));
        self::assertFalse(isset($dir['invalid']));
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';
        $dir     = new Directory($dirPath);

        self::assertTrue($dir->createNode());

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $dir->getCreatedAt()->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';
        $dir     = new Directory($dirPath);

        self::assertTrue($dir->createNode());

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $dir->getChangedAt()->format('Y-m-d'));

        \rmdir($dirPath);
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodeOwner() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');

        self::assertNotEmpty($dir->getOwner());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testNodePermission() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');

        self::assertGreaterThan(0, $dir->getPermission());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testDirname() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');
        $dir->next();

        self::assertEquals('dirtest', $dir->current()->getDirName());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testName() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');
        $dir->next();

        self::assertEquals('test', $dir->current()->getName());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testBaseame() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');
        $dir->next();

        self::assertEquals('test.txt', $dir->current()->getBasename());
    }

    /**
     * @covers phpOMS\System\File\Local\Directory<extended>
     * @group framework
     */
    public function testDirpath() : void
    {
        $dir = new Directory(__DIR__ . '/dirtest');
        $dir->next();

        self::assertEquals(__DIR__ . '/dirtest', $dir->current()->getDirPath());
    }
}
