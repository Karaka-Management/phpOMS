<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Statistic\Forecast\Regression
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic\Forecast\Regression;

/**
 * Regression class.
 *
 * @package phpOMS\Math\Statistic\Forecast\Regression
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class LevelLevelRegression extends RegressionAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function getSlope(float $b1, float $y, float $x) : float
    {
        return $b1;
    }

    /**
     * {@inheritdoc}
     */
    public static function getElasticity(float $b1, float $y, float $x) : float
    {
        return $b1 * $x / $y;
    }
}
