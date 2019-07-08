<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Statistic
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Statistic;

use phpOMS\Math\Exception\ZeroDevisionException;
use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Average class.
 *
 * @package    phpOMS\Math\Statistic
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class Average
{

    public const MA3    = [1 / 3, 1 / 3];
    public const MA5    = [0.2, 0.2, 0.2];
    public const MA2X12 = [5 / 6, 5 / 6, 5 / 6, 5 / 6, 5 / 6, 5 / 6, 0.42];
    public const MA3X3  = [1 / 3, 2 / 9, 1 / 9];
    public const MA3X5  = [0.2, 0.2, 2 / 15, 4 / 6];
    public const MAS15  = [0.231, 0.209, 0.144, 2 / 3, 0.009, -0.016, -0.019, -0.009];
    public const MAS21  = [0.171, 0.163, 0.134, 0.37, 0.51, 0.017, -0.006, -0.014, -0.014, -0.009, -0.003];
    public const MAH5   = [0.558, 0.294, -0.73];
    public const MAH9   = [0.330, 0.267, 0.119, -0.010, -0.041];
    public const MAH13  = [0.240, 0.214, 0.147, 0.66, 0, -0.028, -0.019];
    public const MAH23  = [0.148, 0.138, 0.122, 0.097, 0.068, 0.039, 0.013, -0.005, -0.015, -0.016, -0.011, -0.004];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {

    }

    /**
     * Average change.
     *
     * @param array<int, float|int> $x Dataset
     * @param int                   $h Future steps
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function averageDatasetChange(array $x, int $h = 1) : float
    {
        $count = \count($x);

        return $h * ($x[$count - 1] - $x[0]) / ($count - 1);
    }

    /**
     * Moving average of dataset (SMA)
     *
     * @param array<int, float|int> $x         Dataset
     * @param int                   $order     Periods to use for average
     * @param array<int, float|int> $weight    Weight for moving average
     * @param bool                  $symmetric Cyclic moving average
     *
     * @return array Moving average of data
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function totalMovingAverage(array $x, int $order, array $weight = null, bool $symmetric = false) : array
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
     * @param array<int, float|int> $x         Dataset
     * @param int                   $t         Current period
     * @param int                   $order     Periods to use for average
     * @param array<int, float|int> $weight    Weight for moving average
     * @param bool                  $symmetric Cyclic moving average
     *
     * @return float Moving average
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function movingAverage(array $x, int $t, int $order, array $weight = null, bool $symmetric = false) : float
    {
        $periods = (int) ($order / ($symmetric ? 2 : 1));
        $count   = \count($x);

        if ($count < $t || $count < $periods || ($symmetric && $t + $periods >= $count)) {
            throw new \Exception('Periods');
        }

        $t    += 2;
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
     * @param array<int, float|int> $values Values
     * @param array<int, float|int> $weight Weight for values
     *
     * @return float
     *
     * @throws InvalidDimensionException This exception is thrown in case both parameters have different array length
     *
     * @since  1.0.0
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
     * @param array<int, float|int> $values Values
     *
     * @return float
     *
     * @throws ZeroDevisionException This exception is thrown if the values array is empty
     *
     * @since  1.0.0
     */
    public static function arithmeticMean(array $values) : float
    {
        $count = \count($values);

        if ($count === 0) {
            throw new ZeroDevisionException();
        }

        return \array_sum($values) / $count;
    }

    /**
     * Calculate the mode.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, float|int> $values Values
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function mode(array $values) : float
    {
        $count = \array_count_values($values);
        $best  = \max($count);

        return (float) (\array_keys($count, $best)[0] ?? 0.0);
    }

    /**
     * Calculate the median.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, float|int> $values Values
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function median(array $values) : float
    {
        \sort($values);
        $count     = \count($values);
        $middleval = (int) \floor(($count - 1) / 2);

        if ($count % 2) {
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
     * @param array<int, float|int> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDevisionException This exception is thrown if the values array is empty
     *
     * @since  1.0.0
     */
    public static function geometricMean(array $values, int $offset = 0) : float
    {
        $count = \count($values);

        if ($count === 0) {
            throw new ZeroDevisionException();
        }

        return \pow(\array_product($values), 1 / $count);
    }

    /**
     * Calculate the harmonic mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, float|int> $values Values
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @throws ZeroDevisionException This exception is thrown if a value in the values array is 0 or if the values array is empty
     *
     * @since  1.0.0
     */
    public static function harmonicMean(array $values, int $offset = 0) : float
    {
        \sort($values);

        if ($offset > 0) {
            $values = \array_slice($values, $offset, -$offset);
        }

        $count = \count($values);
        $sum   = 0.0;

        if ($count === 0) {
            throw new ZeroDevisionException();
        }

        foreach ($values as $value) {
            if ($value === 0) {
                throw new ZeroDevisionException();
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
     * @param array<int, float|int> $angles Angles
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function angleMean($angles, int $offset = 0) : float
    {
        $y    = 0;
        $x    = 0;
        $size = \count($angles);

        for ($i = 0; $i < $size; ++$i) {
            $x += \cos(\deg2rad($angles[$i]));
            $y += \sin(\deg2rad($angles[$i]));
        }

        $x /= $size;
        $y /= $size;

        return \rad2deg(\atan2($y, $x));
    }

    /**
     * Calculate the angle mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array<int, float|int> $angles Angles
     * @param int                   $offset Offset for outlier
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function angleMean2(array $angles, int $offset = 0) : float
    {
        \sort($angles);

        if ($offset > 0) {
            $angles = \array_slice($angles, $offset, -$offset);
        }

        $sins = 0.0;
        $coss = 0.0;

        foreach ($angles as $a) {
            $sins += \sin(\deg2rad($a));
            $coss += \cos(\deg2rad($a));
        }

        $avgsin = $sins / (0.0 + \count($angles));
        $avgcos = $coss / (0.0 + \count($angles));
        $avgang = \rad2deg(\atan2($avgsin, $avgcos));

        while ($avgang < 0.0) {
            $avgang += 360.0;
        }

        return $avgang;
    }
}
