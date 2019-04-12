<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Router
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Message\RequestAbstract;
use phpOMS\Message\Http\Request;
use phpOMS\Uri\Http;

/**
 * Router class.
 *
 * @package    phpOMS\Router
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Router
{

    /**
     * Routes.
     *
     * @var array
     * @since 1.0.0
     */
    private $routes = [];

    /**
     * Add routes from file.
     *
     * Files need to return a php array of the following structure:
     * return [
     *      '{REGEX_PATH}' => [
     *          'dest' => '{DESTINATION_NAMESPACE:method}', // can also be static by using :: between namespace and functio name
     *          'verb' => RouteVerb::{VERB},
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
     * @since  1.0.0
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
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function add(string $route, $destination, int $verb = RouteVerb::GET) : void
    {
        if (!isset($this->routes[$route])) {
            $this->routes[$route] = [];
        }

        $this->routes[$route][] = [
            'dest' => $destination,
            'verb' => $verb,
        ];
    }

    /**
     * Route request.
     *
     * @param RequestAbstract $request Request to route
     * @param int             $verb    Route verb
     *
     * @return array[]
     *
     * @since  1.0.0
     */
    public function route(RequestAbstract $request, int $verb = RouteVerb::GET, string $app = null, int $orgId = null, $account = null) : array
    {
        $bound = [];
        $uri   = $request->getUri()->getRoute();
        $csrf  = $request->getData('CSRF');

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
                    if (isset($d['csrf']) && $csrf === null) {
                        // todo: replace http request and http uri with general request and http
                        \array_merge($bound, $this->route(new Request(new Http('/' . $app . '/e403')), $verb));

                        continue;
                    }

                    // if permission check is invalid
                    if ((isset($d['permission']) && $account === null)
                        || (isset($d['permission'])
                            && !$account->hasPermission($d['permission']['type'], $orgId, $app, $d['permission']['module'], $d['permission']['state']))
                    ) {
                        // todo: replace http request and http uri with general request and http
                        \array_merge($bound, $this->route(new Request(new Http('/' . $app . '/e403')), $verb));

                        continue;
                    }

                    $bound[] = ['dest' => $d['dest']];
                }
            }
        }

        return $bound;
    }
}
