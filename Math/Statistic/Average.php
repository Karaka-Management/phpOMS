<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Statistic
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

use phpOMS\Math\Exception\ZeroDivisionException;
use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Average class.
 *
 * @package phpOMS\Math\Statistic
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Average
{
    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MA3 = [1 / 3, 1 / 3, 1 / 3];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MA5 = [0.2, 0.2, 0.2, 0.2, 0.2];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MAS15 = [-0.009, -0.019, -0.016, 0.009, 2 / 3, 0.144, 0.209, 0.231, 0.209, 0.144, 2 / 3, 0.009, -0.016, -0.019, -0.009];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MAS21 = [-0.003, -0.009, -0.014, -0.014, -0.006, 0.017, 0.51, 0.37, 0.134, 0.163, 0.171, 0.163, 0.134, 0.37, 0.51, 0.017, -0.006, -0.014, -0.014, -0.009, -0.003];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MAH5 = [-0.73, 0.294, 0.558, 0.294, -0.73];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MAH9 = [-0.041, -0.01, 0.119, 0.267, 0.330, 0.267, 0.119, -0.010, -0.041];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MAH13 = [-0.019, -0.028, 0, 0.66, 0.147, 0.214, 0.240, 0.214, 0.147, 0.66, 0, -0.028, -0.019];

    /**
     * Moving average weights
     *
     * @var float[]
     * @since 1.0.0
     */
    public const MAH23 = [-0.004, -0.011, -0.016, -0.015, -0.005, 0.013, 0.039, 0.068, 0.097, 0.122, 0.138, 0.148, 0.138, 0.122, 0.097, 0.068, 0.039, 0.013, -0.005, -0.015, -0.016, -0.011, -0.004];

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
     * Average change.
     *
     * @param array<int, int|float> $x Dataset
     * @param int                   $h Future steps
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function averageDatasetChange(array $x, int $h = 1) : float
    {
        $count = \count($x);

        return $h * ($x[$count - 1] - $x[0]) / ($count - 1);
    }

    /**
     * Moving average of dataset (SMA)
     *
     * @param array<int, int|float> $x         Dataset
     * @param int                   $order     Periods to use for average
     * @param array<int, int|float> $weight    Weight for moving average
     * @param bool                  $symmetric Cyclic moving average
     *
     * @return float[] Moving average of data
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public static function totalMovingAverage(array $x, int $order, ?array $weight = null, bool $symmetric = false) : array
    {
        $periods = (int) ($order / ($symmetric ? 2 : 1));
        $count   = \count($x) - ($symmetric ? $periods : 0);
        $avg     = [];

        for ($i = $periods - 1; $i < $count; ++$i) {
            $avg[] = self::movingAverage($x, $i, $order, $weight, $symmetric);
        }

        return $avg;
    }

    /**
     * Moving average of element in dataset (SMA)
     *
     * @param array<int, int|float> $x         Dataset
     * @param int                   $t         Current period
     * @param int                   $order     Periods to use for average
     * @param array<int, int|float> $weight    Weight for moving average
     * @param bool                  $symmetric Cyclic moving average
     *
     * @return float Moving average
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public static function movingAverage(array $x, int $t, int $order, ?array $weight = null, bool $symmetric = false) : float
    {
        $periods = (int) ($order / ($symmetric ? 2 : 1));
        $count   = \count($x);

        if ($count < $t || $count < $periods || ($symmetric && $t + $periods >= $count)) {
            throw new \Exception('Periods');
        }

        $t += 2;
        $end   = $symmetric ? $t + $periods - 1 : $t - 1;
        $start = $t - 1 - $periods;

        if (!empty($weight)) {
            return self::weightedAverage(\array_slice($x, $start, $end - $start), \array_slice($weight, $start, $end - $start));
        } else {
            return self::arithmeticMean(\array_slice($x, $start, $end - $start));
        }
    }

    /**
     * Calculate weighted average.
     *
     * Example: ([1, 2, 3, 4], [0.25, 0.5, 0.125, 0.125])
     *
     * @param array<int, int|float> $values Values
     * @param array<int, int|float> $weight Weight for values
     *
     * @return float
     *
     * @throws InvalidDimensionException This exception is thrown in case both parameters have different array length
     *
     * @since 1.0.0
     */
    public static function weightedAverage(array $values, array $weight) : float
    {
        if (($count = \count($values)) !== \count($weight)) {
            throw new InvalidDimensionException(\count($values) . 'x' . \count($weight));
        }

        $avg = 0.0;

        for ($i = 0; $i < $count; ++$i) {
            $avg += $values[$i] * $weight[$i];
        }

        return $avg;
    }

    /**
     * Calculate the arithmetic mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @latex \mu = mean = \frac{1}{n}\sum_{i=1}^{n}a_i
     *
     * @param array<int, int|float> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDivisionException This exception is thrown if the values array is empty
     *
     * @since 1.0.0
     */
    public static function arithmeticMean(array $values, int $offset = 0) : float
    {
        $count = \count($values);
        if ($count <= $offset * 2) {
            throw new ZeroDivisionException();
        }

        if ($offset > 0) {
            \sort($values);
            $values = \array_slice($values, $offset, -$offset);
            $count -= $offset * 2;
        }

        return \array_sum($values) / $count;
    }

    /**
     * Calculate the mode.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, int|float> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function mode(array $values, int $offset = 0) : float
    {
        if ($offset > 0) {
            \sort($values);
            $values = \array_slice($values, $offset, -$offset);
        }

        $count = \array_count_values($values);
        $best  = \max($count);

        return (float) (\array_keys($count, $best)[0] ?? 0.0);
    }

    /**
     * Calculate the median.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, int|float> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function median(array $values, int $offset = 0) : float
    {
        \sort($values);

        if ($offset > 0) {
            $values = \array_slice($values, $offset, -$offset);
        }

        $count     = \count($values);
        $middleval = (int) \floor(($count - 1) / 2);

        if ($count % 2 !== 0) {
            $median = $values[$middleval];
        } else {
            $low    = $values[$middleval];
            $high   = $values[$middleval + 1];
            $median = ($low + $high) / 2;
        }

        return $median;
    }

    /**
     * Calculate the geometric mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, int|float> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDivisionException This exception is thrown if the values array is empty
     *
     * @since 1.0.0
     */
    public static function geometricMean(array $values, int $offset = 0) : float
    {
        $count = \count($values);
        if ($count <= $offset * 2) {
            throw new ZeroDivisionException();
        }

        if ($offset > 0) {
            \sort($values);
            $values = \array_slice($values, $offset, -$offset);
            $count -= $offset * 2;
        }

        return \pow(\array_product($values), 1 / $count);
    }

    /**
     * Calculate the harmonic mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, int|float> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDivisionException This exception is thrown if a value in the values array is 0 or if the values array is empty
     *
     * @since 1.0.0
     */
    public static function harmonicMean(array $values, int $offset = 0) : float
    {
        $count = \count($values);
        if ($count <= $offset * 2) {
            throw new ZeroDivisionException();
        }

        if ($offset > 0) {
            \sort($values);
            $values = \array_slice($values, $offset, -$offset);
            $count -= $offset * 2;
        }

        $sum = 0.0;
        foreach ($values as $value) {
            if ($value === 0) {
                throw new ZeroDivisionException();
            }

            $sum += 1 / $value;
        }

        return 1 / ($sum / $count);
    }

    /**
     * Calculate the angle mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, int|float> $angles Angles
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDivisionException
     *
     * @since 1.0.0
     */
    public static function angleMean(array $angles, int $offset = 0) : float
    {
        $count = \count($angles);
        if ($count <= $offset * 2) {
            throw new ZeroDivisionException();
        }

        if ($offset > 0) {
            \sort($angles);
            $angles = \array_slice($angles, $offset, -$offset);
            $count -= $offset * 2;
        }

        $y = 0;
        $x = 0;

        for ($i = 0; $i < $count; ++$i) {
            $x += \cos(\deg2rad($angles[$i]));
            $y += \sin(\deg2rad($angles[$i]));
        }

        $x /= $count;
        $y /= $count;

        return \rad2deg(\atan2($y, $x));
    }

    /**
     * Calculate the angle mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, int|float> $angles Angles
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDivisionException
     *
     * @since 1.0.0
     */
    public static function angleMean2(array $angles, int $offset = 0) : float
    {
        $count = \count($angles);
        if ($count <= $offset * 2) {
            throw new ZeroDivisionException();
        }

        if ($offset > 0) {
            \sort($angles);
            $angles = \array_slice($angles, $offset, -$offset);
            $count -= $offset * 2;
        }

        $sins = 0.0;
        $coss = 0.0;

        foreach ($angles as $a) {
            $sins += \sin(\deg2rad($a));
            $coss += \cos(\deg2rad($a));
        }

        $avgsin = $sins / (0.0 + $count);
        $avgcos = $coss / (0.0 + $count);
        $avgang = \rad2deg(\atan2($avgsin, $avgcos));

        while ($avgang < 0.0) {
            $avgang += 360.0;
        }

        return $avgang;
    }
}
