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

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Local\File;

/**
 * @testdox phpOMS\tests\System\File\Local\FileTest: File handler for local file system
 *
 * @internal
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A file without content can be created
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticCreate() : void
    {
        $testFile = __DIR__ . '/path/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        if (is_file(__DIR__ . '/path')) {
            rmdir(__DIR__ . '/path');
        }

        self::assertTrue(File::create($testFile));
        self::assertTrue(is_file($testFile));
        self::assertEquals('', file_get_contents($testFile));

        unlink($testFile);
        rmdir(__DIR__ . '/path');
    }

    /**
     * @testdox A file cannot be created if it already exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::create($testFile));
        self::assertFalse(File::create($testFile));
        self::assertTrue(is_file($testFile));

        unlink($testFile);
    }

    /**
     * @testdox A file with content can be created
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticPut() : void
    {
        $testFile = __DIR__ . '/path/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        if (is_file(__DIR__ . '/path')) {
            rmdir(__DIR__ . '/path');
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(is_file($testFile));
        self::assertEquals('test', file_get_contents($testFile));

        unlink($testFile);
        rmdir(__DIR__ . '/path');
    }

    /**
     * @testdox A file cannot be replaced if it doesn't exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticCreateReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::REPLACE));
        self::assertfalse(is_file($testFile));
    }

    /**
     * @testdox A file cannot be appended if it doesn't exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticCreateAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::APPEND));
        self::assertfalse(is_file($testFile));
    }

    /**
     * @testdox A file cannot be prepended if it doesn't exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticCreatePrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::PREPEND));
        self::assertfalse(is_file($testFile));
    }

    /**
     * @testdox A file can be checked for existence
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticExists() : void
    {
        self::assertTrue(File::exists(__DIR__ . '/FileTest.php'));
        self::assertFalse(File::exists(__DIR__ . '/invalid/file.txt'));
    }

    /**
     * @testdox A file can be replaced with a new one
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', file_get_contents($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The set alias works like the replace flag
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticSetAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::set($testFile, 'test2'));

        self::assertEquals('test2', file_get_contents($testFile));

        unlink($testFile);
    }

    /**
     * @testdox A file can be appended with additional content
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::APPEND));

        self::assertEquals('testtest2', file_get_contents($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The append alias works like the append flag
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticAppendAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::append($testFile, 'test2'));

        self::assertEquals('testtest2', file_get_contents($testFile));

        unlink($testFile);
    }

    /**
     * @testdox A file can be prepended with additional content
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticPrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::PREPEND));

        self::assertEquals('test2test', file_get_contents($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The prepend alias works like the prepend flag
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticPrependAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::prepend($testFile, 'test2'));

        self::assertEquals('test2test', file_get_contents($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The content of a file can be read
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticGet() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertEquals('test', File::get($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The parent directory of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticParent() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(str_replace('\\', '/', realpath(__DIR__ . '/../')), File::parent($testFile));
    }

    /**
     * @testdox The extension of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticExtension() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('txt', File::extension($testFile));
    }

    /**
     * @testdox The name of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticName() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test', File::name($testFile));
    }

    /**
     * @testdox The basename of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticBaseName() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals('test.txt', File::basename($testFile));
    }

    /**
     * @testdox The file name of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticDirname() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(basename(realpath(__DIR__)), File::dirname($testFile));
    }

    /**
     * @testdox The file path of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticDirectoryPath() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(realpath(__DIR__), File::dirpath($testFile));
    }

    /**
     * @testdox The count of a file is always 1
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticCount() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertEquals(1, File::count($testFile));
    }

    /**
     * @testdox The directories creation date can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticCreatedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::created($testFile)->format('Y-m-d'));

        unlink($testFile);
    }

    /**
     * @testdox The directories last change date can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticChangedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::changed($testFile)->format('Y-m-d'));

        unlink($testFile);
    }

    /**
     * @testdox A file can be deleted
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertTrue(File::create($testFile));
        self::assertTrue(File::delete($testFile));
        self::assertFalse(File::exists($testFile));
    }

    /**
     * @testdox A none-existing file cannot be deleted
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertFalse(File::delete($testFile));
    }

    /**
     * @testdox The size of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticSize() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, File::size($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The permission of a file can be returned
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticPermission() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, File::permission($testFile));

        unlink($testFile);
    }

    /**
     * @testdox The permission of a none-existing file is negative
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticPermission() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertEquals(-1, File::permission($testFile));
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testPathInfo() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertEquals([
                'dirname'   => __DIR__,
                'basename'  => 'test.txt',
                'filename'  => 'test',
                'extension' => 'txt',
            ],
            File::pathInfo($testFile)
        );
    }

    /**
     * @testdox A file can be copied to a different location
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $newPath = __DIR__ . '/sub/path/testing.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(File::copy($testFile, $newPath));
        self::assertTrue(File::exists($newPath));
        self::assertEquals('test', File::get($newPath));

        unlink($newPath);
        rmdir(__DIR__ . '/sub/path/');
        rmdir(__DIR__ . '/sub/');

        unlink($testFile);
    }

    /**
     * @testdox A file cannot be copied to a different location if the destination already exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';
        if (is_file($newPath)) {
            unlink($newPath);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(File::copy($testFile, $newPath));
        self::assertEquals('test2', File::get($newPath));

        unlink($newPath);
        unlink($testFile);
    }

    /**
     * @testdox A file can be forced to be copied to a different location even if the destination already exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticCopyOverwrite() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $newPath  = __DIR__ . '/test2.txt';
        if (is_file($newPath)) {
            unlink($newPath);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(File::copy($testFile, $newPath, true));
        self::assertEquals('test', File::get($newPath));

        unlink($newPath);
        unlink($testFile);
    }

    /**
     * @testdox A file can be moved to a different location
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $newPath = __DIR__ . '/sub/path/testing.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(File::move($testFile, $newPath));
        self::assertFalse(File::exists($testFile));
        self::assertTrue(File::exists($newPath));
        self::assertEquals('test', File::get($newPath));

        unlink($newPath);
        rmdir(__DIR__ . '/sub/path/');
        rmdir(__DIR__ . '/sub/');
    }

    /**
     * @testdox A file cannot be moved to a different location if the destination already exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidStaticMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(File::move($testFile, $newPath));
        self::assertTrue(File::exists($testFile));
        self::assertEquals('test2', File::get($newPath));

        unlink($newPath);
        unlink($testFile);
    }

    /**
     * @testdox A file can be forced to be moved to a different location even if the destination already exists
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticMoveOverwrite() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(File::move($testFile, $newPath, true));
        self::assertFalse(File::exists($testFile));
        self::assertEquals('test', File::get($newPath));

        unlink($newPath);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testStaticOwner() : void
    {
        $dirTestPath = __DIR__ . '/dirtest/test.txt';
        self::assertNotEmpty(File::owner($dirTestPath));
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testFileNameSanitizing() : void
    {
        self::assertEquals('/some/test/[path.txt', File::sanitize(':#&^$/some%/test/[path!.txt'));
    }

    /**
     * @testdox The size of a none-existing file is negative
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidSizePath() : void
    {
        self::assertEquals(-1, File::size(__DIR__ . '/invalid.txt'));
    }

    /**
     * @testdox A none-existing file cannot be copied
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(File::copy(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    /**
     * @testdox A none-existing file cannot be moved
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidMovePath() : void
    {
        self::assertFalse(File::move(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    /**
     * @testdox Reading the content of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidGetPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::get(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Reading the created date of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidCreatedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::created(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Reading the last change date of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidChangedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::changed(__DIR__ . '/invalid.txt');
    }

    /**
     * @testdox Reading the owner of a none-existing file throws a PathException
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testInvalidOwnerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::owner(__DIR__ . '/invalid.txt');
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeInputOutput() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertEquals('test', $file->getContent());

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->setContent('test2'));
        self::assertEquals('test2', $file->getContent());

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->appendContent('2'));
        self::assertEquals('test2', $file->getContent());

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodePrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->prependContent('2'));
        self::assertEquals('2test', $file->getContent());

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeExtension() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $file     = new File($testFile);

        self::assertEquals('txt', $file->getExtension());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeCreatedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $file->createdAt->format('Y-m-d'));

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeChangedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $file->getChangedAt()->format('Y-m-d'));

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeOwner() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertNotEmpty($file->getOwner());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodePermission() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertGreaterThan(0, $file->getPermission());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testDirname() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals('dirtest', $file->getDirname());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testName() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals('test', $file->getName());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testBaseame() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals('test.txt', $file->getBasename());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testDirpath() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals(__DIR__ . '/dirtest', $file->getDirPath());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testParentOutput() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals(__DIR__ . '/dirtest', $file->getDirPath());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue(is_file($testFile));

        unlink($testFile);
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue(is_file($testFile));
        self::assertTrue($file->deleteNode());
        self::assertFalse(is_file($testFile));
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue($file->copyNode(__DIR__ . '/test2.txt'));
        self::assertTrue(is_file($testFile));
        self::assertTrue(is_file(__DIR__ . '/test2.txt'));

        unlink($testFile);
        unlink(__DIR__ . '/test2.txt');
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue($file->moveNode(__DIR__ . '/test2.txt'));
        self::assertFalse(is_file($testFile));
        self::assertTrue(is_file(__DIR__ . '/test2.txt'));

        unlink(__DIR__ . '/test2.txt');
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeExists() : void
    {
        $file  = new File(__DIR__ . '/dirtest/test.txt');
        $file2 = new File(__DIR__ . '/invalid.txt');

        self::assertTrue($file->isExisting());
        self::assertFalse($file2->isExisting());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeParent() : void
    {
        $file = new File(__DIR__ . '/dirtest/test.txt');

        self::assertEquals('Local', $file->getParent()->getName());
    }

    /**
     * @covers phpOMS\System\File\Local\File<extended>
     * @group framework
     */
    public function testNodeDirectory() : void
    {
        $file = new File(__DIR__ . '/dirtest/test.txt');

        self::assertEquals('dirtest', $file->getDirectory()->getName());
    }
}
