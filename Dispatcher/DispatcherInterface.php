<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Dispatcher
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Dispatcher;

/**
 * Dispatcher interface
 *
 * @package phpOMS\Dispatcher
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface DispatcherInterface
{
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
     * @since 1.0.0
     */
    public function dispatch(array | string | \Closure $controller, ...$data) : array;
}
