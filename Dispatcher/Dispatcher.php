<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Dispatcher
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Dispatcher;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\System\File\PathException;

/**
 * Dispatcher class.
 *
 * @package phpOMS\Dispatcher
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Dispatcher implements DispatcherInterface
{
    /**
     * Application.
     *
     * @var null|ApplicationAbstract
     * @since 1.0.0
     */
    private ?ApplicationAbstract $app;

    /**
     * Controller.
     *
     * Set in the module manager on module initialization.
     *
     * @var array<string, object>
     * @since 1.0.0
     */
    public array $controllers = [];

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app Application
     *
     * @since 1.0.0
     */
    public function __construct(?ApplicationAbstract $app = null)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(array | string | callable $controller, mixed ...$data) : array
    {
        $views = [];
        $data  = \array_values($data);

        if (\is_array($controller) && isset($controller['dest'])) {
            if (!empty($controller['data'])) {
                $data = \array_merge(
                    empty($data) ? [] : $data,
                    \is_array($controller['data']) ? $controller['data'] : [$controller['data']]
                );
            }

            $controller = $controller['dest'];
        }

        // Php void functions always return null.
        // In a different language the Api functions would require a return type
        // If null is returned (i.e. void functions) these get ignored later in the response renderer as null is not "rendered"
        if (\is_string($controller)) {
            $dispatch = \explode(':', $controller);

            if (($c = \count($dispatch)) === 3) {
                /* Handling static functions */
                /** @var \Closure $function */
                $function = $dispatch[0] . '::' . $dispatch[2];

                $views[$controller] = $data === null ? $function() : $function(...$data);
            } elseif ($c === 2) {
                $obj                = $this->getController($dispatch[0]);
                $views[$controller] = $data === null
                    ? $obj->{$dispatch[1]}()
                    : $obj->{$dispatch[1]}(...$data);
            }
        } elseif (\is_array($controller)) {
            foreach ($controller as $controllerSingle) {
                $views += $data === null
                    ? $this->dispatch($controllerSingle)
                    : $this->dispatch($controllerSingle, ...$data);
            }
        } else {
            $views[] = $data === null
                ? $controller($this->app)
                : $controller($this->app, ...$data);
        }

        return $views;
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
     * @since 1.0.0
     */
    private function getController(string $controller) : object
    {
        if (!isset($this->controllers[$controller])) {
            $this->controllers[$controller] = new $controller($this->app);
        }

        return $this->controllers[$controller];
    }
}
