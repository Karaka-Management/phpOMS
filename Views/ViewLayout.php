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
namespace phpOMS\Views;

use phpOMS\Datatypes\Enum;

/**
 * View layout enum.
 *
 * @category   Framework
 * @package    phpOMS\Socket
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ViewLayout extends Enum
{
    const UNDEFINED = -1;
    const VALUE = 0;
    const HEAD = 1;
    const GLOBAL = 2;
    const HEADER = 3;
    const MAIN = 4;
    const FOOTER = 5;
    const SIDE = 6;
    const FUNC = 7;
    const CLOSURE = 8; // TODO: this could be very dangerous
    const OBJECT = 9;
    const NULL = 10;
}
