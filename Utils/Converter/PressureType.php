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
abstract class PressureType extends Enum
{
    const PASCALS = 'Pa';
    const BAR = 'bar';
    const POUND_PER_SQUARE_INCH = 'psi';
    const ATMOSPHERES = 'atm';
    const INCHES_OF_MERCURY = 'inHg';
    const INCHES_OF_WATER = 'inH20';
    const MILLIMETERS_OF_WATER = 'mmH2O';
    const MILLIMETERS_OF_MERCURY = 'mmHg';
    const MILLIBAR = 'mbar';
    const KILOGRAM_PER_SQUARE_METER = 'kg/m2';
    const NEWTONS_PER_METER_SQUARED = 'N/m2';
    const POUNDS_PER_SQUARE_FOOT = 'psf';
    const TORRS = 'Torr';
}
