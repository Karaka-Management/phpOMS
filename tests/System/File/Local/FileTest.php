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
 declare(strict_types=1);

namespace phpOMS\tests\System\File\Local;

use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\Local\File;

/**
 * @internal
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testStatic() : void
    {
        $testFile = __DIR__ . '/test.txt';
        self::assertFalse(File::put($testFile, 'test', ContentPutMode::REPLACE));
        self::assertFalse(File::exists($testFile));
        self::assertTrue(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::exists($testFile));

        self::assertFalse(File::put($testFile, 'test', ContentPutMode::CREATE));
        self::assertTrue(File::put($testFile, 'test2', ContentPutMode::REPLACE));

        self::assertEquals('test2', File::get($testFile));
        self::assertTrue(File::set($testFile, 'test3'));
        self::assertTrue(File::append($testFile, 'test4'));
        self::assertEquals('test3test4', File::get($testFile));
        self::assertTrue(File::prepend($testFile, 'test5'));
        self::assertEquals('test5test3test4', File::get($testFile));

        self::assertEquals(\str_replace('\\', '/', \realpath(\dirname($testFile) . '/../')), File::parent($testFile));
        self::assertEquals('txt', File::extension($testFile));
        self::assertEquals('test', File::name($testFile));
        self::assertEquals('test.txt', File::basename($testFile));
        self::assertEquals(\basename(\realpath(__DIR__)), File::dirname($testFile));
        self::assertEquals(\realpath(__DIR__), File::dirpath($testFile));
        self::assertEquals(1, File::count($testFile));

        $now = new \DateTime('now');
        self::assertEquals($now->format('Y-m-d'), File::created($testFile)->format('Y-m-d'));
        self::assertEquals($now->format('Y-m-d'), File::changed($testFile)->format('Y-m-d'));

        self::assertGreaterThan(0, File::size($testFile));
        self::assertGreaterThan(0, File::permission($testFile));

        $newPath = __DIR__ . '/sub/path/testing.txt';
        self::assertTrue(File::copy($testFile, $newPath));
        self::assertTrue(File::exists($newPath));
        self::assertFalse(File::copy($testFile, $newPath));
        self::assertTrue(File::copy($testFile, $newPath, true));
        self::assertEquals('test5test3test4', File::get($newPath));

        $newPath2 = __DIR__ . '/sub/path/testing2.txt';
        self::assertTrue(File::move($testFile, $newPath2));
        self::assertTrue(File::exists($newPath2));
        self::assertFalse(File::exists($testFile));
        self::assertEquals('test5test3test4', File::get($newPath2));

        self::assertTrue(File::delete($newPath2));
        self::assertFalse(File::exists($newPath2));
        self::assertFalse(File::delete($newPath2));

        \unlink($newPath);
        \rmdir(__DIR__ . '/sub/path/');
        \rmdir(__DIR__ . '/sub/');

        self::assertTrue(File::create($testFile));
        self::assertFalse(File::create($testFile));
        self::assertEquals('', File::get($testFile));

        \unlink($testFile);
    }

    public function testInvalidGetPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        File::get(__DIR__ . '/invalid.txt');
    }

    public function testInvalidCopyPath() : void
    {
        self::assertFalse(File::copy(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    public function testInvalidMovePath() : void
    {
        self::assertFalse(File::move(__DIR__ . '/invalid.txt', __DIR__ . '/invalid2.txt'));
    }

    public function testInvalidCreatedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        File::created(__DIR__ . '/invalid.txt');
    }

    public function testInvalidChangedPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        File::changed(__DIR__ . '/invalid.txt');
    }

    public function testInvalidSizePath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        File::size(__DIR__ . '/invalid.txt');
    }

    public function testInvalidPermissionPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        File::permission(__DIR__ . '/invalid.txt');
    }

    public function testInvalidOwnerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        File::owner(__DIR__ . '/invalid.txt');
    }
}
