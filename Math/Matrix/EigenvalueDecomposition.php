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
     * Complex scalar division
     *
     * @var float
     * @since 1.0.0
     */
    private $cdivr = 0.0;

    /**
     * Complex scalar division
     *
     * @var float
     * @since 1.0.0
     */
    private $cdivi = 0.0;

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

        for ($j = 0; ($j < $this->m) & $this->isSymmetric; ++$j) {
            for ($i = 0; ($i < $this->m) & $this->isSymmetric; ++$i) {
                $this->isSymmetric = ($this->A[$i][$j] === $this->A[$j][$i]);
            }
        }

        if ($this->isSymmetric) {
            for ($i = 0; $i < $this->m; ++$i) {
                for ($j = 0; $j < $this->m; ++$j) {
                    $this->V[$i][$j] = $this->A[$i][$j];
                }
            }

            $this->tred2();
            $this->tql2();
        } else {
            for ($j = 0; $j < $this->m; ++$j) {
                for ($i = 0; $i < $this->m; ++$i) {
                    $this->H[$i][$j] = $this->A[$i][$j];
                }
            }

            $this->orthes();
            $this->hqr2();
        }
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
                $g = $f > 0 ? -\sqrt($h) : \sqrt($h);

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
                    $r = $p > 0 ? -Triangle::getHypot($p, 1) : Triangle::getHypot($p, 1);

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
                            $h                   = $this->V[$k][$i + 1];
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
        $low  = 0;
        $high = $this->m - 1;

        for ($m = $low + 1; $m < $high - 1; ++$m) {
            $scale = 0.0;

            for ($i = $m; $i <= $high; ++$i) {
                $scale += \abs($this->H[$i][$m - 1]);
            }

            if ($scale !== 0.0) {
                $h = 0.0;
                for ($i = $high; $i >= $m; --$i) {
                    $this->ort[$i] = $this->H[$i][$m - 1] / $scale;
                    $h            += $this->ort[$i] * $this->ort[$i];
                }

                $g              = $this->ort[$m] > 0 ? -sqrt($h) : sqrt($h);
                $h             -= $this->ort[$m] * $g;
                $this->ort[$m] -= $g;

                for ($j = $m; $j < $this->m; ++$j) {
                    $f = 0.0;
                    for ($i = $high; $i >= $m; --$i) {
                        $f += $this->ort[$i] * $this->H[$i][$j];
                    }

                    $f /= $h;
                    for ($i = $m; $i <= $high; ++$i) {
                        $this->H[$i][$j] -= $f * $this->ort[$i];
                    }
                }

                for ($i = 0; $i <= $high; ++$i) {
                    $f = 0.0;
                    for ($j = $high; $j >= $m; --$j) {
                        $f += $this->ort[$j] * $this->H[$i][$j];
                    }

                    $f /= $h;
                    for ($j = $m; $j <= $high; ++$j) {
                        $this->H[$i][$j] -= $f * $this->ort[$j];
                    }
                }

                $this->ort[$m]      *= $scale;
                $this->H[$m][$m - 1] = $scale * $g;
            }
        }

        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = 0; $j < $this->m; ++$j) {
                $this->V[$i][$j] = ($i === $j) ? 1.0 : 0.0;
            }
        }

        for ($m = $high - 1; $m >= $low + 1; --$m) {
            if ($this->H[$m][$m - 1] !== 0.0) {
                for ($i = $m + 1; $i <= $high; ++$i) {
                    $this->ort[$i] = $this->H[$i][$m - 1];
                }

                for ($j = $m; $j <= $high; ++$j) {
                    $g = 0.0;
                    for ($i = $m; $i <= $high; ++$i) {
                        $g += $this->ort[$i] * $this->V[$i][$j];
                    }

                    $g = ($g / $this->ort[$m]) / $this->H[$m][$m - 1];
                    for ($i = $m; $i <= $high; ++$i) {
                        $this->V[$i][$j] += $g * $this->ort[$i];
                    }
                }
            }
        }
    }

    private function cdiv(float $xr, float $xi, float $yr, float $yi) : void
    {
        $r = 0.0;
        $d = 0.0;

        if (abs($yr) > \abs($yi)) {
            $r = $yi / $yr;
            $d = $yr + $r * $yi;

            $this->cdivr = ($xr + $r * $xi) / $d;
            $this->cdivi = ($xi + $r * $xr) / $d;
        } else {
            $r = $yr / $yi;
            $d = $yi + $r * $yr;

            $this->cdivr = ($r * $xr + $xi) / $d;
            $this->cdivi = ($r * $xi - $xr) / $d;
        }
    }

    private function hqr2() : void
    {
        $nn      = $this->m;
        $n       = $nn - 1;
        $low     = 0;
        $high    = $nn - 1;
        $eps     = 0.0001;
        $exshift = 0.0;
        $p       = 0;
        $q       = 0;
        $r       = 0;
        $s       = 0;
        $z       = 0;
        $t       = 0;
        $w       = 0;
        $x       = 0;
        $y       = 0;
        $norm    = 0.0;

        for ($i = 0; $i < $nn; ++$i) {
            if ($i < $low | $i > $high) {
                $this->D[$i] = $this->H[$i][$i];
                $this->E[$i] = 0.0;
            }

            for ($j = max($i - 1, 0); $j < $nn; ++$j) {
                $norm += \abs($this->H[$i][$j]);
            }
        }

        $iter = 0;
        while ($n >= $low) {
            $l = $n;
            while ($l > $low) {
                $s = \abs($this->H[$l - 1][$l - 1]) + \abs($this->H[$l][$l]);
                if ($s === 0.0) {
                    $s = $norm;
                }

                if (abs($this->H[$l][$l - 1]) < $eps * $s) {
                    break;
                }

                --$l;
            }

            if ($l === $n) {
                $this->H[$n][$n] = $this->H[$n][$n] + $exshift;
                $this->D[$n]     = $this->H[$n][$n];
                $this->E[$n]     = 0.0;
                $iter            = 0;

                --$n;
            } elseif ($l === $n - 1) {
                $w = $this->H[$n][$n - 1] * $this->H[$n - 1][$n];
                $p = ($this->H[$n - 1][$n - 1] - $this->H[$n][$n]) / 2.0;
                $q = $p * $p + $w;
                $z = sqrt(abs($q));

                $this->H[$n][$n]         += $exshift;
                $this->H[$n - 1][$n - 1] += $exshift;

                $x = $this->H[$n][$n];

                if ($q >= 0) {
                    $z               = $p >= 0 ? $p + $z : $p - $z;
                    $this->D[$n - 1] = $x + $z;
                    $this->D[$n]     = $z !== 0.0 ? $x - $w / $z : $this->D[$n - 1];
                    $this->E[$n - 1] = 0.0;
                    $this->E[$n]     = 0.0;

                    $x = $this->H[$n][$n - 1];
                    $s = \abs($x) + \abs($z);
                    $p = $x / $s;
                    $q = $z / $s;
                    $r = sqrt($p * $p + $q * $q);
                    $p = $p / $r;
                    $q = $q / $r;

                    for ($j = $n - 1; $j < $nn; ++$j) {
                        $z                   = $this->H[$n - 1][$j];
                        $this->H[$n - 1][$j] = $q * $z + $p * $this->H[$n][$j];
                        $this->H[$n][$j]     = $q * $this->H[$n][$j] - $p * $z;
                    }

                    for ($i = 0; $i <= $n; ++$i) {
                        $z                   = $this->H[$i][$n - 1];
                        $this->H[$i][$n - 1] = $q * $z + $p * $this->H[$i][$n];
                        $this->H[$i][$n]     = $q * $this->H[$i][$n] - $p * $z;
                    }

                    for ($i = $low; $i <= $high; ++$i) {
                        $z                   = $this->V[$i][$n - 1];
                        $this->V[$i][$n - 1] = $q * $z * $this->V[$i][$n];
                        $this->V[$i][$n]     = $q * $this->V[$i][$n] - $p * $z;
                    }
                } else {
                    $this->D[$n - 1] = $x + $p;
                    $this->D[$n]     = $x + $p;
                    $this->E[$n - 1] = $z;
                    $this->E[$n]     = -$z;
                }

                $n   -= 2;
                $iter = 0;
            } else {
                $x = $this->H[$n][$n];
                $y = 0.0;
                $w = 0.0;

                if ($l < $n) {
                    $y = $this->H[$n - 1][$n - 1];
                    $w = $this->H[$n][$n - 1] * $this->H[$n - 1][$n];
                }

                if ($iter === 10) {
                    $exshift += $x;
                    for ($i = $low; $i <= $n; ++$i) {
                        $this->H[$i][$i] -= $x;
                    }

                    $s = \abs($this->H[$n][$n - 1]) + \abs($this->H[$n - 1][$n - 2]);
                    $x = 0.75 * $s;
                    $y = $x;
                    $w = -0.4375 * $s * $s;
                }

                if ($iter === 30) {
                    $s = ($y - $x) / 2.0;
                    $s = $s * $s + $w;

                    if ($s > 0) {
                        $s = $y < $x ? -\sqrt($s) : \sqrt($s);
                        $s = $x - $w / (($y - $x) / 2.0 + $s);

                        for ($i = $low; $i <= $n; ++$i) {
                            $this->H[$i][$i] -= $s;
                        }

                        $exshift += $s;
                        $x        = $y = $w = 0.964;
                    }
                }

                ++$iter;
                $m = $n - 2;

                while ($m >= 1) {
                    $z = $this->H[$m][$m];
                    $r = $x - $z;
                    $s = $y - $z;
                    $p = ($r * $s - $w) / $this->H[$m + 1][$m] + $this->H[$m][$m + 1];
                    $q = $this->H[$m + 1][$m + 1] - $z - $r - $s;
                    $r = $this->H[$m + 2][$m + 1];
                    $s = \abs($p) + \abs($q) + \abs($r);
                    $p = $p / $s;
                    $q = $q / $s;
                    $r = $r / $s;

                    if ($m === $l
                        || \abs($this->H[$m][$m - 1]) * (\abs($q) + \abs($r)) < $eps * (\abs($p) * (\abs($this->H[$m - 1][$m - 1] + \abs($z) + \abs($this->H[$m + 1][$m + 1]))))
                    ) {
                        break;
                    }
                    
                    --$m;
                }

                for ($i = $m + 2; $i <= $n; ++$i) {
                    $this->H[$i][$i - 2] = 0.0;

                    if ($i > $m + 2) {
                        $this->H[$i][$i - 3] = 0.0;
                    }
                }

                
            }
        }
    }

    public function isSymmetric() : bool
    {
        return $this->isSymmetric;
    }

    public function getV() : Matrix 
    {
        $matrix = new Matrix();
        $matrix->setMatrix($this->V);

        return $matrix;
    }

    public function getRealEigenvalues() : array
    {
        return $this->D;
    }

    public function getImagEigenvalues() : array
    {
        return $this->E;
    }

    public function getD() : Matrix
    {
        $matrix = new Matrix();

        $D = [[]];
        for ($i = 0; $i < $this->m; ++$i) {
            for ($j = 0; $j < $this->m; ++$j) {
                $D[$i][$j] = 0.0;
            }

            $D[$i][$i] = $this->D[$i];
            if ($this->E[$i] > 0) {
                $D[$i][$i + 1] = $this->E[$i];
            } elseif ($this->E[$i] < 0) {
                $D[$i][$i - 1] = $this->E[$i];
            }
        }

        $matrix->setMatrix($D);

        return $matrix;
    }
}
