<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

use Model\CoreSettings;
use phpOMS\Application\ApplicationInfo;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;
use phpOMS\System\File\PathException;
use phpOMS\System\File\PermissionException;
use phpOMS\Utils\Parser\Php\ArrayParser;

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
     * Register module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function registerInDatabase(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $queryModule = new Builder($dbPool->get('insert'));
        $queryModule->prefix($dbPool->get('insert')->prefix);
        $queryModule->insert('module_id', 'module_theme', 'module_path', 'module_active', 'module_version')
            ->into('module')
            ->values($info->getInternalName(), 'Default', $info->getDirectory(), 0, $info->getVersion())
            ->execute();

        $queryLoad = new Builder($dbPool->get('insert'));
        $queryLoad->prefix($dbPool->get('insert')->prefix);
        $queryLoad->insert('module_load_pid', 'module_load_type', 'module_load_from', 'module_load_for', 'module_load_file')
            ->into('module_load');

        $load = $info->getLoad();
        foreach ($load as $val) {
            foreach ($val['pid'] as $pid) {
                $queryLoad->values(
                    \sha1(\str_replace('/', '', $pid)),
                    (int) $val['type'],
                    $val['from'],
                    $val['for'],
                    $val['file']
                );
            }
        }

        if (!empty($queryLoad->getValues())) {
            $queryLoad->execute();
        }
    }

    /**
     * Install module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        self::createTables($dbPool, $info);
        self::registerInDatabase($dbPool, $info);
        self::installSettings($dbPool, $info);
        self::initRoutes($info);
        self::initHooks($info);
        self::activate($dbPool, $info);
    }

    /**
     * Install module settings.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function installSettings(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $path = \dirname($info->getPath()) . '/Admin/Install/Settings.install.php';

        if (!\file_exists($path)) {
            return;
        }

        $settings = include $path;

        foreach ($settings as $setting) {
            $settings = new CoreSettings($dbPool->get('insert'));
            $settings->create($setting);
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
    private static function createTables(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $path = \dirname($info->getPath()) . '/Admin/Install/db.json';

        if (!\file_exists($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return;
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
    private static function activate(DatabasePool $dbPool, ModuleInfo $info) : void
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
        self::initRoutes($info, $appInfo);
        self::initHooks($info, $appInfo);
    }

    /**
     * Init routes.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    private static function initRoutes(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(\dirname($info->getPath()) . '/Admin/Routes');

        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    self::installRoutes(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Routes.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                self::installRoutes(__DIR__ . '/../../' . $child->getName() . '/Routes.php', $child->getPath());
            }
        }
    }

    /**
     * Install routes.
     *
     * @param string $destRoutePath Destination route path
     * @param string $srcRoutePath  Source route path
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    private static function installRoutes(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\file_exists($destRoutePath)) {
            \file_put_contents($destRoutePath, '<?php return [];');
        }

        if (!\file_exists($srcRoutePath)) {
            return;
        }

        if (!\file_exists($destRoutePath)) {
            throw new PathException($destRoutePath);
        }

        if (!\is_writable($destRoutePath)) {
            throw new PermissionException($destRoutePath);
        }

        /** @noinspection PhpIncludeInspection */
        $appRoutes = include $destRoutePath;
        /** @noinspection PhpIncludeInspection */
        $moduleRoutes = include $srcRoutePath;

        $appRoutes = \array_merge_recursive($appRoutes, $moduleRoutes);

        \file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', \LOCK_EX);
    }

    /**
     * Init hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    private static function initHooks(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(\dirname($info->getPath()) . '/Admin/Hooks');

        foreach ($directories as $key => $child) {
            if ($child instanceof Directory) {
                foreach ($child as $key2 => $file) {
                    self::installHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Hooks.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                self::installHooks(__DIR__ . '/../../' . $child->getName() . '/Hooks.php', $child->getPath());
            }
        }
    }

    /**
     * Install hooks.
     *
     * @param string $destHookPath Destination hook path
     * @param string $srcHookPath  Source hook path
     *
     * @return void
     *
     * @throws PathException       This exception is thrown if the hook file doesn't exist
     * @throws PermissionException This exception is thrown if the hook file couldn't be updated (no write permission)
     *
     * @since 1.0.0
     */
    private static function installHooks(string $destHookPath, string $srcHookPath) : void
    {
        if (!\file_exists($destHookPath)) {
            \file_put_contents($destHookPath, '<?php return [];');
        }

        if (!\file_exists($srcHookPath)) {
            return;
        }

        if (!\file_exists($destHookPath)) {
            throw new PathException($destHookPath);
        }

        if (!\is_writable($destHookPath)) {
            throw new PermissionException($destHookPath);
        }

        /** @noinspection PhpIncludeInspection */
        $appHooks = include $destHookPath;
        /** @noinspection PhpIncludeInspection */
        $moduleHooks = include $srcHookPath;

        $appHooks = \array_merge_recursive($appHooks, $moduleHooks);

        \file_put_contents($destHookPath, '<?php return ' . ArrayParser::serializeArray($appHooks) . ';', \LOCK_EX);
    }
}
