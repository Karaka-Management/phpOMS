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
 * Area type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class AreaType extends Enum
{
    const SQUARE_FEET = 'ft';
    const SQUARE_METERS = 'm';
    const SQUARE_KILOMETERS = 'km';
    const SQUARE_MILES = 'mi';
    const SQUARE_YARDS = 'yd';
    const SQUARE_INCHES = 'in';
    const SQUARE_MICROINCHES = 'muin';
    const SQUARE_CENTIMETERS = 'cm';
    const SQUARE_MILIMETERS = 'mm';
    const SQUARE_MICROMETERS = 'micron';
    const SQUARE_DECIMETERS = 'dm';
    const HECTARES = 'ha';
    const ACRES = 'ac';
}
