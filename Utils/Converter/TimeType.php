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

namespace phpOMS\Utils\Converter;

use phpOMS\Datatypes\Enum;

/**
 * Time type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class TimeType extends Enum
{
    /* public */ const MILLISECONDS = 'ms';
    /* public */ const SECONDS = 's';
    /* public */ const MINUTES = 'm';
    /* public */ const HOURS = 'h';
    /* public */ const DAYS = 'd';
    /* public */ const WEEKS = 'w';
    /* public */ const MONTH = 'm';
    /* public */ const QUARTER = 'q';
    /* public */ const YEAR = 'y';
}
