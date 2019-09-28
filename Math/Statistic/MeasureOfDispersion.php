<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Statistic
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

use phpOMS\Math\Exception\ZeroDevisionException;
use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Measure of dispersion.
 *
 * @package phpOMS\Math\Statistic
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class MeasureOfDispersion
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
     * Get range.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array<int, float|int> $values Values
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function range(array $values) : float
    {
        \sort($values);
        $end   = \end($values);
        $start = \reset($values);

        return $end - $start;
    }

    /**
     * Calculage empirical variation coefficient.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array<int, float|int> $values Values
     * @param float                 $mean   Mean
     *
     * @return float
     *
     * @throws ZeroDevisionException This exception is thrown if the mean is 0
     *
     * @since 1.0.0
     */
    public static function empiricalVariationCoefficient(array $values, float $mean = null) : float
    {
        $mean = $mean !== null ? $mean : Average::arithmeticMean($values);

        if ($mean === 0.0) {
            throw new ZeroDevisionException();
        }

        return self::standardDeviation($values) / $mean;
    }

    /**
     * Calculage standard deviation.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @latex \sigma = \sqrt{\sigma^{2}} = \sqrt{Var(X)}
     *
     * @param array<int, float|int> $values Values
     * @param float                 $mean   Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function standardDeviation(array $values, float $mean = null) : float
    {
        $mean = $mean !== null ? $mean : Average::arithmeticMean($values);
        $sum  = 0.0;

        foreach ($values as $value) {
            $sum += ($value - $mean) ** 2;
        }

        return \sqrt($sum / (\count($values) - 1));
    }

    /**
     * Calculage sample variance.
     *
     * Similar to `empiricalVariance`.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @latex \sigma^{2} = Var(X) = \frac{1}{N - 1} \sum_{i = 1}^{N}\left(x_{i} - \bar{X}\right)^{2}
     *
     * @param array<int, float|int> $values Values
     * @param float                 $mean   Mean
     *
     * @return float
     *
     * @throws ZeroDevisionException This exception is thrown if the size of the values array is less than 2
     *
     * @since 1.0.0
     */
    public static function sampleVariance(array $values, float $mean = null) : float
    {
        $count = \count($values);

        if ($count < 2) {
            throw new ZeroDevisionException();
        }

        return self::empiricalVariance($values, [], $mean) * $count / ($count - 1);
    }

    /**
     * Calculage empirical variance.
     *
     * Similar to `sampleVariance`.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @latex \sigma^{2} = Var(X) = \frac{1}{N} \sum_{i = 1}^{N}\left(x_{i} - \bar{X}\right)^{2}
     *
     * @param array<int, float|int> $values        Values
     * @param array<int, float|int> $probabilities Probabilities
     * @param float                 $mean          Mean
     *
     * @return float
     *
     * @throws ZeroDevisionException This exception is thrown if the values array is empty
     *
     * @since 1.0.0
     */
    public static function empiricalVariance(array $values, array $probabilities = [], float $mean = null) : float
    {
        $count          = \count($values);
        $hasProbability = !empty($probabilities);

        if ($count === 0) {
            throw new ZeroDevisionException();
        }

        $mean = $hasProbability ? Average::weightedAverage($values, $probabilities) : ($mean !== null ? $mean : Average::arithmeticMean($values));
        $sum  = 0;

        foreach ($values as $key => $value) {
            $sum += ($hasProbability ? $probabilities[$key] : 1) * ($value - $mean) ** 2;
        }

        return $hasProbability ? $sum : $sum / $count;
    }

    /**
     * Calculage empirical covariance.
     *
     * Example: ([4, 5, 9, 1, 3], [4, 5, 9, 1, 3])
     *
     * @latex \sigma_{XY} = cov(X, Y) = \sum_{i = 1}^{N}\frac{\left(x_{i} - \bar{X}\right) \left(y_{i} - \bar{Y}\right)}{N - 1}
     *
     * @param array<int, float|int> $x     Values
     * @param array<int, float|int> $y     Values
     * @param float                 $meanX Mean
     * @param float                 $meanY Mean
     *
     * @return float
     *
     * @throws ZeroDevisionException     This exception is thrown if the size of the x array is less than 2
     * @throws InvalidDimensionException This exception is thrown if x and y have different dimensions
     *
     * @since 1.0.0
     */
    public static function empiricalCovariance(array $x, array $y, float $meanX = null, float $meanY = null) : float
    {
        $count = \count($x);

        if ($count < 2) {
            throw new ZeroDevisionException();
        }

        if ($count !== \count($y)) {
            throw new InvalidDimensionException($count . 'x' . \count($y));
        }

        $xMean = $meanX !== null ? $meanX : Average::arithmeticMean($x);
        $yMean = $meanY !== null ? $meanY : Average::arithmeticMean($y);

        $sum = 0.0;

        for ($i = 0; $i < $count; ++$i) {
            $sum += ($x[$i] - $xMean) * ($y[$i] - $yMean);
        }

        return $sum / ($count - 1);
    }

    /**
     * Get interquartile range.
     *
     * @param array<int, float|int> $x Dataset
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getIQR(array $x) : float
    {
        $count = \count($x);

        if ($count % 2 !== 0) {
            --$count;
        }

        /** @var int $count */
        $count /= 2;

        \sort($x);

        $Q1 = Average::median(\array_slice($x, 0, $count));
        $Q3 = Average::median(\array_slice($x, -$count, $count));

        return $Q3 - $Q1;
    }

    /**
     * Get mean deviation.
     *
     * @param array<int, float|int> $x    Values
     * @param float                 $mean Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function meanDeviation(array $x, float $mean = null) : float
    {
        $mean = $mean !== null ? $mean : Average::arithmeticMean($x);
        $sum  = 0.0;

        foreach ($x as $xi) {
            $sum += ($xi - $mean);
        }

        return $sum / \count($x);
    }

    /**
     * Get mean absolute deviation.
     *
     * @param array<int, float|int> $x    Values
     * @param float                 $mean Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function meanAbsoluteDeviation(array $x, float $mean = null) : float
    {
        $mean = $mean !== null ? $mean : Average::arithmeticMean($x);
        $sum  = 0.0;

        foreach ($x as $xi) {
            $sum += \abs($xi - $mean);
        }

        return $sum / \count($x);
    }

    /**
     * Get squared mean deviation.
     *
     * @param array<int, float|int> $x    Values
     * @param float                 $mean Mean
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function squaredMeanDeviation(array $x, float $mean = null) : float
    {
        $mean = $mean !== null ? $mean : Average::arithmeticMean($x);
        $sum  = 0.0;

        foreach ($x as $xi) {
            $sum += ($xi - $mean) ** 2;
        }

        return $sum / \count($x);
    }
}
