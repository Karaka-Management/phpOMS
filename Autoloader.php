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
     * @param \string $class Class path
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function default_autoloader(\string $class)
    {
        if (($class = self::exists($class)) !== false) {
            /** @noinspection PhpIncludeInspection */
            include __DIR__ . '/../' . $class . '.php';
        }
    }

    /**
     * Check if class exists.
     *
     * @param \string $class Class path
     *
     * @return false|string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function exists(\string $class)
    {
        $class = ltrim($class, '\\');
        $class = str_replace(['_', '\\'], DIRECTORY_SEPARATOR, $class);

        if (file_exists(__DIR__ . '/../' . $class . '.php')) {
            return $class;
        }

        return false;
    }

}
