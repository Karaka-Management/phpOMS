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
 * Router class for web routes.
 *
 * @package phpOMS\Router
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/phpOMS#191
 *  Implement routing parameters
 *  Most routing implementations have parameters in their route e.g.
 *      'route' => '/your/url/{@id}/something'
 *  This is very easy to read but slows down performance.
 *      'route' => [
 *           'match' => '/your/url/.*?/something',
 *           'parameters'=> [
 *               'id' => ['type' => 'path', 'index' => 2]
 *           ]
 *       ]
 *  The parameters should then be passed to the method in the $data = [] variable.
 *
 * @todo Orange-Management/phpOMS#192
 *  Implement form/api data validation
 *  Similar to permission validation it could be possible to add data constraints which are expected for certain routes which then could be checked during routing and dispatching.
 *  For example it would be possible to define required data fields, their type, their pattern etc.
 *  This would make the routing definitions much bigger but also dramatically reduce the work which needs to be done in the controllers.
 *  It could even be written in a way which hardly effects performance.
 */
final class WebRouter implements RouterInterface
{
    /**
     * Routes.
     *
     * @var array
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
     * @param int    $verb        Request verb
     * @param bool   $csrf        Is CSRF token required
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function add(string $route, $destination, int $verb = RouteVerb::GET, bool $csrf = false) : void
    {
        if (!isset($this->routes[$route])) {
            $this->routes[$route] = [];
        }

        $this->routes[$route][] = [
            'dest' => $destination,
            'verb' => $verb,
            'csrf' => $csrf,
        ];
    }

    /**
     * Route request.
     *
     * @param string $uri     Route
     * @param string $csrf    CSRF token
     * @param int    $verb    Route verb
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
        string $csrf = null,
        int $verb = RouteVerb::GET,
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
                                $d['permission']['type'], $orgId, $app, $d['permission']['module'], $d['permission']['state']
                            )
                        )
                    ) {
                        return $app !== null ? $this->route('/' . \strtolower($app) . '/e403', $csrf, $verb) : $this->route('/e403', $csrf, $verb);
                    }

                    $bound[] = ['dest' => $d['dest']];
                }
            }
        }

        return $bound;
    }
}
