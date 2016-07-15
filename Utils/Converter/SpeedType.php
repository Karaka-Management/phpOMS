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
abstract class SpeedType extends Enum
{
    const MILES_PER_DAY = 'mpd';
    const MILES_PER_HOUR = 'mph';
    const MILES_PER_MINUTE = 'mpm';
    const MILES_PER_SECOND = 'mps';
    const KILOMETERS_PER_DAY = 'kpd';
    const KILOMETERS_PER_HOUR = 'kph';
    const KILOMETERS_PER_MINUTE = 'kpm';
    const KILOMETERS_PER_SECOND = 'kps';
    const METERS_PER_DAY = 'md';
    const METERS_PER_HOUR = 'mh';
    const METERS_PER_MINUTE = 'mm';
    const METERS_PER_SECOND = 'ms';
    const CENTIMETERS_PER_DAY = 'cpd';
    const CENTIMETERS_PER_HOUR = 'cph';
    const CENTIMETERS_PER_MINUTES = 'cpm';
    const CENTIMETERS_PER_SECOND = 'cps';
    const MILLIMETERS_PER_DAY = 'mmpd';
    const MILLIMETERS_PER_HOUR = 'mmph';
    const MILLIMETERS_PER_MINUTE = 'mmpm';
    const MILLIMETERS_PER_SECOND = 'mmps';
    const YARDS_PER_DAY = 'ypd';
    const YARDS_PER_HOUR = 'yph';
    const YARDS_PER_MINUTE = 'ypm';
    const YARDS_PER_SECOND = 'yps';
    const INCHES_PER_DAY = 'ind';
    const INCHES_PER_HOUR = 'inh';
    const INCHES_PER_MINUTE = 'inm';
    const INCHES_PER_SECOND = 'ins';
    const FEET_PER_DAY = 'ftd';
    const FEET_PER_HOUR = 'fth';
    const FEET_PER_MINUTE = 'ftm';
    const FEET_PER_SECOND = 'fts';
    const MACH = 'mach';
    const KNOTS = 'knots';
}
