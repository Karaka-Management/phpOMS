<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Router
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Router;

/**
 * Router class for socket routes.
 *
 * @package phpOMS\Router
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class SocketRouter implements RouterInterface
{

    /**
     * Routes.
     *
     * @var   array
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
        if (!\file_exists($path)) {
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
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function add(string $route, $destination) : void
    {
        if (!isset($this->routes[$route])) {
            $this->routes[$route] = [];
        }

        $this->routes[$route][] = [
            'dest' => $destination,
        ];
    }

    /**
     * Route request.
     *
     * @param string $uri     Route
     * @param string $app     Application name
     * @param int    $orgId   Organization id
     * @param mixed  $account Account
     *
     * @return array[]
     *
     * @since 1.0.0
     */
    public function route(
        string $uri,
        string $app = null,
        int $orgId = null,
        $account = null
    ) : array
    {
        $bound = [];
        foreach ($this->routes as $route => $destination) {
            if (!((bool) \preg_match('~^' . $route . '$~', $uri))) {
                continue;
            }

            foreach ($destination as $d) {
                // if permission check is invalid
                if ((isset($d['permission']) && $account === null)
                    || (isset($d['permission'])
                        && !$account->hasPermission(
                            $d['permission']['type'], $orgId, $app, $d['permission']['module'], $d['permission']['state']
                        )
                    )
                ) {
                    return $app !== null ? $this->route('/' . \strtolower($app) . '/e403') : $this->route('/e403');
                }

                $bound[] = ['dest' => $d['dest']];
            }
        }

        return $bound;
    }
}
