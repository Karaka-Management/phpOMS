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

namespace phpOMS\tests\System\File;

use phpOMS\System\File\Local\LocalStorage;
use phpOMS\System\File\Storage;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\File\Storage::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\File\StorageTest: Storage handler for the different storage handler types')]
final class StorageTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default the local storage handler is returned')]
    public function testStorageDefault() : void
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The pre-defined storage handlers can be returned by their name')]
    public function testStoragePreDefined() : void
    {
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('local'));
        self::assertInstanceOf('\phpOMS\System\File\Ftp\FtpStorage', Storage::env('ftp'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Storages can be registered and returned')]
    public function testInputOutput() : void
    {
        self::assertTrue(Storage::register('ftps', '\phpOMS\System\File\Ftp\FtpStorage'));
        self::assertTrue(Storage::register('test', new LocalStorage()));
        self::assertInstanceOf('\phpOMS\System\File\Ftp\FtpStorage', Storage::env('ftps'));
        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Registered storage handlers cannot be overwritten')]
    public function testInvalidRegister() : void
    {
        self::assertTrue(Storage::register('test2', new LocalStorage()));
        self::assertFalse(Storage::register('test2', new LocalStorage()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid or none-existing storage throws a Exception')]
    public function testInvalidStorage() : void
    {
        $this->expectException(\Exception::class);

        self::assertInstanceOf('\phpOMS\System\File\Local\LocalStorage', Storage::env('invalid'));
    }
}
