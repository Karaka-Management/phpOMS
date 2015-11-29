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
namespace phpOMS\Localization;

use phpOMS\Datatypes\EnumArray;

/**
 * Datetime ISO format.
 *
 * Careful only (1) is considered as the ISO8601 standard. This file is only supposed to
 * contain all plausible datetime strings.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ISO8601EnumArray extends EnumArray
{
    protected static $constants = [
        1 => 'YYYY-MM-DD hh:mm:ss', // ietf: rfc3339
        2 => 'YYYY.MM.DD hh:mm:ss',
        3 => 'DD-MM-YYYY hh:mm:ss',
        4 => 'DD.MM.YYYY hh:mm:ss',
    ];
}
