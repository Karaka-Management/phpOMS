<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\System\File;

use phpOMS\System\File\Storage;
use phpOMS\System\File\Local\LocalStorage;
use phpOMS\System\File\Ftp\FtpStorage;

class StorageTest extends \PHPUnit\Framework\TestCase
{
    public function testStorage()
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('local'));
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env());

        self::assertTrue(Storage::register('ftp', '\phpOMS\System\File\Ftp\FtpStorage'));
        self::assertTrue(Storage::register('test', LocalStorage::getInstance()));
        self::assertInstanceOf('\phpOMS\System\File\Ftp\FtpStorage', Storage::env('ftp'));
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('test'));
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidStorage()
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('invalid'));
    }
}
