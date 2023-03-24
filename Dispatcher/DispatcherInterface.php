<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Dispatcher
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Dispatcher;

/**
 * Dispatcher interface
 *
 * @package phpOMS\Dispatcher
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface DispatcherInterface
{
    /**
     * Dispatch controller.
     *
     * @param array|\Closure|string $controller Controller
     * @param mixed                 ...$data    Data
     *
     * @return array Returns array of all dispatched results
     *
     * @throws \UnexpectedValueException This exception is thrown for unsupported controller representations
     *
     * @since 1.0.0
     */
    public function dispatch(array | string | \Closure $controller, mixed ...$data) : array;
}
