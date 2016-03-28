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

use phpOMS\ApplicationAbstract;

/**
 * ModuleFactory class.
 *
 * Responsible for initializing modules as singletons
 *
 * @category   Module
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ModuleFactory
{

    /**
     * Module instances.
     *
     * Reference to module.class
     *
     * @var ModuleAbstract[]
     * @since 1.0.0
     */
    public static $loaded = [];

    /**
     * Unassigned providing.
     *
     * @var string[][]
     * @since 1.0.0
     */
    public static $providing = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * Gets and initializes modules.
     *
     * @param string             $module Module ID
     * @param ApplicationAbstract $app    Application
     *
     * @return ModuleAbstract
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getInstance(string $module, ApplicationAbstract $app) : ModuleAbstract
    {
        if (!isset(self::$loaded[$module])) {
            try {
                $class = '\\Modules\\' . $module . '\\Controller';
                $obj                   = new $class($app);
                self::$loaded[$module] = $obj;
                self::registerRequesting($obj);
                self::registerProvided($obj);
            } catch (\Exception $e) {
                self::$loaded[$module] = new NullModule($app);
            }
        }

        return self::$loaded[$module];
    }

    /**
     * Load modules this module is requesting from
     *
     * @param ModuleAbstract $obj Current module
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function registerRequesting(ModuleAbstract $obj)
    {
        foreach ($obj->getProviding() as $providing) {
            if (isset(self::$loaded[$providing])) {
                self::$loaded[$providing]->addReceiving($obj->getName());
            } else {
                self::$providing[$providing][] = $obj->getName();
            }
        }
    }

    /**
     * Register modules this module is receiving from
     *
     * @param ModuleAbstract $obj Current module
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function registerProvided(ModuleAbstract $obj)
    {
       $name = $obj->getName();
       if (isset(self::$providing[$name])) {
        foreach (self::$providing[$name] as $providing) {
            self::$loaded[$name]->addReceiving($providing);
        }
    }
}
}
