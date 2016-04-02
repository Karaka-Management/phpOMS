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

use phpOMS\Message\RequestMethod;
use phpOMS\Views\ViewLayout;

/**
 * Router class.
 *
 * @category   Framework
 * @package    phpOMS\Socket
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

    public function importFromFile(string $path) 
    {
        include $path;
        $this->routes = $appRoutes;
    }

    /**
     * Add route.
     *
     * @param string $route       Route regex
     * @param mixed $destination Destination e.g. Module:function & verb
     * @param string $verb      Request verb
     * @param int    $layout        Result layout
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add(string $route, $destination, string $verb = RouteVerb::GET)
    {
        $this->routes[$route][] = [
            'dest'   => $destination,
            'verb' => $verb,
        ];
    }

    /**
     * Route uri.
     *
     * @param string $uri          Uri to route
     * @param string $verb GET/POST etc.
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
                    $bound[$route][] = ['dest' => $d['dest'], 'type' => $d['type']];
                }
            }
        }

        return $bound;
    }

    /**
     * Match route and uri.
     *
     * @param string $route        Route
     * @param string $verb       GET,POST for this route
     * @param string $uri          Uri
     * @param string $verb Verb this request is using
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
