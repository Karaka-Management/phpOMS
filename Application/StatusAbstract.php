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
use phpOMS\System\File\PathException;
use phpOMS\System\File\PermissionException;
use phpOMS\Utils\Parser\Php\ArrayParser;

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
    public const PATH = '';

    /**
     * Deactivate app.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   Module info
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
        self::installRoutesHooks(static::PATH . '/../Routes.php', static::PATH . '/../Admin/Install/Application/Routes.php');
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
        self::installRoutesHooks(static::PATH . '/../Hooks.php', static::PATH . '/../Admin/Install/Application/Hooks.php');
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
    protected static function installRoutesHooks(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\is_file($srcRoutePath)) {
            return;
        }

        if (!\is_file($destRoutePath)) {
            \file_put_contents($destRoutePath, '<?php return [];');
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
        $srcRoutes = include $srcRoutePath;

        $appRoutes = \array_merge_recursive($appRoutes, $srcRoutes);

        \file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', \LOCK_EX);
    }

    /**
     * Clear all routes.
     *
     * @return void
     *
     * @throws PathException
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function clearRoutes() : void
    {
        \file_put_contents(static::PATH . '/../Routes.php', '<?php return [];', \LOCK_EX);
    }

    /**
     * Clear all hooks.
     *
     * @return void
     *
     * @throws PathException
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function clearHooks() : void
    {
        \file_put_contents(static::PATH . '/../Hooks.php', '<?php return [];', \LOCK_EX);
    }
}
