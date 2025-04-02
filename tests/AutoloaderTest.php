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

namespace phpOMS\tests;

use phpOMS\Autoloader;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Autoloader::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\AutoloaderTest: Class autoloader')]
final class AutoloaderTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Classes can be checked for existence')]
    public function testAutoloader() : void
    {
        self::assertTrue(Autoloader::exists('\phpOMS\Autoloader'));
        self::assertFalse(Autoloader::exists('\Does\Not\Exist'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testLoading() : void
    {
        Autoloader::defaultAutoloader('\phpOMS\tests\TestLoad');

        $includes = \get_included_files();
        self::assertTrue(\in_array(\realpath(__DIR__ . '/TestLoad.php'), $includes));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testManualPathLoading() : void
    {
        Autoloader::addPath(__DIR__ . '/../');
        Autoloader::defaultAutoloader('\tests\TestLoad2');
        Autoloader::defaultAutoloader('\tests\Invalid');

        $includes = \get_included_files();
        self::assertTrue(\in_array(\realpath(__DIR__ . '/TestLoad2.php'), $includes));
    }

    public function testPathFinding() : void
    {
        self::assertCount(1, Autoloader::findPaths('\phpOMS\Autoloader'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testOpcodeCacheInvalidation() : void
    {
        if (!\extension_loaded('zend opcache')
            || \ini_get('opcache.enable') !== '1'
            || \ini_get('opcache.enable_cli') !== '1'
            || \ini_get('opcache.file_cache_only') !== '0'
            || \opcache_get_status() === false
        ) {
            $this->markTestSkipped(
              'The opcache extension is not available.'
            );
        }

        self::assertFalse(\opcache_is_script_cached(__DIR__ . '/TestLoad3.php'));
        Autoloader::defaultAutoloader('\phpOMS\tests\TestLoad3');
        self::assertTrue(Autoloader::invalidate(__DIR__ . '/TestLoad3.php'));
        self::assertTrue(\opcache_is_script_cached(__DIR__ . '/TestLoad3.php'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testUncachedInvalidation() : void
    {
        self::assertFalse(\opcache_is_script_cached(__DIR__ . '/TestLoad4.php'));
        self::assertFalse(Autoloader::invalidate(__DIR__ . '/TestLoad4.php'));
    }
}
