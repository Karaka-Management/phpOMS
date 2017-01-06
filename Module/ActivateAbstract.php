<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
use phpOMS\DataStorage\Database\DatabasePool;

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
class ActivateAbstract
{

    /**
     * Deactivate module.
     *
     * @param DatabasePool        $dbPool Database instance
     * @param InfoManager $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function activate(DatabasePool $dbPool, InfoManager $info) /* : void */
    {
        self::activateRoutes(ROOT_PATH . '/Web/Routes.php', ROOT_PATH . '/Modules/' . $info->getDirectory() . '/Admin/Routes/http.php');
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function activateRoutes(string $destRoutePath, string $srcRoutePath) /* : void */
    {
        // todo: remove route
    }

    /**
     * Deactivate module in database.
     *
     * @param DatabasePool        $dbPool Database instance
     * @param InfoManager $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function activateInDatabase(DatabasePool $dbPool, InfoManager $info) /* : void */
    {
        switch ($dbPool->get()->getType()) {
            case DatabaseType::MYSQL:
                $dbPool->get()->con->beginTransaction();

                $sth = $dbPool->get()->con->prepare(
                    'UPDATE `' . $dbPool->get()->prefix . 'module` SET `module_active` = :active WHERE `module_id` = :internal;'
                );

                $sth->bindValue(':internal', $info->getInternalName(), \PDO::PARAM_INT);
                $sth->bindValue(':active', 1, \PDO::PARAM_INT);
                $sth->execute();

                $dbPool->get()->con->commit();

                break;
        }
    }
}
