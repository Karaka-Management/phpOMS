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

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\Parser\Php\ArrayParser;
use phpOMS\System\File\PermissionException;
use phpOMS\System\File\PathException;
use phpOMS\Utils\ArrayUtils;

/**
 * Status abstract class.
 *
 * This abstraction can be used by modules in order to manipulate their basic status/state.
 *
 * @package phpOMS\Application
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class StatusAbstract
{
    /**
     * Deactivate app.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ApplicationInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activate(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        self::activateRoutes($info);
        self::activateHooks($info);
    }

    /**
     * Init routes.
     *
     * @param ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function activateRoutes(ApplicationInfo $appInfo = null) : void
    {
        self::installRoutes(__DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Routes.php', __DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Admin/Install/Application/Routes.php');
    }

    /**
     * Init hooks.
     *
     * @param ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function activateHooks(ApplicationInfo $appInfo = null) : void
    {
        self::installRoutes(__DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Hooks.php', __DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Admin/Install/Application/Hooks.php');
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
     * Deactivate routes.
     *
     * @param ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function deactivateRoutes(ApplicationInfo $appInfo) : void
    {
        self::installRoutes(__DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Routes.php', __DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Admin/Install/Application/Routes.php');
    }

    /**
     * Deactivate hooks.
     *
     * @param ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function deactivateHooks(ApplicationInfo $appInfo) : void
    {
        self::installRoutes(__DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Hooks.php', __DIR__ . '/../../Web/' . $appInfo->getInternalName() . '/Admin/Install/Application/Hooks.php');
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
     * Deactivate app.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ApplicationInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivate(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        self::deactivateRoutes($info);
        self::deactivateHooks($info);
    }
}
