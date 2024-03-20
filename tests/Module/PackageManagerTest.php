<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\PackageManager;
use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\IO\Zip\Zip;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\PackageManager::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Module\PackageManagerTest: Manager for install/update packages')]
final class PackageManagerTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        if (\is_file(__DIR__ . '/testPackage.zip')) {
            \unlink(__DIR__ . '/testPackage.zip');
        }

        if (\is_file(__DIR__ . '/testPackageExtracted')) {
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/testSubPackage/*'));
            \rmdir(__DIR__ . '/testPackageExtracted/testSubPackage');
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/*'));
        }

        if (\is_file(__DIR__ . '/public.key')) {
            \unlink(__DIR__ . '/public.key');
        }

        // create keys
        $alice_sign_kp = \sodium_crypto_sign_keypair();

        $alice_sign_secretkey = \sodium_crypto_sign_secretkey($alice_sign_kp);
        $alice_sign_publickey = \sodium_crypto_sign_publickey($alice_sign_kp);

        // create signature
        $files = Directory::list(__DIR__ . '/testPackage', '*', true);
        $state = \sodium_crypto_generichash_init();

        foreach ($files as $file) {
            if ($file === 'package.cert' || \is_dir(__DIR__ . '/testPackage' . '/' . $file)) {
                continue;
            }

            $contents = \file_get_contents(__DIR__ . '/testPackage' . '/' . $file);
            if ($contents === false) {
                throw new \Exception();
            }

            \sodium_crypto_generichash_update($state, $contents);
        }

        $hash      = \sodium_crypto_generichash_final($state);
        $signature = \sodium_crypto_sign_detached($hash, $alice_sign_secretkey);

        \file_put_contents(__DIR__ . '/testPackage/package.cert', $signature);
        \file_put_contents(__DIR__ . '/public.key', $alice_sign_publickey);

        // create zip
        Zip::pack(
            __DIR__ . '/testPackage',
            __DIR__ . '/testPackage.zip'
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A package can be installed')]
    public function testPackageValidInstall() : void
    {
        if (\is_dir(__DIR__ . '/dummyModule')) {
            Directory::delete(__DIR__ . '/dummyModule');
        }

        \chmod(__DIR__ . '/testPackage/testSubPackage/run.batch', 0777);
        \chmod(__DIR__ . '/testPackage/testSubPackage/run.sh', 0777);

        Directory::copy(__DIR__ . '/testModulePackage', __DIR__ . '/dummyModule');

        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            __DIR__ . '/dummyModule/',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->extract(__DIR__ . '/testPackageExtracted');

        self::assertTrue($package->isValid());

        $package->load();
        $package->install();

        self::assertGreaterThan(100, \filesize(__DIR__ . '/dummyModule/README.md'));
        self::assertEquals('To copy!', \file_get_contents(__DIR__ . '/dummyModule/Replace.md'));

        self::assertFalse(\is_dir(__DIR__ . '/dummyModule/toMove'));
        self::assertTrue(\is_dir(__DIR__ . '/dummyModule/moveHere'));
        self::assertTrue(\is_file(__DIR__ . '/dummyModule/moveHere/a.md'));
        self::assertTrue(\is_file(__DIR__ . '/dummyModule/moveHere/sub/b.txt'));

        self::assertTrue(\is_file(__DIR__ . '/dummyModule/externalCopy.md'));

        self::assertTrue(\is_dir(__DIR__ . '/dummyModule/toCopy'));
        self::assertTrue(\is_dir(__DIR__ . '/dummyModule/copyHere'));
        self::assertTrue(\is_file(__DIR__ . '/dummyModule/copyHere/a.md'));
        self::assertTrue(\is_file(__DIR__ . '/dummyModule/copyHere/sub/b.txt'));

        self::assertFalse(\is_dir(__DIR__ . '/dummyModule/Remove'));

        \sleep(1);

        /*
        What is this?
        self::assertEquals('php script', \file_get_contents(__DIR__ . '/dummyModule/phpscript.md'));

        if (\is_executable(__DIR__ . '/testPackageExtracted/testSubPackage/run.sh')
            && \is_executable(__DIR__ . '/testPackageExtracted/testSubPackage/run.batch')
        ) {
            self::assertEquals('cmd script', \file_get_contents(__DIR__ . '/dummyModule/cmdscript.md'));
        }
        */

        if (\is_dir(__DIR__ . '/dummyModule')) {
            Directory::delete(__DIR__ . '/dummyModule');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A package which didn't get extracted cannot be loaded and throws a PathException")]
    public function testNotExtractedLoad() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->load();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid package cannot be installed and throws a Exception')]
    public function testInvalidInstall() : void
    {
        $this->expectException(\Exception::class);

        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key') . ' '
        );

        $package->install();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A invalid package key doesn't validate the package")]
    public function testPackageInvalidKey() : void
    {
        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key') . ' '
        );

        $package->extract(__DIR__ . '/testPackageExtracted');

        self::assertFalse($package->isValid());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A invalid package content doesn't validate the package")]
    public function testPackageInvalidContent() : void
    {
        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->extract(__DIR__ . '/testPackageExtracted');
        \file_put_contents(__DIR__ . '/testPackageExtracted/info.json', ' ', \FILE_APPEND);

        self::assertFalse($package->isValid());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The temporarily extracted package can be cleaned up')]
    public function testCleanup() : void
    {
        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->extract(__DIR__ . '/testPackageExtracted');
        $package->cleanup();

        self::assertFileDoesNotExist(__DIR__ . '/testPackage.zip');
        self::assertFileDoesNotExist(__DIR__ . '/testPackageExtracted');
    }

    public static function tearDownAfterClass() : void
    {
        if (\is_file(__DIR__ . '/testPackage.zip')) {
            \unlink(__DIR__ . '/testPackage.zip');
        }

        if (\is_dir(__DIR__ . '/testPackageExtracted')) {
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/testSubPackage/*'));
            \rmdir(__DIR__ . '/testPackageExtracted/testSubPackage');
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/*'));
            \rmdir(__DIR__ . '/testPackageExtracted');
        }

        if (\is_file(__DIR__ . '/public.key')) {
            \unlink(__DIR__ . '/public.key');
        }

        \file_put_contents(__DIR__ . '/testPackage/package.cert', '');
    }
}
