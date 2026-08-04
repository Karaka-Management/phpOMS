<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Topology
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Topology;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Metrics.
 *
 * @package phpOMS\Math\Topology
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class MetricsND
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Manhatten metric.
     *
     * @latex d(p, q) = \sum_{n=1}^N{|p_i - q_i|}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function manhattan(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = 0.0;
        foreach ($a as $key => $e) {
            $dist += \abs($e - $b[$key]);
        }

        return $dist;
    }

    /**
     * Euclidean metric.
     *
     * @latex d(p, q) = \sqrt{\sum_{n=1}^N{(p_i - q_i)^2}}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function euclidean(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = 0.0;
        foreach ($a as $key => $e) {
            $dist += \abs($e - $b[$key]) ** 2;
        }

        return \sqrt($dist);
    }

    /**
     * Cosine metric.
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function cosine(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dotProduct = 0;
        foreach ($a as $id => $_) {
            $dotProduct += $a[$id] * $b[$id];
        }

        $sumOfSquares = 0;
        foreach ($a as $value) {
            $sumOfSquares += $value * $value;
        }
        $magnitude1 = \sqrt($sumOfSquares);

        $sumOfSquares = 0;
        foreach ($b as $value) {
            $sumOfSquares += $value * $value;
        }
        $magnitude2 = \sqrt($sumOfSquares);

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return \PHP_FLOAT_MAX;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }

    /**
     * Chebyshev metric.
     *
     * @latex d(p, q) = \max_i{(|p_i - q_i|)}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function chebyshev(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = [];
        foreach ($a as $key => $e) {
            $dist[] = \abs($e - $b[$key]);
        }

        return (float) \max($dist);
    }

    /**
     * Minkowski metric.
     *
     * @latex d(p, q) = \sqrt[\lambda]{\sum_{n=1}^N{|p_i - q_i|^\lambda}}
     *
     * @param array<int|string, int|float> $a      n-D array
     * @param array<int|string, int|float> $b      n-D array
     * @param int                          $lambda Lambda
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function minkowski(array $a, array $b, int $lambda) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = 0.0;
        foreach ($a as $key => $e) {
            $dist += \pow(\abs($e - $b[$key]), $lambda);
        }

        return \pow($dist, 1 / $lambda);
    }

    /**
     * Canberra metric.
     *
     * @latex d(p, q) = \sum_{n=1}^N{\frac{|p_i - q_i|}{|p_i| + |q_i|}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function canberra(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = 0.0;
        foreach ($a as $key => $e) {
            $dist += \abs($e - $b[$key]) / (\abs($e) + \abs($b[$key]));
        }

        return $dist;
    }

    /**
     * Bray Curtis metric.
     *
     * @latex d(p, q) = \frac{\sum_{n=1}^N{|p_i - q_i|}}{\sum_{n=1}^N{(p_i + q_i)}}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function brayCurtis(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $distTop    = 0.0;
        $distBottom = 0.0;
        foreach ($a as $key => $e) {
            $distTop    += \abs($e - $b[$key]);
            $distBottom += $e + $b[$key];
        }

        return $distTop / $distBottom;
    }

    /**
     * Angular separation metric.
     *
     * @latex d(p, q) = \frac{\sum_{n=1}^N{p_i * q_i}}{\left(\sum_{n=1}^N{p_i^2} * \sum_{n=1}^N{q_i^2}\right)^\frac{1}{2}}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return float
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function angularSeparation(array $a, array $b) : float
    {
        if (\count($a) > \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $distTop     = 0.0;
        $distBottomA = 0.0;
        $distBottomB = 0.0;
        foreach ($a as $key => $e) {
            $distTop     += $e * $b[$key];
            $distBottomA += $e ** 2;
            $distBottomB += $b[$key] ** 2;
        }

        return $distTop / \pow($distBottomA * $distBottomB, 1 / 2);
    }

    /**
     * Hamming metric.
     *
     * @latex d(p, q) = \sum_{n=1}^N{|p_i - q_i|}
     *
     * @param array<int|string, int|float> $a n-D array
     * @param array<int|string, int|float> $b n-D array
     *
     * @return int
     *
     * @throws InvalidDimensionException
     *
     * @since 1.0.0
     */
    public static function hamming(array $a, array $b) : int
    {
        if (($size = \count($a)) !== \count($b)) {
            throw new InvalidDimensionException(\count($a) . 'x' . \count($b));
        }

        $dist = 0;
        for ($i = 0; $i < $size; ++$i) {
            if ($a[$i] !== $b[$i]) {
                ++$dist;
            }
        }

        return $dist;
    }
}
