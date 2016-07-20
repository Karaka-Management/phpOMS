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

namespace phpOMS\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\Forecast\ForecastIntervalMultiplier;
use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * Regression class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class LogLevelRegression extends RegressionAbstract
{
    /**
     * Get linear regression based on scatter plot.
     *
     * y = b0 + b1 * x
     *
     * @param array $x Obersved x values
     * @param array $y Observed y values
     *
     * @return array [b0 => ?, b1 => ?]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRegression(array $x, array $y) : array
    {
        if(($c = count($x)) != count($y)) {
            throw new \Exception('Dimension');
        }

        for($i = 0; $i < $c; $i++) {
            $y[$i] = log($y[i]);
        }

        return parent::getRegression($x, $y);
    }

    public static function getSlope(float $b1, float $y, float $x) : float {
        return $b1 * $y;
    }

    public static function getElasticity(float $b1, float $y, float $x): float {
        return $b1 * $x;
    }
    
}