<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Router;

use phpOMS\Message\RequestAbstract;

/**
 * Router class.
 *
 * @category   Framework
 * @package    phpOMS\Router
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Router
{

    /**
     * Routes.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    private $routes = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Add routes from file.
     *
     * @param string $path Route file path
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function importFromFile(string $path) : bool
    {
        if(file_exists($path)) {
            /** @noinspection PhpIncludeInspection */
            $this->routes += include $path;

            return true;
        }

        return false;
    }

    /**
     * Add route.
     *
     * @param string $route       Route regex
     * @param mixed  $destination Destination e.g. Module:function & verb
     * @param string $verb        Request verb
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add(string $route, $destination, string $verb = RouteVerb::GET)
    {
        $this->routes[$route][] = [
            'dest' => $destination,
            'verb' => $verb,
        ];
    }

    /**
     * Route request.
     *
     * @param RequestAbstract $request Request to route
     *
     * @return string[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function route(RequestAbstract $request) : array
    {
        $bound = [];
        foreach ($this->routes as $route => $destination) {
            foreach ($destination as $d) {
                if ($this->match($route, $d['verb'], $request->getUri(), $request->getRouteVerb())) {
                    $bound[] = ['dest' => $d['dest']];
                }
            }
        }

        return $bound;
    }

    /**
     * Match route and uri.
     *
     * @param string $route      Route
     * @param string $routeVerb  GET,POST for this route
     * @param string $uri        Uri
     * @param string $remoteVerb Verb this request is using
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function match(string $route, string $routeVerb, string $uri, string $remoteVerb = RouteVerb::GET) : bool
    {
        return (bool) preg_match('~^' . $route . '$~', $uri) && ($routeVerb == RouteVerb::ANY || $remoteVerb == $routeVerb);
    }
}
