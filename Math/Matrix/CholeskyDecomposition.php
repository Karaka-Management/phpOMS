<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Matrix
 * @copyright Dennis Eichhorn
 * @copyright JAMA - https://math.nist.gov/javanumerics/jama/
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Cholesky decomposition
 *
 * A is syymetric, positive definite then A = L*L'
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class CholeskyDecomposition
{
    /**
     * L matrix.
     *
     * @var array
     * @since 1.0.0
     */
    private array $L = [];

    /**
     * Dimension of L
     *
     * @var int
     * @since 1.0.0
     */
    private int $m = 0;

    /**
     * Is symmetric positiv definite
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isSpd = true;

    /**
     * Constructor.
     *
     * @param Matrix $M Matrix
     *
     * @since 1.0.0
     */
    public function __construct(Matrix $M)
    {
        $this->L = $M->toArray();
        $this->m = $M->getM();

        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = $i; $j < $this->m; ++$j) {
                for ($sum = $this->L[$i][$j], $k = $i - 1; $k >= 0; --$k) {
                    $sum -= $this->L[$i][$k] * $this->L[$j][$k];
                }

                if ($i === $j) {
                    if ($sum >= 0) {
                        $this->L[$i][$i] = \sqrt($sum);
                    } else {
                        $this->isSpd = false;
                    }
                } else {
                    if ($this->L[$i][$i] !== 0) {
                        $this->L[$j][$i] = $sum / $this->L[$i][$i];
                    }
                }
            }

            for ($k = $i + 1; $k < $this->m; ++$k) {
                $this->L[$i][$k] = 0.0;
            }
        }
    }

    /**
     * Is matrix symmetric positiv definite.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isSpd() : bool
    {
        return $this->isSpd;
    }

    /**
     * Get L matrix
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getL() : Matrix
    {
        $matrix = new Matrix();
        $matrix->setMatrix($this->L);

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
            throw new InvalidDimensionException((string) $B->getM());
        }

        if (!$this->isSpd) {
            throw new \Exception();
        }

        $X = $B->toArray();
        $n = $B->getN();

        // Solve L*Y = B;
        for ($k = 0; $k < $this->m; ++$k) {
            for ($j = 0; $j < $n; ++$j) {
                for ($i = 0; $i < $k; ++$i) {
                    $X[$k][$j] -= $X[$i][$j] * $this->L[$k][$i];
                }

                $X[$k][$j] /= $this->L[$k][$k];
            }
        }

        // Solve L'*X = Y;
        for ($k = $this->m - 1; $k >= 0; --$k) {
            for ($j = 0; $j < $n; ++$j) {
                for ($i = $k + 1; $i < $this->m; ++$i) {
                    $X[$k][$j] -= $X[$i][$j] * $this->L[$i][$k];
                }

                $X[$k][$j] /= $this->L[$k][$k];
            }
        }

        $solution = new Matrix();
        $solution->setMatrix($X);

        return $solution;
    }
}
