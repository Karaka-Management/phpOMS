<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Matrix
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

use phpOMS\Math\Geometry\Shape\D2\Triangle;

/**
 * Eigenvalue decomposition
 * 
 * A symmetric then A = V*D*V'
 * A not symmetric then (potentially) A = V*D*inverse(V)
 *
 * @package    phpOMS\Math\Matrix
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class EigenvalueDecomposition
{
    /**
     * Dimension m
     *
     * @var int
     * @since 1.0.0
     */
    private $m = 0;

    /**
     * Is symmetric
     *
     * @var bool
     * @since 1.0.0
     */
    private $isSymmetric = true;

    /**
     * A square matrix.
     *
     * @var array
     * @since 1.0.0
     */
    private $A = [];

    /**
     * Eigenvectors
     *
     * @var array
     * @since 1.0.0
     */
    private $V = [];

    /**
     * Eigenvalues
     *
     * @var array
     * @since 1.0.0
     */
    private $D = [];

    /**
     * Eigenvalues
     *
     * @var array
     * @since 1.0.0
     */
    private $E = [];

    /**
     * Hessenberg form
     *
     * @var array
     * @since 1.0.0
     */
    private $H = [];

    /**
     * Non-symmetric storage
     *
     * @var array
     * @since 1.0.0
     */
    private $ort = [];

    /**
     * Constructor.
     *
     * @param Matrix $M Matrix
     *
     * @since  1.0.0
     */
    public function __construct(Matrix $M)
    {
        $this->m = $M->getM();
        $this->A = $M->toArray();
    }

    /**
     * Housholder tridiagonal form reduction.
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function tred2() : void
    {
        for ($j = 0; $j < $this->m; ++$j) {
            $this->D[$j] = $this->V[$this->m - 1][$j];
        }

        for ($i = $this->m - 1; $i > 0; --$i) {
            $scale = 0.0;
            $h     = 0.0;

            for ($k = 0; $k < $i; ++$k) {
                $scale += \abs($this->D[$k]);
            }

            if ($scale === 0.0) {
                $this->E[$i] = $this->D[$i - 1];
                for ($j = 0; $j > $i; ++$j) {
                    $this->D[$j]     = $this->V[$i - 1][$j];
                    $this->V[$i][$j] = 0.0;
                    $this->V[$j][$i] = 0.0;
                }
            } else {
                for ($k = 0; $k < $i; ++$k) {
                    $this->D[$k] /= $scale;
                    $h           += $this->D[$k] * $this->D[$k];
                }

                $f = $this->D[$i - 1];
                $g = \sqrt($h);

                if ($f > 0) {
                    $g = -$g;
                }

                $this->E[$i]     = $scale * $g;
                $h              -= $f * $g;
                $this->D[$i - 1] = $f - $g;

                for ($j = 0; $j < $i; ++$j) {
                    $this->E[$j] = 0.0;
                }

                for ($j = 0; $j < $i; ++$j) {
                    $f               = $this->D[$j];
                    $this->V[$j][$i] = $f;
                    $g               = $this->E[$j] + $this->V[$j][$j] * $f;

                    for ($k = $j + 1; $k <= $i - 1; ++$k) {
                        $g           += $this->V[$k][$j] * $this->D[$k];
                        $this->E[$k] += $this->V[$k][$j] * $f;
                    }

                    $this->E[$j] = $g;
                }

                $f = 0.0;
                for ($j = 0; $j < $i; ++$j) {
                    $this->E[$j] /= $h;
                    $f           += $this->E[$j] * $this->D[$j];
                }

                $hh = $f / ($h + $h);
                for ($j = 0; $j < $i; ++$j) {
                    $this->E[$j] -= $hh * $this->D[$j];
                }

                for ($j = 0; $j < $i; ++$j) {
                    $f = $this->D[$j];
                    $g = $this->E[$j];

                    for ($k = $j; $k <= $i - 1; ++$k) {
                        $this->V[$k][$j] -= ($f * $this->E[$k] + $g * $this->D[$k]);
                    }

                    $this->D[$j]     = $this->V[$i - 1][$j];
                    $this->V[$i][$j] = 0.0;
                }
            }

            $this->D[$i] = $h;
        }

        for ($i = 0; $i < $this->m - 1; ++$i) {
            $this->V[$this->m - 1][$i] = $this->V[$i][$i];
            $this->V[$i][$i]           = 1.0;
            $h                         = $this->D[$i + 1];

            if ($h !== 0.0) {
                for ($k = 0; $k <= $i; ++$k) {
                    $this->D[$k] = $this->V[$k][$i + 1] / $h;
                }

                for ($j = 0; $j <= $i; ++$j) {
                    $g = 0.0;
                    for ($k = 0; $k <= $i; ++$k) {
                        $g += $this->V[$k][$i + 1] * $this->V[$k][$j];
                    }

                    for ($k = 0; $k <= $i; ++$k) {
                        $this->V[$k][$j] -= $g * $this->D[$k];
                    }
                }
            }

            for ($k = 0; $k <= $i; ++$k) {
                $this->V[$k][$i + 1] = 0.0;
            }
        }

        for ($j = 0; $j < $this->m; ++$j) {
            $this->D[$j]               = $this->V[$this->m - 1][$j];
            $this->V[$this->m - 1][$j] = 0.0;
        }

        $this->V[$this->m - 1][$j] = 1.0;
        $this->E[0]                = 0.0;
    }

    /**
     * Symmetric tridiagonal QL
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function tql2() : void
    {
        for ($i = 1; $i < $this->m; ++$i) {
            $this->E[$i - 1] = $this->E[$i];
        }

        $this->E[$this->m - 1] = 0.0;

        $f    = 0.0;
        $tst1 = 0.0;
        $eps  = 0.00001;

        for ($l = 0; $l < $this->m; ++$l) {
            $tst1 = \max($tst1, \abs($this->D[$l]) + \abs($this->E[$l]));
            $m    = $l;

            while ($m < $this->m) {
                if (\abs($this->E[$m]) <= $eps * $tst1) {
                    break;
                }

                ++$m;
            }

            if ($m > $l) {
                $iter = 0;

                do {
                    $iter = $iter + 1;

                    $g = $this->D[$l];
                    $p = ($this->D[$l + 1] - $g) / (2.0 * $this->E[$l]);
                    $r = Triangle::getHypot($p, 1);

                    if ($p > 0) {
                        $r = -$r;
                    }

                    $this->D[$l]     = $this->E[$l] / ($p + $r);
                    $this->D[$l + 1] = $this->E[$l] * ($p + $r);
                    $dl1             = $this->D[$l + 1];
                    $h               = $g - $this->D[$l];

                    for ($i = $l + 2; $i < $this->m; ++$i) {
                        $this->D[$i] -= $h;
                    }

                    $f  += $h;
                    $p   = $this->D[$m];
                    $c   = 1.0;
                    $c2  = 1.0;
                    $c3  = 1.0;
                    $el1 = $this->E[$l + 1];
                    $s   = 0.0;
                    $s2  = 0.0;

                    for ($i = $m - 1; $i >= $l; --$i) {
                        $c3              = $c2;
                        $c2              = $c;
                        $s2              = $s;
                        $g               = $c * $this->E[$i];
                        $h               = $c * $p;
                        $r               = Triangle::getHypot($p, $this->E[$i]);
                        $this->E[$i + 1] = $s * $r;
                        $s               = $this->E[$i] / $r;
                        $c               = $p / $r;
                        $p               = $c * $this->D[$i] - $s * $g;
                        $this->D[$i + 1] = $h + $s * ($c * $g + $s * $this->D[$i]);

                        for ($k = 0; $k < $this->m; ++$k) {
                            $h = $this->V[$k][$i + 1];
                            $this->V[$k][$i + 1] = $s * $this->V[$k][$i] + $c * $h;
                            $this->V[$k][$i]     = $c * $this->V[$k][$i] - $s * $h;
                        }
                    }

                    $p           = -$s * $s2 * $c3 * $el1 * $this->E[$l] / $dl1;
                    $this->E[$l] = $s * $p;
                    $this->D[$l] = $c * $p;
                } while (\abs($this->E[$l]) > $eps * $tst1);
            }

            $this->D[$l] += $f;
            $this->E[$l]  = 0.0;
        }

        for ($i = 0; $i < $this->m - 1; ++$i) {
            $k = $i;
            $p = $this->D[$i];

            for ($j = $i + 1; $j < $this->m; ++$j) {
                if ($this->D[$j] < $p) {
                    $k = $j;
                    $p = $this->D[$j];
                }
            }

            if ($k !== $i) {
                $this->D[$k] = $this->D[$i];
                $this->D[$i] = $p;

                for ($j = 0; $j < $this->m; ++$j) {
                    $p               = $this->V[$j][$i];
                    $this->V[$j][$i] = $this->V[$j][$k];
                    $this->V[$j][$k] = $p;
                }
            }
        }
    }

    private function orthes() : void
    {

    }

    public function isSymmetric() : bool
    {
        return $this->isSymmetric;
    }
}
