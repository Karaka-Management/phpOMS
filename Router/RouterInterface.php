<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Schema
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Router;

/**
 * Router interface.
 *
 * @package phpOMS\Router
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface RouterInterface
{
    /**
     * Add routes from file.
     *
     * @param string $path Route file path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function importFromFile(string $path) : bool;

    /**
     * Clear routes
     *
     * @return void
     * @since 1.0.0
     */
    public function clear() : void;
}
