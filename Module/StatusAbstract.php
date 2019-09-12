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

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;

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
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activate(DatabasePool $dbPool, InfoManager $info) : void
    {
        self::activateRoutes(__DIR__ . '/../../Web/Routes.php', __DIR__ . '/../../Modules/' . $info->getDirectory() . '/Admin/Routes/');
        self::activateInDatabase($dbPool, $info);
    }

    /**
     * Install routes.
     *
     * @param string $destRoutePath Destination route path
     * @param string $srcRoutePath  Source route path
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function activateRoutes(string $destRoutePath, string $srcRoutePath) : void
    {
        // todo: remove route
    }

    /**
     * Deactivate module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activateInDatabase(DatabasePool $dbPool, InfoManager $info) : void
    {
        $query = new Builder($dbPool->get('update'));
        $query->prefix($dbPool->get('update')->prefix);
        $query->update('module')
            ->sets('module.module_active', 1)
            ->where('module.module_id', '=', $info->getInternalName())
            ->execute();
    }

    /**
     * Deactivate module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivate(DatabasePool $dbPool, InfoManager $info) : void
    {
        self::deactivateRoutes(__DIR__ . '/../../Web/Routes.php', __DIR__ . '/../../Modules/' . $info->getDirectory() . '/Admin/Routes/');
        self::deactivateInDatabase($dbPool, $info);
    }

    /**
     * Install routes.
     *
     * @param string $destRoutePath Destination route path
     * @param string $srcRoutePath  Source route path
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function deactivateRoutes(string $destRoutePath, string $srcRoutePath) : void
    {
        // todo: remove route
    }

    /**
     * Deactivate module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateInDatabase(DatabasePool $dbPool, InfoManager $info) : void
    {
        $query = new Builder($dbPool->get('update'));
        $query->prefix($dbPool->get('update')->prefix);
        $query->update('module')
            ->sets('module.module_active', 0)
            ->where('module.module_id', '=', $info->getInternalName())
            ->execute();
    }
}
