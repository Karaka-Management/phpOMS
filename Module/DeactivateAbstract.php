<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Module;

use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Pool;

/**
 * Installer Abstract class.
 *
 * @category   Framework
 * @package    phpOMS\Module
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class DeactivateAbstract
{

    /**
     * Deactivate module.
     *
     * @param Pool        $dbPool Database instance
     * @param InfoManager $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function deactivate(Pool $dbPool, InfoManager $info)
    {
        self::deactivateRoutes(ROOT_PATH . '/Web/Routes.php', ROOT_PATH . '/Modules/' . $info->getDirectory() . '/Admin/Routes/http.php');
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function deactivateRoutes(string $destRoutePath, string $srcRoutePath)
    {
        // todo: remove route
    }

    /**
     * Deactivate module in database.
     *
     * @param Pool        $dbPool Database instance
     * @param InfoManager $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function deactivateInDatabase(Pool $dbPool, InfoManager $info)
    {
        switch ($dbPool->get()->getType()) {
            case DatabaseType::MYSQL:
                $dbPool->get()->con->beginTransaction();

                $sth = $dbPool->get()->con->prepare(
                    'UPDATE `' . $dbPool->get()->prefix . 'module` SET `module_active` = :active WHERE `module_id` = :internal;'
                );

                $sth->bindValue(':internal', $info->getInternalName(), \PDO::PARAM_INT);
                $sth->bindValue(':active', 0, \PDO::PARAM_INT);
                $sth->execute();

                $dbPool->get()->con->commit();

                break;
        }
    }
}
