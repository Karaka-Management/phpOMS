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

use phpOMS\Math\Matrix\IdentityMatrix;
use phpOMS\Math\Matrix\Matrix;

/**
 * Kernels.
 *
 * @package phpOMS\Math\Topology
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class KernelsND
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
     * Gaussian kernel
     *
     * @param array<float|int> $distances  Distances
     * @param array<float|int> $bandwidths Bandwidths
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function gaussianKernel(array $distances, array $bandwidths) : array
    {
        $dim = \count($bandwidths);

        $bandwidthMatrix = Matrix::fromArray($bandwidths);
        $distanceMatrix  = Matrix::fromArray($distances);
        $identityMatrix  = new IdentityMatrix($dim);

        $cov = $bandwidthMatrix->mult($identityMatrix);

        /** @phpstan-ignore-next-line */
        $exponent = $distanceMatrix->mult($cov->inverse())->mult($distanceMatrix)->sum(1)->mult(-0.5);

        return $exponent->exp()->mult((1 / \pow(2 * \M_PI, $dim / 2)) * \pow($cov->det(), 0.5))->matrix;
    }
}
