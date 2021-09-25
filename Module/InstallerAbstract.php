<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationInfo;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Installer abstract class.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class InstallerAbstract
{
    /**
     * Install module.
     *
     * @param DatabasePool      $dbPool     Database instance
     * @param ModuleInfo        $info       Module info
     * @param SettingsInterface $cfgHandler Settings/Configuration handler
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(DatabasePool $dbPool, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        self::createTables($dbPool, $info);
        self::installSettings($dbPool, $info, $cfgHandler);
        self::activate($dbPool, $info);
    }

    /**
     * Install module settings.
     *
     * @param DatabasePool      $dbPool     Database instance
     * @param ModuleInfo        $info       Module info
     * @param SettingsInterface $cfgHandler Settings/Configuration handler
     *
     * @return void
     *
     * @since 1.0.0
     * @todo move to admin module as providing option (like media providing `Admin.install.php` instead of Settings.install.php)
     */
    public static function installSettings(DatabasePool $dbPool, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        $path = \dirname($info->getPath()) . '/Admin/Install/Settings.install.php';
        if (!\is_file($path)) {
            return;
        }

        $settings = include $path;

        foreach ($settings as $setting) {
            $cfgHandler->create($setting);
        }
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
        $path = \dirname($info->getPath()) . '/Admin/Install/db.json';
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
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected static function activate(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        /** @var StatusAbstract $class */
        $class = '\Modules\\' . $info->getDirectory() . '\Admin\Status';
        $class::activate($dbPool, $info);
    }

    /**
     * Re-init module.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function reInit(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $class = '\Modules\\' . $info->getDirectory() . '\Admin\Status';
        $class::activateRoutes($info, $appInfo);
        $class::activateHooks($info, $appInfo);
    }
}
