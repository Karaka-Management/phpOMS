<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Dispatcher
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Dispatcher;

use phpOMS\ApplicationAbstract;
use phpOMS\Autoloader;
use phpOMS\Module\ModuleAbstract;
use phpOMS\System\File\PathException;

/**
 * Dispatcher class.
 *
 * @package    phpOMS\Dispatcher
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class Dispatcher
{

    /**
     * Application.
     *
     * @var null|ApplicationAbstract
     * @since 1.0.0
     */
    private ?ApplicationAbstract $app = null;

    /**
     * Controller.
     *
     * Set in the module manager on module initialization.
     *
     * @var array
     * @since 1.0.0
     */
    private array $controllers = [];

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app Appliaction
     *
     * @since  1.0.0
     */
    public function __construct(ApplicationAbstract $app = null)
    {
        $this->app = $app;
    }

    /**
     * Dispatch controller.
     *
     * @param array|\Closure|string $controller Controller
     * @param null|array|mixed      ...$data    Data
     *
     * @return array Returns array of all dispatched results
     *
     * @throws \UnexpectedValueException This exception is thrown for unsupported controller representations
     *
     * @since  1.0.0
     */
    public function dispatch($controller, ...$data) : array
    {
        $views = [];

        if (\is_array($controller) && isset($controller['dest'])) {
            $controller = $controller['dest'];
        }

        if (\is_string($controller)) {
            $views += $this->dispatchString($controller, $data);
        } elseif (\is_array($controller)) {
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
     * The disptacher can dispatch static functions.
     * String: `some/namespace/path::myStaticFunction`
     *
     * Additionally it's also possible to dispatch functions of modules.
     * Modules are classes which can get instantiated with `new Class(ApplicationAbstract $app)`
     * String: `some/namespace/path:myMethod`
     *
     * @param string     $controller Controller string
     * @param null|array $data       Data
     *
     * @return array
     *
     * @throws PathException             this exception is thrown if the function cannot be autoloaded
     * @throws \Exception                this exception is thrown if the function is not callable
     * @throws \UnexpectedValueException this exception is thrown if the controller string is malformed
     *
     * @since  1.0.0
     */
    private function dispatchString(string $controller, array $data = null) : array
    {
        $views    = [];
        $dispatch = \explode(':', $controller);

        if (!Autoloader::exists($dispatch[0])) {
            throw new PathException($dispatch[0]);
        }

        if (($c = \count($dispatch)) === 3) {
            /* Handling static functions */
            $function = $dispatch[0] . '::' . $dispatch[2];

            if (!\is_callable($function)) {
                throw new \Exception();
            }

            $views[$controller] = $data === null ? $function() : $function(...$data);
        } elseif ($c === 2) {
            $this->getController($dispatch[0]);
            $views[$controller] = $data === null ? $this->controllers[$dispatch[0]]->{$dispatch[1]}() : $this->controllers[$dispatch[0]]->{$dispatch[1]}(...$data);
        } else {
            throw new \UnexpectedValueException('Unexpected function.');
        }

        return $views;
    }

    /**
     * Dispatch array.
     *
     * @param array      $controller Controller string
     * @param null|array $data       Data
     *
     * @return array
     *
     * @since  1.0.0
     */
    private function dispatchArray(array $controller, array $data = null) : array
    {
        $views = [];
        foreach ($controller as $controllerSingle) {
            $views += $data === null ? $this->dispatch($controllerSingle) : $this->dispatch($controllerSingle, ...$data);
        }

        return $views;
    }

    /**
     * Dispatch closure.
     *
     * @param \Closure   $controller Controller string
     * @param null|array $data       Data
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private function dispatchClosure(\Closure $controller, array $data = null)
    {
        return $data === null ? $controller($this->app) : $controller($this->app, ...$data);
    }

    /**
     * Dispatch controller.
     *
     * @param string $controller Controller
     *
     * @return object
     *
     * @throws PathException this exception is thrown in case the controller couldn't be found
     *
     * @since  1.0.0
     */
    private function getController(string $controller) : object
    {
        if (!isset($this->controllers[$controller])) {
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
     * @return void
     *
     * @since  1.0.0
     */
    public function set(ModuleAbstract $controller, string $name) : void
    {
        $this->controllers[$name] = $controller;
    }
}
