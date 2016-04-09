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
use phpOMS\Module\InfoManager;
use phpOMS\Router\RouteVerb;
use phpOMS\Utils\Parser\Php\ArrayParser;

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
    public static function install(Pool $dbPool, InfoManager $info)
    {
        self::installRoutes(ROOT_PATH . '/Web/Routes.php', ROOT_PATH . '/Modules/' . $info->getDirectory() . '/Admin/Routes/http.php');
        self::installRoutes(ROOT_PATH . '/Socket/Routes.php', ROOT_PATH . '/Modules/' . $info->getDirectory() . '/Admin/Routes/socket.php');
        self::installRoutes(ROOT_PATH . '/Console/Routes.php', ROOT_PATH . '/Modules/' . $info->getDirectory() . '/Admin/Routes/console.php');
    }

    private static function installRoutes(string $appRoutePath, string $moduleRoutePath) 
    {
        if(file_exists($appRoutePath) && file_exists($moduleRoutePath)) {
            $appRoutes = include $appRoutePath;
            $moduleRoutes = include $moduleRoutePath;
            $appRoutes = array_merge_recursive($appRoutes, $moduleRoutes);

            if(is_writable($appRoutePath)) {
                file_put_contents($appRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', LOCK_EX);
            } else {
                throw new PermissionException($appRoutePath);
            }
        }
    }
}
