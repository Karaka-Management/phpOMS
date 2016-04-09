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

    /**
     * Install routes.
     *
     * @param string  $destRoutePath Destination route path
     * @param string $srcRoutePath   Source route path
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function installRoutes(string $destRoutePath, string $srcRoutePath) 
    {
        if(file_exists($destRoutePath) && file_exists($srcRoutePath)) {
            $appRoutes = include $destRoutePath;
            $moduleRoutes = include $srcRoutePath;
            $appRoutes = array_merge_recursive($appRoutes, $moduleRoutes);

            if(is_writable($destRoutePath)) {
                file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', LOCK_EX);
            } else {
                throw new PermissionException($destRoutePath);
            }
        }
    }
}
