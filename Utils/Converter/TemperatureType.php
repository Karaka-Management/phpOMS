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
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

use phpOMS\Datatypes\Enum;

/**
 * Temperature type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class TemperatureType extends Enum
{
    /* public */ const CELSIUS = 'celsius';
    /* public */ const FAHRENHEIT = 'fahrenheit';
    /* public */ const KELVIN = 'kelvin';
    /* public */ const REAUMUR = 'reaumur';
    /* public */ const RANKINE = 'rankine';
    /* public */ const DELISLE = 'delisle';
    /* public */ const NEWTON = 'newton';
    /* public */ const ROMER = 'romer';
}
