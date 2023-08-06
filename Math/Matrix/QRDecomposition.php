<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Matrix
 * @copyright Dennis Eichhorn
 * @copyright JAMA - https://math.nist.gov/javanumerics/jama/
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

use phpOMS\Math\Geometry\Shape\D2\Triangle;
use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * QR decomposition
 *
 * For every matrix A = Q*R
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class QRDecomposition
{
    /**
     * QR matrix.
     *
     * @var array[]
     * @since 1.0.0
     */
    private array $QR = [];

    /**
     * Dimension m
     *
     * @var int
     * @since 1.0.0
     */
    private int $m = 0;

    /**
     * Dimension n
     *
     * @var int
     * @since 1.0.0
     */
    private int $n = 0;

    /**
     * R diagonal
     *
     * @var array<int, int|float>
     * @since 1.0.0
     */
    private array $Rdiag = [];

    /**
     * Constructor.
     *
     * @param Matrix $M Matrix
     *
     * @since 1.0.0
     */
    public function __construct(Matrix $M)
    {
        // Initialize.
        $this->QR = $M->toArray();
        $this->m  = $M->getM();
        $this->n  = $M->getN();

        // Main loop.
        for ($k = 0; $k < $this->n; ++$k) {
            // Compute 2-norm of k-th column without under/overflow.
            $nrm = 0.0;
            for ($i = $k; $i < $this->m; ++$i) {
                $nrm = Triangle::getHypot($nrm, $this->QR[$i][$k]);
            }

            if ($nrm != 0) {
                // Form k-th Householder vector.
                if ($this->QR[$k][$k] < 0) {
                    $nrm = -$nrm;
                }

                for ($i = $k; $i < $this->m; ++$i) {
                    $this->QR[$i][$k] /= $nrm;
                }

                $this->QR[$k][$k] += 1.0;

                // Apply transformation to remaining columns.
                for ($j = $k + 1; $j < $this->n; ++$j) {
                    $s = 0.0;
                    for ($i = $k; $i < $this->m; ++$i) {
                        $s += $this->QR[$i][$k] * $this->QR[$i][$j];
                    }

                    $s = -$s / $this->QR[$k][$k];
                    for ($i = $k; $i < $this->m; ++$i) {
                        $this->QR[$i][$j] += $s * $this->QR[$i][$k];
                    }
                }
            }

            $this->Rdiag[$k] = -$nrm;
        }
    }

    /**
     * Matrix has full rank
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isFullRank() : bool
    {
        for ($j = 0; $j < $this->n; ++$j) {
            if (\abs($this->Rdiag[$j]) < Matrix::EPSILON) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get R matrix
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getR() : Matrix
    {
        $R = [[]];

        for ($i = 0; $i < $this->n; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                if ($i < $j) {
                    $R[$i][$j] = $this->QR[$i][$j];
                } elseif ($i === $j) {
                    $R[$i][$j] = $this->Rdiag[$i];
                } else {
                    $R[$i][$j] = 0.0;
                }
            }
        }

        $matrix = new Matrix();
        $matrix->setMatrix($R);

        return $matrix;
    }

    /**
     * Get Q matrix
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getQ() : Matrix
    {
        $Q = [[]];

        for ($k = $this->n - 1; $k >= 0; --$k) {
            for ($i = 0; $i < $this->m; ++$i) {
                $Q[$i][$k] = 0.0;
            }

            $Q[$k][$k] = 1.0;
            for ($j = $k; $j < $this->n; ++$j) {
                if ($this->QR[$k][$k] != 0) {
                    $s = 0.0;
                    for ($i = $k; $i < $this->m; ++$i) {
                        $s += $this->QR[$i][$k] * $Q[$i][$j];
                    }

                    $s = -$s / $this->QR[$k][$k];
                    for ($i = $k; $i < $this->m; ++$i) {
                        $Q[$i][$j] += $s * $this->QR[$i][$k];
                    }
                }
            }
        }

        $matrix = new Matrix();
        $matrix->setMatrix($Q);

        return $matrix;
    }

    /**
     * Solve Ax = b
     *
     * @param Matrix $B Matrix
     *
     * @return Matrix
     *
     * @throws InvalidDimensionException
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public function solve(Matrix $B) : Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new InvalidDimensionException($B->getM());
        }

        if (!$this->isFullRank()) {
            throw new \Exception('Rank');
        }

        $nx = $B->getN();
        $X  = $B->toArray();

        // Compute Y = transpose(Q)*B
        for ($k = 0; $k < $this->n; ++$k) {
            for ($j = 0; $j < $nx; ++$j) {
                $s = 0.0;
                for ($i = $k; $i < $this->m; ++$i) {
                    $s += $this->QR[$i][$k] * $X[$i][$j];
                }

                $s = -$s / $this->QR[$k][$k];
                for ($i = $k; $i < $this->m; ++$i) {
                    $X[$i][$j] += $s * $this->QR[$i][$k];
                }
            }
        }

        // Solve R*X = Y;
        for ($k = $this->n - 1; $k >= 0; --$k) {
            for ($j = 0; $j < $nx; ++$j) {
                $X[$k][$j] /= $this->Rdiag[$k];
            }

            for ($i = 0; $i < $k; ++$i) {
                for ($j = 0; $j < $nx; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->QR[$i][$k];
                }
            }
        }

        $matrix = new Matrix();
        $matrix->setMatrix($X);

        return $matrix->getSubMatrix(0, $this->n - 1, 0, $nx - 1);
    }
}
