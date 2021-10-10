<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\System\File\Local\Directory;
use phpOMS\Autoloader;

/**
 * Installer abstract class.
 *
 * @package phpOMS\Application
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = '';

    /**
     * Install app.
     *
     * @param DatabasePool      $dbPool     Database instance
     * @param ApplicationInfo   $info       App info
     * @param SettingsInterface $cfgHandler Settings/Configuration handler
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(DatabasePool $dbPool, ApplicationInfo $info, SettingsInterface $cfgHandler) : void
    {
        self::createTables($dbPool, $info);
        self::activate($dbPool, $info);
        self::installTheme(static::PATH . '/..', 'Default');
    }

    /**
     * Install the theme
     *
     * @param string $destination Destination of the application
     * @param string $theme       Theme name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function installTheme(string $destination, string $theme) : void
    {
        if (!\is_dir($path = $destination . '/Themes/' . $theme)) {
            return;
        }

        $dirs = \scandir($path);
        foreach ($dirs as $dir) {
            if (!\is_dir($path. '/' . $dir) || $dir === '.' || $dir === '..') {
                continue;
            }

            if (\is_dir($destination . '/' . $dir)) {
                Directory::delete($destination . '/' . $dir);
            }

            Directory::copy(
                $destination . '/Themes/' . $theme . '/' . $dir,
                $destination . '/' . $dir,
                true
            );
        }
    }

    /**
     * Create tables for app.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected static function createTables(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $path = static::PATH . '/Install/db.json';
        if (!\is_file($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        $definitions = \json_decode($content, true);
        foreach ($definitions as $definition) {
            SchemaBuilder::createFromSchema($definition, $dbPool->get('schema'))->execute();
        }
    }

    /**
     * Activate after install.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected static function activate(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $classPath = \substr(\realpath(static::PATH) . '/Status', \strlen(\realpath(__DIR__ . '/../../')));

        $class = \str_replace('/', '\\', $classPath);

        if (!Autoloader::exists($class)) {
            throw new \UnexpectedValueException($class); // @codeCoverageIgnore
        }

        $class::activate($dbPool, $info);
    }

    /**
     * Re-init app.
     *
     * @param ApplicationInfo $info App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function reInit(ApplicationInfo $info) : void
    {
        $classPath = \substr(\realpath(static::PATH) . '/Status', \strlen(\realpath(__DIR__ . '/../../')));

        $class = \str_replace('/', '\\', $classPath);

        if (!Autoloader::exists($class)) {
            throw new \UnexpectedValueException($class); // @codeCoverageIgnore
        }

        $class::clearRoutes();
        $class::clearHooks();

        $class::activateRoutes($info);
        $class::activateHooks($info);
    }
}
