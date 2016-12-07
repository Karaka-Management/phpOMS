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
namespace phpOMS\Utils\Converter;

use phpOMS\Datatypes\Enum;

/**
 * Speed type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class EnergyPowerType extends Enum
{
    /* public */ const KILOWATT_HOUERS = 'kWh';
    /* public */ const MEGAWATT_HOUERS = 'MWh';
    /* public */ const KILOTONS = 'kt';
    /* public */ const JOULS = 'J';
    /* public */ const CALORIES = 'Cal';
    /* public */ const BTU = 'BTU';
    /* public */ const KILOJOULS = 'kJ';
    /* public */ const THERMEC = 'thmEC';
    /* public */ const NEWTON_METERS = 'Nm';
}
