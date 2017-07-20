<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic\Forecast\Regression;

/**
 * Regression class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class LevelLevelRegression extends RegressionAbstract
{
    public static function getSlope(float $b1, float $y, float $x) : float
    {
        return $b1;
    }

    public static function getElasticity(float $b1, float $y, float $x): float
    {
        return $b1 * $y / $x;
    }
}