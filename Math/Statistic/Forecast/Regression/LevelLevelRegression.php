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

/**
 * Regression class.
 *
 * @package phpOMS\Math\Statistic\Forecast\Regression
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class LevelLevelRegression extends RegressionAbstract
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

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
