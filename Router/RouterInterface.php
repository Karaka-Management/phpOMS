<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Schema
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Account\Account;

/**
 * Router interface.
 *
 * @package phpOMS\Router
 * @license OMS License 2.2
 * @link    https://jingga.app
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

    /**
     * Add route.
     *
     * @param string $route       Route regex
     * @param mixed  $destination Destination e.g. Module:function string or callback
     * @param int    $verb        Request verb
     * @param bool   $csrf        Is CSRF token required
     * @param array  $validation  Validation patterns
     * @param string $dataPattern Data patterns
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function add(
        string $route,
        mixed $destination,
        int $verb = RouteVerb::GET,
        bool $csrf = false,
        array $validation = [],
        string $dataPattern = ''
    ) : void;

    /**
     * Route request.
     *
     * @param string  $uri     Route
     * @param string  $csrf    CSRF token
     * @param int     $verb    Route verb
     * @param int     $app     Application name
     * @param int     $unitId  Organization id
     * @param Account $account Account
     * @param array   $data    Data
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function route(
        string $uri,
        ?string $csrf = null,
        int $verb = RouteVerb::GET,
        ?int $app = null,
        ?int $unitId = null,
        ?Account $account = null,
        ?array $data = null
    ) : array;
}
