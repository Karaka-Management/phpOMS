<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\System\File;

use phpOMS\System\File\Local\LocalStorage;
use phpOMS\System\File\Storage;

/**
 * @testdox phpOMS\tests\System\File\StorageTest: Storage handler for the different storage handler types
 *
 * @internal
 */
final class StorageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox By default the local storage handler is returned
     * @covers phpOMS\System\File\Storage
     * @group framework
     */
    public function testStorageDefault() : void
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env());
    }

    /**
     * @testdox The pre-defined storage handlers can be returned by their name
     * @covers phpOMS\System\File\Storage
     * @group framework
     */
    public function testStoragePreDefined() : void
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('local'));
        self::assertInstanceOf('\phpOMS\System\File\Ftp\FtpStorage', Storage::env('ftp'));
    }

    /**
     * @testdox Storages can be registered and returned
     * @covers phpOMS\System\File\Storage
     * @group framework
     */
    public function testInputOutput() : void
    {
        self::assertTrue(Storage::register('ftps', '\phpOMS\System\File\Ftp\FtpStorage'));
        self::assertTrue(Storage::register('test', new LocalStorage()));
        self::assertInstanceOf('\phpOMS\System\File\Ftp\FtpStorage', Storage::env('ftps'));
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('test'));
    }

    /**
     * @testdox Registered storage handlers cannot be overwritten
     * @covers phpOMS\System\File\Storage
     * @group framework
     */
    public function testInvalidRegister() : void
    {
        self::assertTrue(Storage::register('test2', new LocalStorage()));
        self::assertFalse(Storage::register('test2', new LocalStorage()));
    }

    /**
     * @testdox A invalid or none-existing storage throws a Exception
     * @covers phpOMS\System\File\Storage
     * @group framework
     */
    public function testInvalidStorage() : void
    {
        $this->expectException(\Error::class);

        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('invalid'));
    }
}
