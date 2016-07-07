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
 * Weight type enum.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class WeightType extends Enum
{
    const MICROGRAM = 'mg';
    const MILLIGRAM = 'mug';
    const GRAM = 'g';
    const KILOGRAM = 'kg';
    const METRIC_TONS = 't';
    const POUNDS = 'lb';
    const OUNCES = 'oz';
    const STONES = 'st';
    const GRAIN = 'gr';
    const CARAT = 'ct';
    const LONG_TONS = 'uk t';
    const SHORT_TONS = 'us ton';
    const TROY_POUNDS = 't lb';
    const TROY_OUNCES = 't oz';
}
