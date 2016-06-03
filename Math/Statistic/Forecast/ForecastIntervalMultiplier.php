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
namespace phpOMS\Math\Statistic\Forecast;
use phpOMS\Datatypes\Enum;

/**
 * Address type enum.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ForecastIntervalMultiplier extends Enum
{
    const P_50 = 0.67;
    const P_55 = 0.76;
    const P_60 = 0.84;
    const P_65 = 0.93;
    const P_70 = 1.04;
    const P_75 = 1.15;
    const P_80 = 1.28;
    const P_85 = 1.44;
    const P_90 = 1.64;
    const P_95 = 1.96;
    const P_96 = 2.05;
    const P_97 = 2.17;
    const P_98 = 2.33;
    const P_99 = 2.58;
}
