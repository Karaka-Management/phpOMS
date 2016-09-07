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
namespace phpOMS\Dispatcher;

use phpOMS\ApplicationAbstract;
use phpOMS\Module\ModuleAbstract;
use phpOMS\System\File\PathException;

/**
 * Dispatcher class.
 *
 * @category   Framework
 * @package    phpOMS\Dispatcher
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Dispatcher
{

    /**
     * Application.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    private $app = null;

    /**
     * Controller.
     *
     * Set in the module manager on module initialization.
     *
     * @var array
     * @since 1.0.0
     */
    private $controllers = [];

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app Appliaction
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ApplicationAbstract $app)
    {
        $this->app = $app;
    }

    /**
     * Dispatch controller.
     *
     * @param string|array|\Closure $controller Controller string
     * @param mixed                 $data       Data
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function dispatch($controller, ...$data) : array
    {
        $views = [];

        if (is_array($controller) && isset($controller['dest'])) {
            $controller = $controller['dest'];
        }

        if (is_string($controller)) {
            $views += $this->dispatchString($controller, $data);
        } elseif (is_array($controller)) {
            $views += $this->dispatchArray($controller, $data);
        } elseif ($controller instanceof \Closure) {
            $views[] = $this->dispatchClosure($controller, $data);
        } else {
            throw new \UnexpectedValueException('Unexpected controller type.');
        }

        return $views;
    }

    /**
     * Dispatch string.
     *
     * @param string|array|\Closure $controller Controller string
     * @param array                 $data       Data
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function dispatchString(string $controller, array $data = null) : array
    {
        $views    = [];
        $dispatch = explode(':', $controller);
        $this->getController($dispatch[0]);

        if (($c = count($dispatch)) === 3) {
            /* Handling static functions */
            $function           = $dispatch[0] . '::' . $dispatch[2];
            $views[$controller] = $function(...$data);
        } elseif ($c === 2) {
            $views[$controller] = $this->controllers[$dispatch[0]]->{$dispatch[1]}(...$data);
        } else {
            throw new \UnexpectedValueException('Unexpected function.');
        }

        return $views;
    }

    /**
     * Dispatch array.
     *
     * @param string|array|\Closure $controller Controller string
     * @param array                 $data       Data
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function dispatchArray(array $controller, array $data = null) : array
    {
        $views = [];
        foreach ($controller as $controllerSingle) {
            foreach ($controllerSingle as $c) {
                $views += $this->dispatch($c, ...$data);
            }
        }

        return $views;
    }

    /**
     * Dispatch closure.
     *
     * @param string|array|\Closure $controller Controller string
     * @param array                 $data       Data
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function dispatchClosure(\Closure $controller, array $data = null)
    {
        return $controller($this->app, ...$data);
    }

    /**
     * Dispatch controller.
     *
     * @param string $controller Controller
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getController(string $controller)
    {
        if (!isset($this->controllers[$controller])) {
            if (!file_exists($path = __DIR__ . '/../../' . str_replace('\\', '/', $controller) . '.php')) {
                throw new PathException($path);
            }

            $this->controllers[$controller] = new $controller($this->app);
        }

        return $this->controllers[$controller];
    }

    /**
     * Set controller by alias.
     *
     * @param ModuleAbstract $controller Controller
     * @param string         $name       Controller string
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(ModuleAbstract $controller, string $name) : bool
    {
        if (!isset($this->controllers[$name])) {
            $this->controllers[$name] = $controller;

            return true;
        }

        return false;
    }
}
