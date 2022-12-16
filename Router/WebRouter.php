<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Router
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Account\Account;
use phpOMS\Account\NullAccount;

/**
 * Router class for web routes.
 *
 * @package phpOMS\Router
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class WebRouter implements RouterInterface
{
    /**
     * Routes.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    private array $routes = [];

    /**
     * Add routes from file.
     *
     * Files need to return a php array of the following structure (see PermissionHandlingTrait):
     * return [
     *      '{REGEX_PATH}' => [
     *          'dest' => '{DESTINATION_NAMESPACE:method}', // use :: for static functions
     *          'verb' => RouteVerb::{VERB},
     *          'csrf' => true,
     *          'permission' => [ // optional
     *              'module' => '{NAME}',
     *              'type' => PermissionType::{TYPE},
     *              'category' => PermissionCategory::{STATE},
     *          ],
     *          // define different destination for different verb
     *      ],
     *      // define another regex path, destination, permission here
     * ];
     *
     * @param string $path Route file path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function importFromFile(string $path) : bool
    {
        if (!\is_file($path)) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        $this->routes += include $path;

        return true;
    }

    /**
     * Clear routes
     *
     * @return void
     * @since 1.0.0
     */
    public function clear() : void
    {
        $this->routes = [];
    }

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
        bool $csrf = false, array $validation = [],
        string $dataPattern = ''
    ) : void
    {
        if (!isset($this->routes[$route])) {
            $this->routes[$route] = [];
        }

        $this->routes[$route][] = [
            'dest'       => $destination,
            'verb'       => $verb,
            'csrf'       => $csrf,
            'validation' => empty($validation) ? null : $validation,
            'pattern'    => empty($dataPattern) ? null : $dataPattern,
        ];
    }

    /**
     * Route request.
     *
     * @param string  $uri     Route
     * @param string  $csrf    CSRF token
     * @param int     $verb    Route verb
     * @param string  $app     Application name
     * @param int     $orgId   Organization id
     * @param Account $account Account
     * @param array   $data    Validation
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function route(
        string $uri,
        string $csrf = null,
        int $verb = RouteVerb::GET,
        string $app = null,
        int $orgId = null,
        Account $account = null,
        array $data = null
    ) : array
    {
        $bound = [];
        foreach ($this->routes as $route => $destination) {
            if (!((bool) \preg_match('~^' . $route . '$~', $uri))) {
                continue;
            }

            foreach ($destination as $d) {
                if ($d['verb'] === RouteVerb::ANY
                    || $verb === RouteVerb::ANY
                    || ($verb & $d['verb']) === $verb
                ) {
                    // if csrf is required but not set
                    if (isset($d['csrf']) && $d['csrf'] && $csrf === null) {
                        return ['dest' => RouteStatus::INVALID_CSRF];
                    }

                    // if permission check is invalid
                    if (isset($d['permission']) && !empty($d['permission'])
                        && ($account === null || $account instanceof NullAccount)
                    ) {
                        return ['dest' => RouteStatus::NOT_LOGGED_IN];
                    } elseif (isset($d['permission']) && !empty($d['permission'])
                        && !($account?->hasPermission(
                                $d['permission']['type'] ?? 0,
                                $d['permission']['unit'] ?? $orgId,
                                $app,
                                $d['permission']['module'] ?? null,
                                $d['permission']['category'] ?? null
                            )
                        )
                    ) {
                        return ['dest' => RouteStatus::INVALID_PERMISSIONS];
                    }

                    // if validation check is invalid
                    if (isset($d['validation'])) {
                        foreach ($d['validation'] as $name => $validation) {
                            if (!isset($data[$name]) || \preg_match($validation, $data[$name]) !== 1) {
                                return ['dest' => RouteStatus::INVALID_DATA];
                            }
                        }
                    }

                    $temp = ['dest' => $d['dest']];

                    // fill data
                    if (isset($d['pattern'])) {
                        \preg_match($d['pattern'], $uri, $matches);

                        $temp['data'] = $matches;
                    }

                    $bound[] = $temp;
                }
            }
        }

        return $bound;
    }
}
