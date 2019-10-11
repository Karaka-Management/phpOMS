<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\System\File;

use phpOMS\System\File\Local\LocalStorage;
use phpOMS\System\File\Storage;

/**
 * @internal
 */
class StorageTest extends \PHPUnit\Framework\TestCase
{
    public function testStorage() : void
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('local'));
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env());

        self::assertTrue(Storage::register('ftp', '\phpOMS\System\File\Ftp\FtpStorage'));
        self::assertTrue(Storage::register('test', LocalStorage::getInstance()));
        self::assertFalse(Storage::register('test', LocalStorage::getInstance()));

        self::assertInstanceOf('\phpOMS\System\File\Ftp\FtpStorage', Storage::env('ftp'));
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('test'));
    }

    public function testInvalidStorage() : void
    {
        self::expectException(\Exception::class);

        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('invalid'));
    }
}
