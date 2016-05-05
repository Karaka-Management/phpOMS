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
namespace phpOMS\DataStorage\Database\Query;

use phpOMS\Datatypes\Enum;

/**
 * Query type enum.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class JoinType extends Enum
{
    const JOIN = 'JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';
    const LEFT_INNER_JOIN = 'LEFT INNER JOIN';
    const RIGHT_JOIN = 'RIGHT JOIN';
    const RIGHT_OUTER_JOIN = 'RIGHT OUTER JOIN';
    const RIGHT_INNER_JOIN = 'RIGHT INNER JOIN';
    const OUTER_JOIN = 'OUTER JOIN';
    const INNER_JOIN = 'INNER JOIN';
    const CROSS_JOIN = 'CROSS JOIN';
    const FULL_OUTER_JOIN = 'FULL OUTER JOIN';
}
