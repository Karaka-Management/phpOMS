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
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Module\ModuleAbstract;
use phpOMS\System\File\PathException;
use phpOMS\Views\ViewLayout;

/**
 * Dispatcher class.
 *
 * @category   Framework
 * @package    Framework
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
     * @param RequestAbstract       $request    Request
     * @param ResponseAbstract      $response   Response
     * @param mixed                 $data       Data
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function dispatch($controller, RequestAbstract $request, ResponseAbstract $response, $data = null) : array
    {
        $views = [];
        $type  = ViewLayout::UNDEFINED;

        if (is_array($controller) && isset($controller['type'])) {
            $type       = $controller['type'];
            $controller = $controller['dest'];
        }

        if (is_string($controller)) {
            $views += $this->dispatchString($controller, $request, $response, $data);
        } elseif (is_array($controller)) {
            $views += $this->dispatchArray($controller, $request, $response, $data);
        } elseif ($controller instanceof \Closure) {
            $views[$type][] = $this->dispatchClosure($controller, $request, $response, $data);
        } else {
            throw new \UnexpectedValueException('Unexpected controller type.');
        }

        return $views;
    }

    private function dispatchString(string $controller, RequestAbstract $request, ResponseAbstract $response, $data = null)
    {
        $views =[];
        $dispatch = explode(':', $controller);
        $this->getController($dispatch[0]);

        if (($c = count($dispatch)) == 3) {
            /* Handling static functions */
            $views[$type][$controller] = $dispatch[0]::$dispatch[2]();
        } elseif ($c == 2) {
            $views[$type][$controller] = $this->controllers[$dispatch[0]]->{$dispatch[1]}($request, $response, $data);
        } else {
            throw new \UnexpectedValueException('Unexpected function.');
        }

        return $views
    }

    private function dispatchArray(array $controller, RequestAbstract $request, ResponseAbstract $response, $data = null) : array
    {
        $views = [];
        foreach ($controller as $controllerSingle) {
            foreach ($controllerSingle as $c) {
                $views += $this->dispatch($c, $request, $response, $data);
            }
        }

        return $views;
    }

    private function dispatchClosure(\Closure $controller, RequestAbstract $request, ResponseAbstract $response, $data = null)
    {
        return $controller($this->app, $request, $response, $data);
    }

    private function getController(string $controller)
    {
        if (!isset($this->controllers[$controller])) {
            if (realpath($path = ROOT_PATH . '/' . str_replace('\\', '/', $controller) . '.php') === false) {
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
