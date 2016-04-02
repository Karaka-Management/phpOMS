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
class InstallerAbstract
{

    /**
     * Install module.
     *
     * @param Pool  $dbPool Database instance
     * @param array $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function install(Pool $dbPool, array $info)
    {

        self::$installRoutes(ROOT_PATH . '/Web/Routes.php', ROOT_PATH . '/Modules/' . $info['directory'] . '/Admin/Routes/http.php');
        self::$installRoutes(ROOT_PATH . '/Socket/Routes.php', ROOT_PATH . '/Modules/' . $info['directory'] . '/Admin/Routes/socket.php');
        self::$installRoutes(ROOT_PATH . '/Console/Routes.php', ROOT_PATH . '/Modules/' . $info['directory'] . '/Admin/Routes/console.php');
    }

    private static function installRoutes(string $appRoutePath, string $moduleRoutePath) 
    {
        if(file_exists($appRoutePath) && file_exists($moduleRoutePath)) {
            include $appRoutePath;
            include $moduleRoutePath;
            $appRoutes = array_merge_recursive($appRoutes, $moduleRoutes);

            if(is_writable($appRoutePath)) {
                file_put_contents(ArrayParser::createFile('moduleRoutes', $appRoutes), $appRoutePath);
            } else {
                throw new PermissionException($appRoutePath);
            }
        }
    }
}
