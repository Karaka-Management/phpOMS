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
 * Length type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class LengthType extends Enum
{
    const MILES = 'mi';
    const METERS = 'm';
    const MICROMETER = 'micron';
    const CENTIMETERS = 'cm';
    const MILLIMETERS = 'mm';
    const KILOMETERS = 'km';
    const CHAINS = 'ch';
    const FEET = 'ft';
    const FURLONGS = 'fur';
    const MICROINCH = 'muin';
    const INCHES = 'in';
    const YARDS = 'yd';
    const PARSECS = 'pc';
    const UK_NAUTICAL_MILES = 'uk nmi';
    const US_NAUTICAL_MILES = 'us nmi';
    const UK_NAUTICAL_LEAGUES = 'uk nl';
    const NAUTICAL_LEAGUES = 'nl';
    const UK_LEAGUES = 'uk lg';
    const US_LEAGUES = 'us lg';
    const LIGHTYEARS = 'ly';
    const DECIMETERS = 'dm';
}
