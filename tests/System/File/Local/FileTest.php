<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\System\File\Local;

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Local\File;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\File\Local\File::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\File\Local\FileTest: File handler for local file system')]
final class FileTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file without content can be created')]
    public function testStaticCreate() : void
    {
        $testFile = __DIR__ . '/path/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        if (\is_file(__DIR__ . '/path')) {
            \rmdir(__DIR__ . '/path');
        }

        self::assertTrue(File::create($testFile));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('', \file_get_contents($testFile));

        \unlink($testFile);
        \rmdir(__DIR__ . '/path');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be created if it already exists')]
    public function testInvalidStaticCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::create($testFile));
        self::assertFalse(File::create($testFile));
        self::assertTrue(\is_file($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file with content can be created')]
    public function testStaticPut() : void
    {
        $testFile = __DIR__ . '/path/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        if (\is_file(__DIR__ . '/path')) {
            \rmdir(__DIR__ . '/path');
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(\is_file($testFile));
        self::assertEquals('test', \file_get_contents($testFile));

        \unlink($testFile);
        \rmdir(__DIR__ . '/path');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be replaced if it doesn't exists")]
    public function testInvalidStaticCreateReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::REPLACE));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be appended if it doesn't exists")]
    public function testInvalidStaticCreateAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::APPEND));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A file cannot be prepended if it doesn't exists")]
    public function testInvalidStaticCreatePrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::PREPEND));
        self::assertfalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be checked for existence')]
    public function testStaticExists() : void
    {
        self::assertTrue(File::exists(__DIR__ . '/FileTest.php'));
        self::assertFalse(File::exists(__DIR__ . '/invalid/file.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be replaced with a new one')]
    public function testStaticReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The set alias works like the replace flag')]
    public function testStaticSetAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::set($testFile, 'test2'));

        self::assertEquals('test2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be appended with additional content')]
    public function testStaticAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::APPEND));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The append alias works like the append flag')]
    public function testStaticAppendAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::append($testFile, 'test2'));

        self::assertEquals('testtest2', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be prepended with additional content')]
    public function testStaticPrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::PREPEND));

        self::assertEquals('test2test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The prepend alias works like the prepend flag')]
    public function testStaticPrependAlias() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::prepend($testFile, 'test2'));

        self::assertEquals('test2test', \file_get_contents($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The content of a file can be read')]
    public function testStaticGet() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertEquals('test', File::get($testFile));

        \unlink($testFile);
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

        self::assertEquals(1, File::count($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories creation date can be returned')]
    public function testStaticCreatedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::created($testFile)->format('Y-m-d'));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories last change date can be returned')]
    public function testStaticChangedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertTrue(File::create($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::changed($testFile)->format('Y-m-d'));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be deleted')]
    public function testStaticDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertTrue(File::create($testFile));
        self::assertTrue(File::delete($testFile));
        self::assertFalse(File::exists($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be deleted')]
    public function testInvalidStaticDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';

        self::assertFalse(File::delete($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a file can be returned')]
    public function testStaticSize() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, File::size($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a file can be returned')]
    public function testStaticPermission() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertGreaterThan(0, File::permission($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a none-existing file is negative')]
    public function testInvalidStaticPermission() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertEquals(-1, File::permission($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be copied to a different location')]
    public function testStaticCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $newPath = __DIR__ . '/sub/path/testing.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(File::copy($testFile, $newPath));
        self::assertTrue(File::exists($newPath));
        self::assertEquals('test', File::get($newPath));

        \unlink($newPath);
        \rmdir(__DIR__ . '/sub/path/');
        \rmdir(__DIR__ . '/sub/');

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be copied to a different location if the destination already exists')]
    public function testInvalidStaticCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';
        if (\is_file($newPath)) {
            \unlink($newPath);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(File::copy($testFile, $newPath));
        self::assertEquals('test2', File::get($newPath));

        \unlink($newPath);
        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be forced to be copied to a different location even if the destination already exists')]
    public function testStaticCopyOverwrite() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';
        if (\is_file($newPath)) {
            \unlink($newPath);
        }

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(File::copy($testFile, $newPath, true));
        self::assertEquals('test', File::get($newPath));

        \unlink($newPath);
        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be moved to a different location')]
    public function testStaticMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $newPath = __DIR__ . '/sub/path/testing.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);

        self::assertTrue(File::move($testFile, $newPath));
        self::assertFalse(File::exists($testFile));
        self::assertTrue(File::exists($newPath));
        self::assertEquals('test', File::get($newPath));

        \unlink($newPath);
        \rmdir(__DIR__ . '/sub/path/');
        \rmdir(__DIR__ . '/sub/');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file cannot be moved to a different location if the destination already exists')]
    public function testInvalidStaticMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertFalse(File::move($testFile, $newPath));
        self::assertTrue(File::exists($testFile));
        self::assertEquals('test2', File::get($newPath));

        \unlink($newPath);
        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be forced to be moved to a different location even if the destination already exists')]
    public function testStaticMoveOverwrite() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $newPath = __DIR__ . '/test2.txt';

        File::put($testFile, 'test', ContentPutMode::CREATE);
        File::put($newPath, 'test2', ContentPutMode::CREATE);

        self::assertTrue(File::move($testFile, $newPath, true));
        self::assertFalse(File::exists($testFile));
        self::assertEquals('test', File::get($newPath));

        \unlink($newPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticOwner() : void
    {
        $dirTestPath = __DIR__ . '/dirtest/test.txt';
        self::assertNotEmpty(File::owner($dirTestPath));
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
        self::assertEquals(-1, File::size(__DIR__ . '/invalid.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be copied')]
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(File::copy(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be moved')]
    public function testInvalidMovePath() : void
    {
        self::assertFalse(File::move(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the content of a none-existing file throws a PathException')]
    public function testInvalidGetPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::get(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the created date of a none-existing file throws a PathException')]
    public function testInvalidCreatedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::created(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the last change date of a none-existing file throws a PathException')]
    public function testInvalidChangedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::changed(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the owner of a none-existing file throws a PathException')]
    public function testInvalidOwnerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        File::owner(__DIR__ . '/invalid.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeInputOutput() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertEquals('test', $file->getContent());

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeReplace() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->setContent('test2'));
        self::assertEquals('test2', $file->getContent());

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeAppend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->appendContent('2'));
        self::assertEquals('test2', $file->getContent());

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodePrepend() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);
        self::assertTrue($file->setContent('test'));
        self::assertTrue($file->prependContent('2'));
        self::assertEquals('2test', $file->getContent());

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeExtension() : void
    {
        $testFile = __DIR__ . '/test.txt';
        $file     = new File($testFile);

        self::assertEquals('txt', $file->getExtension());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCreatedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $file->createdAt->format('Y-m-d'));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeChangedAt() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $file->getChangedAt()->format('Y-m-d'));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeOwner() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertNotEmpty($file->getOwner());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodePermission() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertGreaterThan(0, $file->getPermission());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirname() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals('dirtest', $file->getDirname());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testName() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals('test', $file->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testBaseame() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals('test.txt', $file->getBasename());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirpath() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals(__DIR__ . '/dirtest', $file->getDirPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testParentOutput() : void
    {
        $testFile = __DIR__ . '/dirtest/test.txt';
        $file     = new File($testFile);

        self::assertEquals(__DIR__ . '/dirtest', $file->getDirPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCreate() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue(\is_file($testFile));

        \unlink($testFile);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeDelete() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue(\is_file($testFile));
        self::assertTrue($file->deleteNode());
        self::assertFalse(\is_file($testFile));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCopy() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue($file->copyNode(__DIR__ . '/test2.txt'));
        self::assertTrue(\is_file($testFile));
        self::assertTrue(\is_file(__DIR__ . '/test2.txt'));

        \unlink($testFile);
        \unlink(__DIR__ . '/test2.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeMove() : void
    {
        $testFile = __DIR__ . '/test.txt';
        if (\is_file($testFile)) {
            \unlink($testFile);
        }

        $file = new File($testFile);

        $file->createNode();
        self::assertTrue($file->moveNode(__DIR__ . '/test2.txt'));
        self::assertFalse(\is_file($testFile));
        self::assertTrue(\is_file(__DIR__ . '/test2.txt'));

        \unlink(__DIR__ . '/test2.txt');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeExists() : void
    {
        $file  = new File(__DIR__ . '/dirtest/test.txt');
        $file2 = new File(__DIR__ . '/invalid.txt');

        self::assertTrue($file->isExisting());
        self::assertFalse($file2->isExisting());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeParent() : void
    {
        $file = new File(__DIR__ . '/dirtest/test.txt');

        self::assertEquals('Local', $file->getParent()->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeDirectory() : void
    {
        $file = new File(__DIR__ . '/dirtest/test.txt');

        self::assertEquals('dirtest', $file->getDirectory()->getName());
    }
}
