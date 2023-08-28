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
 * Metrics.
 *
 * @package phpOMS\Math\Topology
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Kernels2D
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

    public static function uniformKernel(float $distance, float $bandwidth) : float
    {
        return \abs($distance) <= $bandwidth / 2
            ? 1 / $bandwidth
            : 0.0;
    }

    public static function triangularKernel(float $distance, float $bandwidth) : float
    {
        return \abs($distance) <= $bandwidth / 2
            ? 1 - abs($distance) / ($bandwidth / 2)
            : 0.0;
    }

    public static function epanechnikovKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth) {
            $u = \abs($distance) / $bandwidth;

            return 0.75 * (1 - $u * $u) / $bandwidth;
        } else {
            return 0.0;
        }
    }

    public static function quarticKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth) {
            $u = \abs($distance) / $bandwidth;

            return (15 / 16) * (1 - $u * $u) * (1 - $u * $u) / $bandwidth;
        } else {
            return 0.0;
        }
    }

    public static function triweightKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth) {
            $u = \abs($distance) / $bandwidth;

            return (35 / 32) * (1 - $u * $u) * (1 - $u * $u) * (1 - $u * $u) / $bandwidth;
        } else {
            return 0.0;
        }
    }

    public static function tricubeKernel(float $distance, float $bandwidth) : float
    {
        if (\abs($distance) <= $bandwidth) {
            $u = \abs($distance) / $bandwidth;

            return (70 / 81) * (1 - $u * $u * $u) * (1 - $u * $u * $u) * (1 - $u * $u * $u) / $bandwidth;
        } else {
            return 0.0;
        }
    }

    public static function gaussianKernel(float $distance, float $bandwidth) : float
    {
        return \exp(-($distance * $distance) / (2 * $bandwidth * $bandwidth)) / ($bandwidth * \sqrt(2 * \M_PI));
    }

    public static function cosineKernel(float $distance, float $bandwidth) : float
    {
        return \abs($distance) <= $bandwidth
            ? (\M_PI / 4) * \cos(\M_PI * $distance / (2 * $bandwidth)) / $bandwidth
            : 0.0;
    }

    public static function logisticKernel(float $distance, float $bandwidth) : float
    {
        return 1 / (\exp($distance / $bandwidth) + 2 + \exp(-$distance / $bandwidth));
    }
}
