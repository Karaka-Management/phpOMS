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
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS;

spl_autoload_register('\phpOMS\Autoloader::default_autoloader');

/**
 * Autoloader class.
 *
 * @category   Framework
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Autoloader
{

    /**
     * Loading classes by namespace + class name.
     *
     * @param string $class Class path
     *
     * @example Autoloader::default_autoloader('\phpOMS\Autoloader') // void
     *
     * @return void
     *
     * @throws AutoloadException Throws this exception if the class to autoload doesn't exist. This could also be related to a wrong namespace/file path correlation.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function default_autoloader(string $class) /* : void */
    {
        $class = ltrim($class, '\\');
        $class = str_replace(['_', '\\'], '/', $class);

        /** @noinspection PhpIncludeInspection */
        include_once __DIR__ . '/../' . $class . '.php';
    }

    /**
     * Check if class exists.
     *
     * @param string $class Class path
     *
     * @example Autoloader::exists('\phpOMS\Autoloader') // true
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function exists(string $class) : bool
    {
        $class = ltrim($class, '\\');
        $class = str_replace(['_', '\\'], '/', $class);

        return file_exists(__DIR__ . '/../' . $class . '.php');
    }

}
