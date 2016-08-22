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
namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @category   Framework
 * @package    phpOMS\System\File
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
final class Storage
{
    private static $registered = [];

    public static function env(string $env = 'local') : string
    {
        if (isset(self::$registered[$env])) {
            $env = self::$registered[$env];
        } else {
            $env = ucfirst(strtolower($env));
            $env = __NAMESPACE__ . '\\' . $env . '\\' . $env . 'Storage';
        }

        return $env::getInstance();
    }

    public static function register(string $name, string $class) : bool
    {
        if (isset(self::$registered[$name])) {
            return false;
        }

        self::$registered[$name] = $class;

        return true;
    }
}
