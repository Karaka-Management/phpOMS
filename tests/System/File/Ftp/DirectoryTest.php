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

namespace phpOMS\tests\System\File\Ftp;

use phpOMS\System\File\Ftp\Directory;
use phpOMS\Uri\HttpUri;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\File\Ftp\Directory::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\File\Ftp\DirectoryTest: Directory handler for a ftp server')]
final class DirectoryTest extends \PHPUnit\Framework\TestCase
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
        self::assertNotFalse(Directory::ftpConnect(new HttpUri(self::BASE . '/test')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidConnection() : void
    {
        self::assertNull(Directory::ftpConnect(new HttpUri('ftp://karaka.app:21')));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be created')]
    public function testStaticCreate() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create(self::$con, $dirPath));
        self::assertTrue(\is_dir($dirPath));

        \rmdir($dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be checked for existence')]
    public function testStaticExists() : void
    {
        self::assertTrue(Directory::exists(self::$con, __DIR__));
        self::assertFalse(Directory::exists(self::$con, __DIR__ . '/invalid/path/here'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An existing directory cannot be overwritten')]
    public function testInvalidStaticOverwrite() : void
    {
        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create(self::$con, $dirPath));
        self::assertFalse(Directory::create(self::$con, $dirPath));

        \rmdir($dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be forced to be created recursively')]
    public function testStaticSubdir() : void
    {
        $dirPath = __DIR__ . '/test/sub/path';
        self::assertTrue(Directory::create(self::$con, $dirPath, 0755, true));
        self::assertTrue(Directory::exists(self::$con, $dirPath));

        Directory::delete(self::$con, __DIR__ . '/test/sub/path');
        Directory::delete(self::$con, __DIR__ . '/test/sub');
        Directory::delete(self::$con, __DIR__ . '/test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default a directory is not created recursively')]
    public function testInvalidStaticSubdir() : void
    {
        self::assertFalse(Directory::create(self::$con, __DIR__ . '/invalid/path/here'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The name of a directory is just its name without its path')]
    public function testStaticName() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::name($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The basename is the same as the name of the directory')]
    public function testStaticBasename() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::basename($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dirname is the same as the name of the directory')]
    public function testStaticDirname() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals('test', Directory::dirname($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The parent of a directory can be returned')]
    public function testStaticParent() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals(\strtr(\realpath(__DIR__), '\\', '/'), Directory::parent($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The full absolute path of a directory can be returned')]
    public function testStaticDirectoryPath() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertEquals($dirPath, Directory::dirpath($dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories creation date can be returned')]
    public function testStaticCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create(self::$con, $dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), Directory::created(self::$con, $dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The directories last change date can be returned')]
    public function testStaticChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create(self::$con, $dirPath));

        $now = new \DateTime('now');
        $now->setTimestamp(-1);

        self::assertEquals($now->format('Y-m-d'), Directory::changed(self::$con, $dirPath)->format('Y-m-d'));

        Directory::delete(self::$con, $dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be deleted')]
    public function testStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertTrue(Directory::create(self::$con, $dirPath));
        self::assertTrue(Directory::delete(self::$con, $dirPath));
        self::assertFalse(Directory::exists(self::$con, $dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing directory cannot be deleted')]
    public function testInvalidStaticDelete() : void
    {
        $dirPath = __DIR__ . '/test';

        self::assertFalse(Directory::delete(self::$con, $dirPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a directory can be returned')]
    public function testStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size(self::$con, $dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The size of a none-existing directory is negative')]
    public function testInvalidStaticSizeRecursive() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::size(self::$con, $dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The recursive size of a directory is equals or greater than the size of the same directory none-recursive')]
    public function testStaticSize() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(Directory::size(self::$con, $dirTestPath, false), Directory::size(self::$con, $dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a directory can be returned')]
    public function testStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::permission(self::$con, $dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission of a none-existing directory is negative')]
    public function testInvalidStaticPermission() : void
    {
        $dirTestPath = __DIR__ . '/invalid/test/here';
        self::assertEquals(-1, Directory::permission(self::$con, $dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be copied recursively')]
    public function testStaticCopy() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(self::$con, __DIR__ . '/newdirtest');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticCopyOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest', false));
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest', true));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::delete(self::$con, __DIR__ . '/newdirtest');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticInvalidCopyOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::copy(self::$con, $dirTestPath, __DIR__ . '/newdirtest', false));

        Directory::delete(self::$con, __DIR__ . '/newdirtest');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A directory can be moved/renamed to a different path')]
    public function testStaticMove() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFileExists(__DIR__ . '/newdirtest/sub/path/test3.txt');

        Directory::move(self::$con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticInvalidMoveOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertFalse(Directory::move(self::$con, __DIR__ . '/newdirtest', __DIR__ . '/newdirtest', false));

        Directory::move(self::$con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticMoveOverwrite() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';

        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest'));

        self::assertTrue(Directory::copy(self::$con, __DIR__ . '/newdirtest', $dirTestPath));
        self::assertFalse(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest', false));
        self::assertTrue(Directory::move(self::$con, $dirTestPath, __DIR__ . '/newdirtest', true));

        Directory::move(self::$con, __DIR__ . '/newdirtest', $dirTestPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of files in a directory can be returned recursively')]
    public function testStaticCountRecursive() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(4, Directory::count(self::$con, $dirTestPath));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of files in a directory can be returned none-recursively')]
    public function testStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertEquals(1, Directory::count(self::$con, $dirTestPath, false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of files of a none-existing directory is negative')]
    public function testInvalidStaticCount() : void
    {
        $dirTestPath = __DIR__ . '/invalid/path/here';
        self::assertEquals(-1, Directory::count(self::$con, $dirTestPath, false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All files and sub-directories of a directory can be listed')]
    public function testStaticListFiles() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(6, Directory::list(self::$con, $dirTestPath, '*', true));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticListFilesByExtension() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertCount(3, Directory::listByExtension(self::$con, $dirTestPath, 'txt', '', true));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStaticOwner() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(!empty(Directory::owner(self::$con, $dirTestPath)));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirectoryNameSanitizing() : void
    {
        self::assertEquals(':/some/test/[path', Directory::sanitize(':#&^$/some%/test/[path!'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing directory returns a empty list of files and sub-directories')]
    public function testInvalidListPath() : void
    {
        self::assertEquals([], Directory::list(self::$con, __DIR__ . '/invalid.txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidListFilesByExtension() : void
    {
        self::assertEquals([], Directory::listByExtension(self::$con, __DIR__ . '/invalid/path/here', 'txt'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid directory cannot be copied to a new destination')]
    public function testInvalidCopyPath() : void
    {
        self::assertFalse(Directory::copy(self::$con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid directory cannot be moved to a new destination')]
    public function testInvalidMovePath() : void
    {
        self::assertFalse(Directory::move(self::$con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the creation date of a none-existing directory throws a PathException')]
    public function testInvalidCreatedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::created(self::$con, __DIR__ . '/invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the last change date of a none-existing directory throws a PathException')]
    public function testInvalidChangedPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::changed(self::$con, __DIR__ . '/invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Reading the owner of a none-existing directory throws a PathException')]
    public function testInvalidOwnerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        Directory::owner(self::$con, __DIR__ . '/invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testList() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        $dir         = new Directory(new HttpUri(self::BASE . $dirTestPath), true, self::$con);

        self::assertEquals([
            'sub',
            'test.txt',
        ], $dir->getList());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeOutput() : void
    {
        $dirTestPath = __DIR__ . '/dirtest';
        $dir         = new Directory(new HttpUri(self::BASE . $dirTestPath), true, self::$con);

        self::assertInstanceOf(Directory::class, $dir->getNode('sub'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCreate() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');

        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir2'), true, self::$con);
        $dir->createNode();

        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));
        \rmdir(__DIR__ . '/nodedir2');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeDelete() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        self::assertTrue($dir->getNode('nodedir')->deleteNode());

        \clearstatcache();
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCopy() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        $dir->getNode('nodedir')->copyNode(__DIR__ . '/nodedir2');
        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));

        \rmdir(__DIR__ . '/nodedir');
        \rmdir(__DIR__ . '/nodedir2');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeMove() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        $dir->getNode('nodedir')->moveNode(__DIR__ . '/nodedir2');
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
        self::assertTrue(\is_dir(__DIR__ . '/nodedir2'));

        \rmdir(__DIR__ . '/nodedir2');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeExists() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);

        self::assertTrue($dir->isExisting());
        self::assertTrue($dir->isExisting('dirtest'));
        self::assertFalse($dir->isExisting('invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testParentOutput() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        self::assertEquals(__DIR__, $dir->getParent()->getPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeNext() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);
        $dir->next();

        self::assertEquals(__DIR__ . '/dirtest/test.txt', $dir->current()->getPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCurrent() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        self::assertEquals(__DIR__ . '/dirtest/sub', $dir->current()->getPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeKey() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        self::assertEquals('sub', $dir->key());

        $dir->next();
        self::assertEquals('test.txt', $dir->key());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeArrayRead() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        self::assertEquals('test', $dir['test.txt']->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeArraySet() : void
    {
        $dir   = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);
        $dir[] = new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');

        $dir['nodedir'] = new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir'));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        \rmdir(__DIR__ . '/nodedir');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeArrayRemove() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);
        $dir->addNode(new Directory(new HttpUri(self::BASE . __DIR__ . '/nodedir')));

        self::assertTrue(\is_dir(__DIR__ . '/nodedir'));
        unset($dir['nodedir']);

        \clearstatcache();
        self::assertFalse(\is_dir(__DIR__ . '/nodedir'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeArrayExists() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__), true, self::$con);

        self::assertTrue(isset($dir['dirtest']));
        self::assertFalse(isset($dir['invalid']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeCreatedAt() : void
    {
        $dirPath = __DIR__ . '/test';
        $dir     = new Directory(new HttpUri(self::BASE . $dirPath), true, self::$con);

        self::assertTrue($dir->createNode());

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $dir->getCreatedAt()->format('Y-m-d'));

        \rmdir($dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeChangedAt() : void
    {
        $dirPath = __DIR__ . '/test';
        $dir     = new Directory(new HttpUri(self::BASE . $dirPath), true, self::$con);

        self::assertTrue($dir->createNode());

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), $dir->getChangedAt()->format('Y-m-d'));

        \rmdir($dirPath);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeOwner() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        self::assertTrue(!empty($dir->getOwner()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodePermission() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        self::assertGreaterThan(0, $dir->getPermission());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirname() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);
        $dir->next();

        self::assertEquals('dirtest', $dir->current()->getDirname());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testName() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);
        $dir->next();

        self::assertEquals('test', $dir->current()->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testBaseame() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);
        $dir->next();

        self::assertEquals('test.txt', $dir->current()->getBasename());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDirpath() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);
        $dir->next();

        self::assertEquals(__DIR__ . '/dirtest', $dir->current()->getDirPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeValid() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);
        $dir->next();

        self::assertTrue($dir->valid());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testNodeInvalid() : void
    {
        $dir = new Directory(new HttpUri(self::BASE . __DIR__ . '/dirtest'), true, self::$con);

        while ($dir->valid()) {
            $dir->next();
        }

        self::assertFalse($dir->valid());
    }
}
