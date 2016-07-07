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
 * Volume type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class VolumeType extends Enum
{
    const UK_GALLON = 'UK gal';
    const US_GALLON_LIQUID = 'US gal lqd';
    const US_GALLON_DRY = 'US gal dry';
    const UK_PINT = 'pt';
    const US_PINT_LIQUID = 'US pt lqd';
    const US_PINT_DRY = 'US pt dry';
    const US_QUARTS_LIQUID = 'US qt lqd';
    const US_QUARTS_DRY = 'US qt dry';
    const UK_QUARTS = 'US qt dry';
    const US_GILL = 'US gi';
    const UK_GILL = 'UK gi';
    const LITER = 'L';
    const MICROLITER = 'mul';
    const MILLILITER = 'mL';
    const CENTILITER = 'cl';
    const KILOLITER = 'kl';
    const UK_BARREL = 'UK bbl';
    const US_BARREL_DRY = 'US bbl dry';
    const US_BARREL_LIQUID = 'US bbl lqd';
    const US_BARREL_OIL = 'US bbl oil';
    const US_BARREL_FEDERAL = 'US bbl fed';
    const US_OUNCES = 'us fl oz';
    const UK_OUNCES = 'uk fl oz';
    const US_TEASPOON = 'US tsp';
    const UK_TEASPOON = 'UK tsp';
    const METRIC_TEASPOON = 'Metric tsp';
    const US_TABLESPOON = 'US tblsp';
    const UK_TABLESPOON = 'UK tblsp';
    const METRIC_TABLESPOON = 'Metric tblsp';
    const US_CUP = 'US cup';
    const CAN_CUP = 'Can cup';
    const METRIC_CUP = 'Metric cup';
    const CUBIC_CENTIMETER = 'cm';
    const CUBIC_MILLIMETER = 'mm';
    const CUBIC_METER = 'm';
    const CUBIC_INCH = 'in';
    const CUBIC_FEET = 'ft';
    const CUBIC_YARD = 'yd';
}
