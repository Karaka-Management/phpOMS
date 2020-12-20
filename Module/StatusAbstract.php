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

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;
use phpOMS\System\File\PathException;
use phpOMS\System\File\PermissionException;
use phpOMS\Utils\Parser\Php\ArrayParser;
use phpOMS\Application\ApplicationInfo;
use phpOMS\Utils\ArrayUtils;

/**
 * Status abstract class.
 *
 * This abstraction can be used by modules in order to manipulate their basic status/state.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class StatusAbstract
{
    /**
     * Deactivate module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activate(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        self::activateRoutes($info);
        self::activateHooks($info);
        self::activateInDatabase($dbPool, $info);
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
    public static function activateRoutes(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(\dirname($info->getPath()) . '/Admin/Routes');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::installRoutes(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Routes.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

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
    protected static function installRoutes(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\is_file($destRoutePath)) {
            \file_put_contents($destRoutePath, '<?php return [];');
        }

        if (!\is_file($srcRoutePath)) {
            return;
        }

        if (!\is_file($destRoutePath)) {
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
    public static function activateHooks(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(\dirname($info->getPath()) . '/Admin/Hooks');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::installHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Hooks.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

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
    protected static function installHooks(string $destHookPath, string $srcHookPath) : void
    {
        if (!\is_file($destHookPath)) {
            \file_put_contents($destHookPath, '<?php return [];');
        }

        if (!\is_file($srcHookPath)) {
            return;
        }

        if (!\is_file($destHookPath)) {
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

    /**
     * Deactivate module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activateInDatabase(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $query = new Builder($dbPool->get('update'));
        $query->update('module')
            ->sets('module.module_active', 1)
            ->where('module.module_id', '=', $info->getInternalName())
            ->execute();
    }

    /**
     * Deactivate module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivate(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        self::deactivateRoutes($info);
        self::deactivateHooks($info);
        self::deactivateInDatabase($dbPool, $info);
    }

    /**
     * Deactivate routes.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateRoutes(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(\dirname($info->getPath()) . '/Admin/Routes');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::uninstallRoutes(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Routes.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::uninstallRoutes(__DIR__ . '/../../' . $child->getName() . '/Routes.php', $child->getPath());
            }
        }
    }

    /**
     * Uninstall routes.
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
    public static function uninstallRoutes(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\is_file($destRoutePath)
            || !\is_file($srcRoutePath)
        ) {
            return;
        }

        if (!\is_file($destRoutePath)) {
            throw new PathException($destRoutePath);
        }

        if (!\is_writable($destRoutePath)) {
            throw new PermissionException($destRoutePath);
        }

        /** @noinspection PhpIncludeInspection */
        $appRoutes = include $destRoutePath;
        /** @noinspection PhpIncludeInspection */
        $moduleRoutes = include $srcRoutePath;

        $appRoutes = ArrayUtils::array_diff_assoc_recursive($appRoutes, $moduleRoutes);

        \file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', \LOCK_EX);
    }

    /**
     * Deactivate hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateHooks(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(\dirname($info->getPath()) . '/Admin/Hooks');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::uninstallHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Hooks.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::uninstallHooks(__DIR__ . '/../../' . $child->getName() . '/Hooks.php', $child->getPath());
            }
        }
    }

    /**
     * Uninstall hooks.
     *
     * @param string $destHookPath Destination hook path
     * @param string $srcHookPath  Source hook path
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    protected static function uninstallHooks(string $destHookPath, string $srcHookPath) : void
    {
        if (!\is_file($destHookPath)
            || !\is_file($srcHookPath)
        ) {
            return;
        }

        if (!\is_file($destHookPath)) {
            throw new PathException($destHookPath);
        }

        if (!\is_writable($destHookPath)) {
            throw new PermissionException($destHookPath);
        }

        /** @noinspection PhpIncludeInspection */
        $appHooks = include $destHookPath;
        /** @noinspection PhpIncludeInspection */
        $moduleHooks = include $srcHookPath;

        $appHooks = ArrayUtils::array_diff_assoc_recursive($appHooks, $moduleHooks);

        \file_put_contents($destHookPath, '<?php return ' . ArrayParser::serializeArray($appHooks) . ';', \LOCK_EX);
    }

    /**
     * Deactivate module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateInDatabase(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $query = new Builder($dbPool->get('update'));
        $query->update('module')
            ->sets('module.module_active', 0)
            ->where('module.module_id', '=', $info->getInternalName())
            ->execute();
    }
}
