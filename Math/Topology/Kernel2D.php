<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Topology
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Topology;

/**
 * Kernels.
 *
 * The bandwidth in the following functions is equivalent with 2 * sigma.
 *
 * @package phpOMS\Math\Topology
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Kernel2D
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
     * Uniform kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function uniformKernel(float $distance, float $bandwidth) : float
    {
        return \abs($distance) <= $bandwidth / 2
            ? 1 / $bandwidth
            : 0.0;
    }

    /**
     * Triangular kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function triangularKernel(float $distance, float $bandwidth) : float
    {
        return \abs($distance) <= $bandwidth / 2
            ? 1 - \abs($distance) / ($bandwidth / 2)
            : 0.0;
    }

    /**
     * Epanechnikov kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function epanechnikovKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth / 2) {
            $u = \abs($distance) / ($bandwidth / 2);

            return 0.75 * (1 - $u * $u) / ($bandwidth / 2);
        } else {
            return 0.0;
        }
    }

    /**
     * Quartic kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function quarticKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth / 2) {
            $u = $distance / ($bandwidth / 2);

            return (15 / 16) * (1 - $u * $u) * (1 - $u * $u) / ($bandwidth / 2);
        } else {
            return 0.0;
        }
    }

    /**
     * Triweight kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function triweightKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth / 2) {
            $u = $distance / ($bandwidth / 2);

            return (35 / 32) * (1 - $u * $u) * (1 - $u * $u) * (1 - $u * $u) / ($bandwidth / 2);
        } else {
            return 0.0;
        }
    }

    /**
     * Tricube kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function tricubeKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth / 2) {
            $u = \abs($distance) / ($bandwidth / 2);

            return (70 / 81) * (1 - $u * $u * $u) * (1 - $u * $u * $u) * (1 - $u * $u * $u) / ($bandwidth / 2);
        } else {
            return 0.0;
        }
    }

    /**
     * Gaussian kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function gaussianKernel(float $distance, float $bandwidth) : float
    {
        return \exp(-($distance * $distance) / (2 * $bandwidth * $bandwidth / 4)) / ($bandwidth / 2 * \sqrt(2 * \M_PI));
    }

    /**
     * Cosine kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function cosineKernel(float $distance, float $bandwidth) : float
    {
        return \abs($distance) <= $bandwidth / 2
            ? \M_PI / 4 * \cos(\M_PI / 2 * ($distance / ($bandwidth / 2)))
            : 0.0;
    }

    /**
     * Logistic kernel.
     *
     * @param float $distance  Distance
     * @param float $bandwidth Bandwidth
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function logisticKernel(float $distance, float $bandwidth) : float
    {
        return 1 / (\exp($distance / ($bandwidth / 2)) + 2 + \exp(-$distance / ($bandwidth / 2)));
    }
}
