<?php
/**
 * Karaka
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

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * LU decomposition
 *
 * A(piv,:) = L*U
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class LUDecomposition
{
    /**
     * LU matrix.
     *
     * @var array
     * @since 1.0.0
     */
    private array $LU = [];

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
     * Pivot sign
     *
     * @var int
     * @since 1.0.0
     */
    private int $pivSign = 1;

    /**
     * Pivot
     *
     * @var array
     * @since 1.0.0
     */
    private array $piv = [];

    /**
     * Constructor.
     *
     * @param Matrix $M Matrix
     *
     * @since 1.0.0
     */
    public function __construct(Matrix $M)
    {
        $this->LU = $M->toArray();
        $this->m  = $M->getM();
        $this->n  = $M->getN();

        for ($i = 0; $i < $this->m; ++$i) {
            $this->piv[$i] = $i;
        }

        $this->pivSign = 1;
        $LUrowi        = $LUcolj = [];

        for ($j = 0; $j < $this->n; ++$j) {
            for ($i = 0; $i < $this->m; ++$i) {
                $LUcolj[$i] = &$this->LU[$i][$j];
            }

            for ($i = 0; $i < $this->m; ++$i) {
                $LUrowi = $this->LU[$i];
                $kmax   = \min($i, $j);
                $s      = 0.0;

                for ($k = 0; $k < $kmax; ++$k) {
                    $s += $LUrowi[$k] * $LUcolj[$k];
                }
                $LUrowi[$j] = $LUcolj[$i] -= $s;
            }

            $p = $j;
            for ($i = $j + 1; $i < $this->m; ++$i) {
                if (\abs($LUcolj[$i]) > \abs($LUcolj[$p])) {
                    $p = $i;
                }
            }

            if ($p !== $j) {
                for ($k = 0; $k < $this->n; ++$k) {
                    $t                = $this->LU[$p][$k];
                    $this->LU[$p][$k] = $this->LU[$j][$k];
                    $this->LU[$j][$k] = $t;
                }

                $k              = $this->piv[$p];
                $this->piv[$p]  = $this->piv[$j];
                $this->piv[$j]  = $k;
                $this->pivSign *= -1;
            }

            if (($j < $this->m) && ($this->LU[$j][$j] != 0.0)) {
                for ($i = $j + 1; $i < $this->m; ++$i) {
                    $this->LU[$i][$j] /= $this->LU[$j][$j];
                }
            }
        }
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
        $L = [[]];

        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                if ($i > $j) {
                    $L[$i][$j] = $this->LU[$i][$j];
                } elseif ($i === $j) {
                    $L[$i][$j] = 1.0;
                } else {
                    $L[$i][$j] = 0.0;
                }
            }
        }

        $matrix = new Matrix();
        $matrix->setMatrix($L);

        return $matrix;
    }

    /**
     * Get U matrix
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function getU() : Matrix
    {
        $U = [[]];

        for ($i = 0; $i < $this->n; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                $U[$i][$j] = $i <= $j ? $this->LU[$i][$j] ?? 0 : 0.0;
            }
        }

        $matrix = new Matrix();
        $matrix->setMatrix($U);

        return $matrix;
    }

    /**
     * Get pivot
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getPivot() : array
    {
        return $this->piv;
    }

    /**
     * Is matrix nonsingular
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isNonsingular() : bool
    {
        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->LU[$j][$j] == 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get determinant
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function det() : float
    {
        $d = $this->pivSign;
        for ($j = 0; $j < $this->n; ++$j) {
            $d *= $this->LU[$j][$j];
        }

        return (float) $d;
    }

    /**
     * Solve Ax = b
     *
     * @param Matrix $B Matrix
     *
     * @return Matrix
     *
     * @since 1.0.0
     */
    public function solve(Matrix $B) : Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new InvalidDimensionException((string) $B->getM());
        }

        if (!$this->isNonsingular()) {
            throw new \Exception();
        }

        $n = $B->getN();
        $X = $B->getSubMatrixByRows($this->piv, 0, $n - 1)->toArray();

        // Solve L*Y = B(piv,:)
        for ($k = 0; $k < $this->n; ++$k) {
            for ($i = $k + 1; $i < $this->n; ++$i) {
                for ($j = 0; $j < $n; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->LU[$i][$k];
                }
            }
        }

        // Solve U*X = Y;
        for ($k = $this->n - 1; $k >= 0; --$k) {
            for ($j = 0; $j < $n; ++$j) {
                $X[$k][$j] /= $this->LU[$k][$k];
            }
            for ($i = 0; $i < $k; ++$i) {
                for ($j = 0; $j < $n; ++$j) {
                    $X[$i][$j] -= $X[$k][$j] * $this->LU[$i][$k];
                }
            }
        }

        $solution = new Matrix();
        $solution->setMatrix($X);

        return $solution;
    }
}
