<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Math\Matrix
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

use phpOMS\Math\Geometry\Shape\D2\Triangle;

/**
 * Singular value decomposition
 *
 * @package    phpOMS\Math\Matrix
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class SingularValueDecomposition
{
    /**
     * U matrix.
     *
     * @var array[]
     * @since 1.0.0
     */
    private $U = [];

    /**
     * V matrix.
     *
     * @var array[]
     * @since 1.0.0
     */
    private $V = [];

    /**
     * Singular values.
     *
     * @var array
     * @since 1.0.0
     */
    private $S = [];

    /**
     * Dimension m
     *
     * @var int
     * @since 1.0.0
     */
    private $m = 0;

    /**
     * Dimension n
     *
     * @var int
     * @since 1.0.0
     */
    private $n = 0;

    /**
     * Constructor.
     *
     * @param Matrix $M Matrix
     *
     * @since  1.0.0
     */
    public function __construct(Matrix $M)
    {
        $A       = $M->toArray();
        $this->m = $M->getM();
        $this->n = $M->getN();
        $nu      = \min($this->m, $this->n);
        $e       = [];
        $work    = [];
        $nct     = \min($this->m - 1, $this->n);
        $nrt     = \max(0, \min($this->n - 2, $this->m));
        $eps     = 0.00001;

        $maxNctNrt = \max($nct, $nrt);

        for ($k = 0; $k < $maxNctNrt; ++$k) {
            if ($k < $nct) {
                $this->S[$k] = 0;
                for ($i = $k; $i < $this->m; ++$i) {
                    $this->S[$k] = Triangle::getHypot($this->S[$k], $A[$i][$k]);
                }

                if ($this->S[$k] != 0) {
                    if ($A[$k][$k] < 0.0) {
                        $this->S[$k] = -$this->S[$k];
                    }

                    for ($i = $k; $i < $this->m; ++$i) {
                        $A[$i][$k] /= $this->S[$k];
                    }

                    $A[$k][$k] += 1.0;
                }

                $this->S[$k] = -$this->S[$k];
            }

            for ($j = $k + 1; $j < $this->n; ++$j) {
                if ($k < $nct && $this->S[$k] != 0) {
                    $t = 0;
                    for ($i = $k; $i < $this->m; ++$i) {
                        $t += $A[$i][$k] * $A[$i][$j];
                    }

                    $t = -$t / $A[$k][$k];
                    for ($i = $k; $i < $this->m; ++$i) {
                        $A[$i][$j] += $t * $A[$i][$k];
                    }

                    $e[$j] = $A[$k][$j];
                }
            }

            if ($k < $nct) {
                for ($i = $k; $i < $this->m; ++$i) {
                    $this->U[$i][$k] = $A[$i][$k];
                }
            }

            if ($k < $nrt) {
                $e[$k] = 0;
                for ($i = $k + 1; $i < $this->n; ++$i) {
                    $e[$k] = Triangle::getHypot($e[$k], $e[$i]);
                }

                if ($e[$k] != 0) {
                    if ($e[$k + 1] < 0.0) {
                        $e[$k] = -$e[$k];
                    }

                    for ($i = $k + 1; $i < $this->n; ++$i) {
                        $e[$i] /= $e[$k];
                    }

                    $e[$k + 1] += 1.0;
                }

                $e[$k] = -$e[$k];
                if ($k + 1 < $this->m && $e[$k] != 0) {
                    for ($i = $k + 1; $i < $this->m; ++$i) {
                        $work[$i] = 0.0;
                    }

                    for ($j = $k + 1; $j < $this->n; ++$j) {
                        for ($i = $k + 1; $i < $this->m; ++$i) {
                            $work[$i] += $e[$j] * $A[$i][$j];
                        }
                    }

                    for ($j = $k + 1; $j < $this->n; ++$j) {
                        $t = -$e[$j] / $e[$k + 1];
                        for ($i = $k + 1; $i < $this->m; ++$i) {
                            $A[$i][$j] += $t * $work[$i];
                        }
                    }
                }

                for ($i = $k + 1; $i < $this->n; ++$i) {
                    $this->V[$i][$k] = $e[$i];
                }
            }
        }

        $p = \min($this->n, $this->m + 1);
        if ($nct < $this->n) {
            $this->S[$nct] = $A[$nct][$nct];
        }

        if ($this->m < $p) {
            $this->S[$p - 1] = 0.0;
        }

        if ($nrt + 1 < $p) {
            $e[$nrt] = $A[$nrt][$p - 1];
        }

        $e[$p - 1] = 0.0;

        for ($j = $nct; $j < $nu; ++$j) {
            for ($i = 0; $i < $this->m; ++$i) {
                $this->U[$i][$j] = 0.0;
            }

            $this->U[$j][$j] = 1.0;
        }

        for ($k = $nct - 1; $k >= 0; --$k) {
            if ($this->S[$k] != 0) {
                for ($j = $k + 1; $j < $nu; ++$j) {
                    $t = 0;
                    for ($i = $k; $i < $this->m; ++$i) {
                        $t += $this->U[$i][$k] * $this->U[$i][$j];
                    }

                    $t = -$t / $this->U[$k][$k];
                    for ($i = $k; $i < $this->m; ++$i) {
                        $this->U[$i][$j] += $t * $this->U[$i][$k];
                    }
                }

                for ($i = $k; $i < $this->m; ++$i) {
                    $this->U[$i][$k] = -$this->U[$i][$k];
                }

                $this->U[$k][$k] += 1.0;
                for ($i = 0; $i < $k - 1; ++$i) {
                    $this->U[$i][$k] = 0.0;
                }
            } else {
                for ($i = 0; $i < $this->m; ++$i) {
                    $this->U[$i][$k] = 0.0;
                }

                $this->U[$k][$k] = 1.0;
            }
        }

        for ($k = $this->n - 1; $k >= 0; --$k) {
            if ($k < $nrt && $e[$k] != 0) {
                for ($j = $k + 1; $j < $nu; ++$j) {
                    $t = 0;
                    for ($i = $k + 1; $i < $this->n; ++$i) {
                        $t += $this->V[$i][$k] * $this->V[$i][$j];
                    }

                    $t = -$t / $this->V[$k + 1][$k];
                    for ($i = $k + 1; $i < $this->n; ++$i) {
                        $this->V[$i][$j] += $t * $this->V[$i][$k];
                    }
                }
            }

            for ($i = 0; $i < $this->n; ++$i) {
                $this->V[$i][$k] = 0.0;
            }

            $this->V[$k][$k] = 1.0;
        }

        $pp   = $p - 1;
        $iter = 0;

        while ($p > 0) {
            for ($k = $p - 2; $k >= -1; --$k) {
                if ($k === -1) {
                    break;
                } elseif (\abs($e[$k]) <= $eps * (\abs($this->S[$k]) + \abs($this->S[$k + 1]))) {
                    $e[$k] = 0.0;
                    break;
                }
            }

            $case = 0;
            if ($k === $p - 2) {
                $case = 4;
            } else {
                for ($ks = $p - 1; $ks >= $k; --$ks) {
                    if ($ks === $k) {
                        break;
                    }

                    $t = ($ks !== $p ? \abs($e[$ks]) : 0) + ($ks !== $k + 1 ? \abs($e[$ks - 1]) : 0);

                    if (\abs($this->S[$ks]) <= $eps * $t) {
                        $this->S[$ks] = 0.0;
                        break;
                    }
                }

                if ($ks === $k) {
                    $case = 3;
                } elseif ($ks === $p - 1) {
                    $case = 1;
                } else {
                    $case = 2;
                    $k    = $ks;
                }
            }
            ++$k;

            switch ($case) {
                case 1:
                    $f         = $e[$p - 2];
                    $e[$p - 2] = 0.0;

                    for ($j = $p - 2; $j >= $k; --$j) {
                        $t           = Triangle::getHypot($this->S[$j], $f);
                        $cs          = $this->S[$j] / $t;
                        $sn          = $f / $t;
                        $this->S[$j] = $t;

                        if ($j !== $k) {
                            $f         = -$sn * $e[$j - 1];
                            $e[$j - 1] = $cs * $e[$j - 1];
                        }

                        for ($i = 0; $i < $this->n; ++$i) {
                            $t                   = $cs * $this->V[$i][$j] + $sn * $this->V[$i][$p - 1];
                            $this->V[$i][$p - 1] = -$sn * $this->V[$i][$j] + $cs * $this->V[$i][$p - 1];
                            $this->V[$i][$j]     = $t;
                        }
                    }
                    break;
                case 2:
                    $f         = $e[$k - 1];
                    $e[$k - 1] = 0.0;

                    for ($j = $k; $j < $p; ++$j) {
                        $t           = Triangle::getHypot($this->S[$j], $f);
                        $cs          = $this->S[$j] / $t;
                        $sn          = $f / $t;
                        $this->S[$j] = $t;
                        $f           = -$sn * $e[$j];
                        $e[$j]       = $cs * $e[$j];

                        for ($i = 0; $i < $this->m; ++$i) {
                            $t                   = $cs * $this->U[$i][$j] + $sn * $this->U[$i][$k - 1];
                            $this->U[$i][$k - 1] = -$sn * $this->U[$i][$j] + $cs * $this->U[$i][$k - 1];
                            $this->U[$i][$j]     = $t;
                        }
                    }
                    break;
                case 3:
                    $scale = \max(
                        \max(
                            \max(
                                \max(
                                    \abs($this->S[$p - 1]),
                                    \abs($this->S[$p - 2])
                                ),
                                \abs($e[$p - 2])
                            ),
                            \abs($this->S[$k])
                        ),
                        \abs($e[$k])
                    );

                    $sp    = $this->S[$p - 1] / $scale;
                    $spm1  = $this->S[$p - 2] / $scale;
                    $epm1  = $e[$p - 2] / $scale;
                    $sk    = $this->S[$k] / $scale;
                    $ek    = $e[$k] / $scale;
                    $b     = (($spm1 + $sp) * ($spm1 - $sp) + $epm1 * $epm1) / 2.0;
                    $c     = ($sp * $epm1) * ($sp * $epm1);
                    $shift = 0.0;

                    if ($b != 0 || $c != 0) {
                        $shift = \sqrt($b * $b + $c);
                        if ($b < 0.0) {
                            $shift = -$shift;
                        }

                        $shift = $c / ($b + $shift);
                    }

                    $f = ($sk + $sp) * ($sk - $sp) + $shift;
                    $g = $sk * $ek;

                    for ($j = $k; $j < $p - 1; ++$j) {
                        $t  = Triangle::getHypot($f, $g);
                        $cs = $f / $t;
                        $sn = $g / $t;

                        if ($j !== $k) {
                            $e[$j - 1] = $t;
                        }

                        $f                = $cs * $this->S[$j] + $sn * $e[$j];
                        $e[$j]            = $cs * $e[$j] - $sn * $this->S[$j];
                        $g                = $sn * $this->S[$j + 1];
                        $this->S[$j + 1] *= $cs;

                        for ($i = 0; $i < $this->n; ++$i) {
                            $t                   = $cs * $this->V[$i][$j] + $sn * $this->V[$i][$j + 1];
                            $this->V[$i][$j + 1] = -$sn * $this->V[$i][$j] + $cs * $this->V[$i][$j + 1];
                            $this->V[$i][$j]     = $t;
                        }

                        $t               = Triangle::getHypot($f, $g);
                        $cs              = $f / $t;
                        $sn              = $g / $t;
                        $this->S[$j]     = $t;
                        $f               = $cs * $e[$j] + $sn * $this->S[$j + 1];
                        $this->S[$j + 1] = -$sn * $e[$j] + $cs * $this->S[$j + 1];
                        $g               = $sn * $e[$j + 1];
                        $e[$j + 1]       = $cs * $e[$j + 1];

                        if ($j < $this->m - 1) {
                            for ($i = 0; $i < $this->m; ++$i) {
                                $t                   = $cs * $this->U[$i][$j] + $sn * $this->U[$i][$j + 1];
                                $this->U[$i][$j + 1] = -$sn * $this->U[$i][$j] + $cs * $this->U[$i][$j + 1];
                                $this->U[$i][$j]     = $t;
                            }
                        }
                    }

                    $e[$p - 2] = $f;
                    ++$iter;
                    break;
                case 4:
                    if ($this->S[$k] <= 0.0) {
                        $this->S[$k] = ($this->S[$k] < 0.0 ? -$this->S[$k] : 0.0);

                        for ($i = 0; $i <= $pp; ++$i) {
                            $this->V[$i][$k] = -$this->V[$i][$k];
                        }
                    }

                    while ($k < $pp) {
                        if ($this->S[$k] >= $this->S[$k + 1]) {
                            break;
                        }

                        $t               = $this->S[$k];
                        $this->S[$k]     = $this->S[$k + 1];
                        $this->S[$k + 1] = $t;

                        if ($k < $this->n - 1) {
                            for ($i = 0; $i < $this->n; ++$i) {
                                $t                   = $this->V[$i][$k + 1];
                                $this->V[$i][$k + 1] = $this->V[$i][$k];
                                $this->V[$i][$k]     = $t;
                            }
                        }

                        if ($k < $this->m - 1) {
                            for ($i = 0; $i < $this->m; ++$i) {
                                $t                   = $this->U[$i][$k + 1];
                                $this->U[$i][$k + 1] = $this->U[$i][$k];
                                $this->U[$i][$k]     = $t;
                            }
                        }
                        ++$k;
                    }

                    $iter = 0;
                    --$p;
                    break;
            }
        }
    }

    /**
     * Get U matrix
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function getU() : Matrix
    {
        $matrix = new Matrix();
        $matrix->setMatrix($this->U);

        return $matrix;
    }

    /**
     * Get V matrix
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function getV() : Matrix
    {
        $matrix = new Matrix();
        $matrix->setMatrix($this->V);

        return $matrix;
    }

    /**
     * Get S matrix
     *
     * @return Matrix
     *
     * @since  1.0.0
     */
    public function getS() : Matrix
    {
        $S = [[]];
        for ($i = 0; $i < $this->n; ++$i) {
            for ($j = 0; $j < $this->n; ++$j) {
                $S[$i][$j] = 0.0;
            }

            $S[$i][$i] = $this->S[$i];
        }

        $matrix = new Matrix();
        $matrix->setMatrix($S);

        return $matrix;
    }

    /**
     * Get singular Values
     *
     * @return Vector
     *
     * @since  1.0.0
     */
    public function getSingularValues() : Vector
    {
        $vector = new Vector();
        $vector->setMatrix($this->S);

        return $vector;
    }

    /**
     * Get norm
     *
     * @return float
     *
     * @since  1.0.0
     */
    public function norm2() : float
    {
        return $this->S[0];
    }

    /**
     * Get condition
     *
     * @return float
     *
     * @since  1.0.0
     */
    public function cond() : float
    {
        return $this->S[0] / $this->S[\min($this->m, $this->n) - 1];
    }

    /**
     * Get rank
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function rank() : int
    {
        $eps = 0.00001;
        $tol = \max($this->m, $this->n) * $this->S[0] * $eps;
        $r   = 0;

        $length = \count($this->S);
        for ($i = 0; $i < $length; ++$i) {
            if ($this->S[$i] > $tol) {
                ++$r;
            }
        }

        return $r;
    }
}
