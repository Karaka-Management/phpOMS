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
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Math\Finance\Forecasting\ExponentialSmoothing;

use phpOMS\Datatypes\Enum;

/**
 * Smoothing enum.
 *
 * @category   Framework
 * @package    phpOMS\Html
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class SeasonalType extends Enum
{
    /* public */ const ALL = 0;
    /* public */ const NONE = 1;
    /* public */ const ADDITIVE = 2;
    /* public */ const MULTIPLICATIVE = 4;
}