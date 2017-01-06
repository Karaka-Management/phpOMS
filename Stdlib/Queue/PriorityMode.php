<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
namespace phpOMS\Stdlib\Queue;

use phpOMS\Datatypes\Enum;

/**
 * Account type enum.
 *
 * @category   Framework
 * @package    phpOMS\Stdlib
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class PriorityMode extends Enum
{
    /* public */ const FIFO = 0;
    /* public */ const LIFO = 0;
    /* public */ const EARLIEST_DEADLINE = 0;
    /* public */ const SHORTEST_JOB = 0;
    /* public */ const HIGHEST = 0;
    /* public */ const LOWEST = 0;
}
