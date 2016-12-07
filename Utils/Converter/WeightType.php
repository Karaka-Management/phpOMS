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
    /* public */ const MICROGRAM = 'mg';
    /* public */ const MILLIGRAM = 'mug';
    /* public */ const GRAM = 'g';
    /* public */ const KILOGRAM = 'kg';
    /* public */ const METRIC_TONS = 't';
    /* public */ const POUNDS = 'lb';
    /* public */ const OUNCES = 'oz';
    /* public */ const STONES = 'st';
    /* public */ const GRAIN = 'gr';
    /* public */ const CARAT = 'ct';
    /* public */ const LONG_TONS = 'uk t';
    /* public */ const SHORT_TONS = 'us ton';
    /* public */ const TROY_POUNDS = 't lb';
    /* public */ const TROY_OUNCES = 't oz';
}
