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

use phpOMS\Datatypes\Enum;

/**
 * Route verb enum.
 *
 * @category   Framework
 * @package    phpOMS\Router
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class RouteVerb extends Enum
{
    /* public */ const GET = 1;
    /* public */ const PUT = 2;
    /* public */ const SET = 4;
    /* public */ const DELETE = 8;
    /* public */ const ANY = 16;
}
