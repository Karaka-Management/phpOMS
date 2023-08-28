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

use phpOMS\Math\Matrix\IdentityMatrix;
use phpOMS\Math\Matrix\Matrix;

/**
 * Metrics.
 *
 * @package phpOMS\Math\Topology
 * @license OMS License 2.0
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

    public static function gaussianKernel(array $distances, array $bandwidths) : array
    {
        $dim = \count($bandwidths);

        $bandwithMatrix = Matrix::fromArray($bandwidths);
        $distnaceMatrix = Matrix::fromArray($distances);
        $identityMatrix = new IdentityMatrix($dim);

        $cov = $bandwithMatrix->mult($identityMatrix);

        $exponent = $distnaceMatrix->dot($cov->inverse())->mult($distnaceMatrix)->sum(1)->mult(-0.5);

        return $exponent->exp()->mult((1 / \pow(2 * \M_PI, $dim / 2)) * \pow($cov->det(), 0.5))->matrix;
    }
}
