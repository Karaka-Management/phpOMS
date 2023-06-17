<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Statistic\Forecast\Regression
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Regression class.
 *
 * @package phpOMS\Math\Statistic\Forecast\Regression
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class LevelLogRegression extends RegressionAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function getRegression(array $x, array $y) : array
    {
        if (($c = \count($x)) !== \count($y)) {
            throw new InvalidDimensionException($c . 'x' . \count($y));
        }

        for ($i = 0; $i < $c; ++$i) {
            $x[$i] = \log($x[$i]);
        }

        return parent::getRegression($x, $y);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSlope(float $b1, float $y, float $x) : float
    {
        return $b1 / $x;
    }

    /**
     * {@inheritdoc}
     */
    public static function getElasticity(float $b1, float $y, float $x) : float
    {
        return $b1 / $y;
    }
}
