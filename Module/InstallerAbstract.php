<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Application\ApplicationInfo;
use phpOMS\Autoloader;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Installer abstract class.
 *
 * @package phpOMS\Module
 * @license OMS License 2.0
 * @link    https://jingga.app
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
     * Install module.
     *
     * @param ApplicationAbstract $app        Application
     * @param ModuleInfo          $info       Module info
     * @param SettingsInterface   $cfgHandler Settings/Configuration handler
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        self::createTables($app->dbPool, $info);
        self::activate($app, $info);
    }

    /**
     * Create tables for module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected static function createTables(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $path = static::PATH . '/Install/db.json';
        if (!\is_file($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        /** @var array[] $definitions */
        $definitions = \json_decode($content, true);

        if (!\is_array($definitions)) {
            return; // @codeCoverageIgnore
        }

        foreach ($definitions as $definition) {
            SchemaBuilder::createFromSchema($definition, $dbPool->get('schema'))->execute();
        }
    }

    /**
     * Activate after install.
     *
     * @param ApplicationAbstract $app  Application
     * @param ModuleInfo          $info Module info
     *
     * @return void
     *
     * @throws \UnexpectedValueException
     *
     * @since 1.0.0
     */
    protected static function activate(ApplicationAbstract $app, ModuleInfo $info) : void
    {
        if (($path = \realpath(static::PATH)) === false) {
            return; // @codeCoverageIgnore
        }

        $classPath = \substr($path . '/Status', (int) \strlen((string) \realpath(__DIR__ . '/../../')));

        /** @var class-string<StatusAbstract> $class */
        $class = \strtr($classPath, '/', '\\');

        if (!Autoloader::exists($class)) {
            throw new \UnexpectedValueException($class); // @codeCoverageIgnore
        }

        $class::activate($app, $info);
    }

    /**
     * Re-init module.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws \UnexpectedValueException
     *
     * @since 1.0.0
     */
    public static function reInit(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        if (($path = \realpath(static::PATH)) === false) {
            return; // @codeCoverageIgnore
        }

        $classPath = \substr($path . '/Status', \strlen((string) \realpath(__DIR__ . '/../../')));

        /** @var class-string<StatusAbstract> $class */
        $class = \strtr($classPath, '/', '\\');

        if (!Autoloader::exists($class)) {
            throw new \UnexpectedValueException($class); // @codeCoverageIgnore
        }

        $class::activateRoutes($info, $appInfo);
        $class::activateHooks($info, $appInfo);
    }
}
