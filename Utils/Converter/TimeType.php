<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Utils\Converter;

use phpOMS\Stdlib\Base\Enum;

/**
 * Time type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class TimeType extends Enum
{
    /* public */ const MILLISECONDS = 'ms';
    /* public */ const SECONDS = 's';
    /* public */ const MINUTES = 'i';
    /* public */ const HOURS = 'h';
    /* public */ const DAYS = 'd';
    /* public */ const WEEKS = 'w';
    /* public */ const MONTH = 'm';
    /* public */ const QUARTER = 'q';
    /* public */ const YEAR = 'y';
}
