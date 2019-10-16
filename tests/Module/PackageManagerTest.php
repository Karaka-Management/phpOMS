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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Module\PackageManager;
use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\IO\Zip\Zip;

/**
 * @internal
 */
class PackageManagerTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        if (\file_exists(__DIR__ . '/testPackage.zip')) {
            \unlink(__DIR__ . '/testPackage.zip');
        }

        if (\file_exists(__DIR__ . '/testPackageExtracted')) {
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/testSubPackage/*'));
            \rmdir(__DIR__ . '/testPackageExtracted/testSubPackage');
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/*'));
        }

        if (\file_exists(__DIR__ . '/public.key')) {
            \unlink(__DIR__ . '/public.key');
        }

        // create keys
        $alice_sign_kp = \sodium_crypto_sign_keypair();

        $alice_sign_secretkey = \sodium_crypto_sign_secretkey($alice_sign_kp);
        $alice_sign_publickey = \sodium_crypto_sign_publickey($alice_sign_kp);

        // create signature
        $files = Directory::list(__DIR__ . '/testPackage');
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
            [
                __DIR__ . '/testPackage',
            ],
            __DIR__ . '/testPackage.zip'
        );
    }

    public function testPackageValidInstall() : void
    {
        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->extract(__DIR__ . '/testPackageExtracted');

        self::assertTrue($package->isValid());

        $package->load();
    }

    public function testNotExtractedLoad() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->load();
    }

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

    public function testCleanup() : void
    {
        $package = new PackageManager(
            __DIR__ . '/testPackage.zip',
            '/invalid',
            \file_get_contents(__DIR__ . '/public.key')
        );

        $package->extract(__DIR__ . '/testPackageExtracted');
        $package->cleanup();

        self::assertFileNotExists(__DIR__ . '/testPackage.zip');
        self::assertFileNotExists(__DIR__ . '/testPackageExtracted');
    }

    public static function tearDownAfterClass() : void
    {
        if (\file_exists(__DIR__ . '/testPackage.zip')) {
            \unlink(__DIR__ . '/testPackage.zip');
        }

        if (\file_exists(__DIR__ . '/testPackageExtracted')) {
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/testSubPackage/*'));
            \rmdir(__DIR__ . '/testPackageExtracted/testSubPackage');
            \array_map('unlink', \glob(__DIR__ . '/testPackageExtracted/*'));
            \rmdir(__DIR__ . '/testPackageExtracted');
        }

        if (\file_exists(__DIR__ . '/public.key')) {
            \unlink(__DIR__ . '/public.key');
        }

        \file_put_contents(__DIR__ . '/testPackage/package.cert', '');
    }
}
