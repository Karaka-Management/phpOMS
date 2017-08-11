<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Views;

use phpOMS\Stdlib\Base\Enum;

/**
 * View layout enum.
 *
 * @category   Framework
 * @package    phpOMS\Socket
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ViewLayout extends Enum
{
    /* public */ const UNDEFINED = -1;
    /* public */ const VALUE = 0;
    /* public */ const HEAD = 1;
    /* public */ const GLOBAL = 2;
    /* public */ const HEADER = 3;
    /* public */ const MAIN = 4;
    /* public */ const FOOTER = 5;
    /* public */ const SIDE = 6;
    /* public */ const FUNC = 7;
    /* public */ const CLOSURE = 8; // TODO: this could be very dangerous
    /* public */ const OBJECT = 9;
    /* public */ const NULL = 10;
}
