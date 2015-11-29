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

    /**
     * Add route.
     *
     * @param \string $route       Route regex
     * @param \string $destination Destination e.g. Module:function & method
     * @param \string $method      Request method
     * @param \int    $type        Result type
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add(\string $route, \string $destination, \string $method = RequestMethod::GET, \int $type = ViewLayout::MAIN)
    {
        $this->routes[$route][] = [
            'dest'   => $destination,
            'method' => $method,
            'type'   => $type,
        ];
    }

    /**
     * Is route regex.
     *
     * @param \string $route Route regex
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function isValid(\string $route) : \bool
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        if (@preg_match($route, null) === false) {
            return false;
        }

        return true;
    }

    /**
     * Route uri.
     *
     * @param \string $uri          Uri to route
     * @param \string $remoteMethod GET/POST etc.
     *
     * @return \string[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function route(\string $uri, \string $remoteMethod) : array
    {
        $bound = [];
        foreach ($this->routes as $route => $destination) {
            foreach ($destination as $d) {
                if ($this->match($route, $d['method'], $uri, $remoteMethod)) {
                    $bound[$route][] = ['dest' => $d['dest'], 'type' => $d['type']];
                }
            }
        }

        return $bound;
    }

    /**
     * Match route and uri.
     *
     * @param \string $route        Route
     * @param \string $method       GET,POST for this route
     * @param \string $uri          Uri
     * @param \string $remoteMethod Method this request is using
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function match(\string $route, \string $method, \string $uri, \string $remoteMethod = RequestMethod::GET) : \bool
    {
        return (bool) preg_match('~^' . $route . '$~', $uri) && ($method == 'any' || $remoteMethod == $method);
    }
}
