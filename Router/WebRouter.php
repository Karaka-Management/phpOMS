<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Router
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Account\Account;

/**
 * Router class for web routes.
 *
 * @package phpOMS\Router
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
     * Files need to return a php array of the following structure:
     * return [
     *      '{REGEX_PATH}' => [
     *          'dest' => '{DESTINATION_NAMESPACE:method}', // can also be static by using :: between namespace and function name
     *          'verb' => RouteVerb::{VERB},
     *          'csrf' => true,
     *          'permission' => [ // optional
     *              'module' => '{MODULE_NAME}',
     *              'type' => PermissionType::{TYPE},
     *              'state' => PermissionState::{STATE},
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
    ) : void {
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
     * @return array[]
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
                        return $app !== null ? $this->route('/' . \strtolower($app) . '/e403', $csrf, $verb) : $this->route('/e403', $csrf, $verb);
                    }

                    // if permission check is invalid
                    if ((isset($d['permission']) && $account === null)
                        || (isset($d['permission'])
                            && !$account->hasPermission(
                                $d['permission']['type'] ?? 0, $d['permission']['unit'] ?? $orgId, $app, $d['permission']['module'] ?? null, $d['permission']['state'] ?? null
                            )
                        )
                    ) {
                        return $app !== null ? $this->route('/' . \strtolower($app) . '/e403', $csrf, $verb) : $this->route('/e403', $csrf, $verb);
                    }

                    // if validation check is invalid
                    if (isset($d['validation'])) {
                        foreach ($d['validation'] as $name => $pattern) {
                            if (!isset($data[$name]) || \preg_match($pattern, $data[$name]) !== 1) {
                                return $app !== null ? $this->route('/' . \strtolower($app) . '/e403', $csrf, $verb) : $this->route('/e403', $csrf, $verb);
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
