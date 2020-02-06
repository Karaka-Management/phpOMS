<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   TBD
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Matrix\Matrix;

/**
 * Regression class.
 *
 * @package phpOMS\Math\Statistic\Forecast\Regression
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class MultipleLinearRegression extends RegressionAbstract
{
    /**
     * Get linear regression based on scatter plot.
     *
     * @latex y = b_{0} + b_{1} \cdot x
     *
     * @param array<array<int|float>> $x Obersved x values
     * @param array<array<int|float>> $y Observed y values
     *
     * @return array [b0 => ?, b1 => ?]
     *
     * @since 1.0.0
     */
    public static function getRegression(array $x, array $y) : array
    {
        $X = new Matrix(\count($x), \count($x[0]));
        $X->setMatrix($x);
        $XT = $X->transpose();

        $Y = new Matrix(\count($y));
        $Y->setMatrix($y);

        return $XT->mult($X)->inverse()->mult($XT)->mult($Y)->getMatrix();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSlope(float $b1, float $y, float $x) : float
    {
        return 0.0;
    }

    /**
     * {@inheritdoc}
     */
    public static function getElasticity(float $b1, float $y, float $x): float
    {
        return 0.0;
    }
}
