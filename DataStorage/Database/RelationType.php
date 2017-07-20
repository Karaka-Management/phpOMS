<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\Datatypes\Enum;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class RelationType extends Enum
{
    /* public */ const NONE = 0;
    /* public */ const NEWEST = 1;
    /* public */ const BELONGS_TO = 2;
    /* public */ const OWNS_ONE = 4;
    /* public */ const HAS_MANY = 8;
    /* public */ const ALL = 16;
    /* public */ const REFERENCE = 32;
}
