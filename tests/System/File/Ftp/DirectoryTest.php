<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\System\File\Ftp;

use phpOMS\System\File\Ftp\Directory;
use phpOMS\System\File\PathException;
use phpOMS\Uri\Http;

class DirectoryTest extends \PHPUnit\Framework\TestCase
{
    const BASE = 'ftp://test:123456@127.0.0.1:20';

    private $con = null;

    protected function setUp() : void
    {
        if ($this->con === null) {
            $this->con = Directory::ftpConnect(new Http(self::BASE));
        }
    }

    public function testStatic() : void
    {
        self::assertNotFalse($this->con);

        $dirPath = __DIR__ . '/test';
        self::assertTrue(Directory::create($this->con, $dirPath));
        self::assertTrue(Directory::exists($this->con, $dirPath));
        self::assertFalse(Directory::create($this->con, $dirPath));
        self::assertFalse(Directory::create($this->con, __DIR__ . '/test/sub/path'));
        self::assertTrue(Directory::create($this->con, __DIR__ . '/test/sub/path', 0755, true));
        self::assertTrue(Directory::exists($this->con, __DIR__ . '/test/sub/path'));

        self::assertEquals('test', Directory::name($dirPath));
        self::assertEquals('test', Directory::basename($dirPath));
        self::assertEquals('test', Directory::dirname($dirPath));
        self::assertEquals(\str_replace('\\', '/', \realpath($dirPath . '/../')), Directory::parent($dirPath));
        self::assertEquals($dirPath, Directory::dirpath($dirPath));

        $now = new \DateTime('now');
        // todo: implement, doesn't work for ftp yet
        //self::assertEquals($now->format('Y-m-d'), Directory::created($this->con, $dirPath)->format('Y-m-d'));
        //self::assertEquals($now->format('Y-m-d'), Directory::changed($this->con, $dirPath)->format('Y-m-d'));

        self::assertTrue(Directory::delete($this->con, $dirPath));
        self::assertFalse(Directory::exists($this->con, $dirPath));

        $dirTestPath = __DIR__ . '/dirtest';
        self::assertGreaterThan(0, Directory::size($this->con, $dirTestPath));
        self::assertGreaterThan(Directory::size($this->con, $dirTestPath, false), Directory::size($this->con, $dirTestPath));
        self::assertGreaterThan(0, Directory::permission($this->con, $dirTestPath));
    }

    public function testStaticMove() : void
    {
        self::assertNotFalse($this->con);

        $dirTestPath = __DIR__ . '/dirtest';
        self::assertTrue(Directory::copy($this->con, $dirTestPath, __DIR__ . '/newdirtest'));
        self::assertTrue(\file_exists(__DIR__ . '/newdirtest/sub/path/test3.txt'));

        self::assertTrue(Directory::delete($this->con, $dirTestPath));
        self::assertFalse(Directory::exists($this->con, $dirTestPath));

        self::assertTrue(Directory::move($this->con, __DIR__ . '/newdirtest', $dirTestPath));
        self::assertTrue(\file_exists($dirTestPath . '/sub/path/test3.txt'));

        self::assertEquals(4, Directory::count($this->con, $dirTestPath));
        self::assertEquals(1, Directory::count($this->con, $dirTestPath, false));

        self::assertEquals(6, \count(Directory::list($this->con, $dirTestPath)));
    }

    public function testInvalidListPath() : void
    {
        self::assertNotFalse($this->con);
        self::assertEquals([], Directory::list($this->con, __DIR__ . '/invalid.txt'));
    }

    public function testInvalidCopyPath() : void
    {
        self::assertNotFalse($this->con);
        self::assertFalse(Directory::copy($this->con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    public function testInvalidMovePath() : void
    {
        self::assertNotFalse($this->con);
        self::assertFalse(Directory::move($this->con, __DIR__ . '/invalid', __DIR__ . '/invalid2'));
    }

    public function testInvalidCreatedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        Directory::created($this->con, __DIR__ . '/invalid');
    }

    public function testInvalidChangedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        Directory::changed($this->con, __DIR__ . '/invalid');
    }

    public function testInvalidSizePath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        Directory::size($this->con, __DIR__ . '/invalid');
    }

    public function testInvalidPermissionPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        Directory::permission($this->con, __DIR__ . '/invalid');
    }

    public function testInvalidOwnerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        self::assertNotFalse($this->con);

        Directory::owner($this->con, __DIR__ . '/invalid');
    }
}
