<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Optimization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Optimization;

/**
 * Simplex class.
 *
 * The Simplex algorithm aims to solve a linear program - optimising a linear function subject
 * to linear constraints. As such it is useful for a very wide range of applications.
 *
 * N.B. The linear program has to be given in *slack form*, which is as follows:
 * maximise
 *     c_1 * x_1 + c_2 * x_2 + ... + c_n * x_n + v
 * subj. to
 *     a_11 * x_1 + a_12 * x_2 + ... + a_1n * x_n + b_1 = s_1
 *     a_21 * x_1 + a_22 * x_2 + ... + a_2n * x_n + b_2 = s_2
 *     ...
 *     a_m1 * x_1 + a_m2 * x_2 + ... + a_mn * x_n + b_m = s_m
 * and
 *     x_1, x_2, ..., x_n, s_1, s_2, ..., s_m >= 0
 *
 * Every linear program can be translated into slack form; the parameters to specify are:
 *      - the number of variables, n, and the number of constraints, m;
 *      - the matrix A = [[A_11, A_12, ..., A_1n], ..., [A_m1, A_m2, ..., A_mn]];
 *      - the vector b = [b_1, b_2, ..., b_m];
 *      - the vector c = [c_1, c_2, ..., c_n] and the constant v.
 *
 * Complexity: O(m^(n/2)) worst case
 *             O(n + m) average case (common)
 *
 * @package phpOMS\Math\Optimization
 * @license Copyright (c) 2015 Petar Veličković
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @link    https://github.com/PetarV-/Algorithms/blob/master/Mathematical%20Algorithms/Simplex%20Algorithm.cpp
 * @since   1.0.0
 */
final class Simplex
{
    /**
     * Bounding equations
     *
     * @var int
     * @since 1.0.0
     */
    private int $m = 0;

    /**
     * Bounding variables
     *
     * @var int
     * @since 1.0.0
     */
    private int $n = 0;

    /**
     * Bounding equations
     *
     * @var array<int, array<int|float>>
     * @since 1.0.0
     */
    private array $A = [];

    /**
     * Bounds for bounding equations
     *
     * @var array<int|float>
     * @since 1.0.0
     */
    private array $b = [];

    /**
     * Maximize vector
     *
     * @var array<int|float>
     * @since 1.0.0
     */
    private array $c = [];

    /**
     * Maximized value
     *
     * @var float
     * @since 1.0.0
     */
    private float $v = 0.0;

    /**
     * Basic solutions
     *
     * @var array<int|float>
     * @since 1.0.0
     */
    private array $basic = [];

    /**
     * Non-basic solutions
     *
     * @var array<int|float>
     * @since 1.0.0
     */
    private array $nonbasic = [];

    /**
     * Pivot yth variable around xth constraint
     *
     * @param int $x Constraint index
     * @param int $y Variable index
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function pivot(int $x, int $y) : void
    {
        for ($j = 0; $j < $this->n; ++$j) {
            if ($j !== $y) {
                $this->A[$x][$j] /= -$this->A[$x][$y];
            }
        }

        $this->b[$x] /= -$this->A[$x][$y];
        $this->A[$x][$y] = 1.0 / $this->A[$x][$y];

        for ($i = 0; $i < $this->m; ++$i) {
            if ($i !== $x) {
                for ($j = 0; $j < $this->n; ++$j) {
                    if ($j !== $y) {
                        $this->A[$i][$j] += $this->A[$i][$y] * $this->A[$x][$j];
                    }
                }

                $this->b[$i] += $this->A[$i][$y] * $this->b[$x];
                $this->A[$i][$y] *= $this->A[$x][$y];
            }
        }

        for ($j = 0; $j < $this->n; ++$j) {
            if ($j !== $y) {
                $this->c[$j] += $this->c[$y] * $this->A[$x][$j];
            }
        }

        $this->v += $this->c[$y] * $this->b[$x];
        $this->c[$y] *= $this->A[$x][$y];

        $temp               = $this->basic[$x];
        $this->basic[$x]    = $this->nonbasic[$y];
        $this->nonbasic[$y] = $temp;
    }

    /**
     * Perform simplex iteration step
     *
     * @return int 0 = OK, 1 = stop, -1 = unbound
     *
     * @since 1.0.0
     */
    private function iterate() : int
    {
        $ind  = -1;
        $best = -1;

        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->c[$j] > 0
                && ($best === -1 || $this->nonbasic[$j] < $ind)
            ) {
                $ind  = $this->nonbasic[$j];
                $best = $j;
            }
        }

        if ($ind === -1) {
            return 1;
        }

        $maxConstraint  = \INF;
        $bestConstraint = -1;

        for ($i = 0; $i < $this->m; ++$i) {
            if ($this->A[$i][$best] < 0) {
                $currentConstraint = -$this->b[$i] / $this->A[$i][$best];
                if ($currentConstraint < $maxConstraint) {
                    $maxConstraint  = $currentConstraint;
                    $bestConstraint = $i;
                }
            }
        }

        if ($maxConstraint === \INF) {
            return -1;
        }

        $this->pivot($bestConstraint, $best);

        return 0;
    }

    /**
     * Initialize simplex algorithm
     *
     * 1. possibly converts LP to slack form
     * 2. find feasible basic solution
     *
     * @return int 0 = OK, 1 = stop, -1 = unbound
     *
     * @since 1.0.0
     */
    private function initialize() : int
    {
        $k    = -1;
        $minB = -1;

        for ($i = 0; $i < $this->m; ++$i) {
            if ($k === -1 || $this->b[$i] < $minB) {
                $k    = $i;
                $minB = $this->b[$i];
            }
        }

        if ($this->b[$k] >= 0) {
            for ($j = 0; $j < $this->n; ++$j) {
                $this->nonbasic[$j] = $j;
            }

            for ($i = 0; $i < $this->m; ++$i) {
                $this->basic[$i] = $this->n + $i;
            }

            return 0;
        }

        ++$this->n;
        for ($j = 0; $j < $this->n; ++$j) {
            $this->nonbasic[$j] = $j;
        }

        for ($i = 0; $i < $this->m; ++$i) {
            $this->basic[$i] = $this->n + $i;
        }

        $oldC = [];
        for ($j = 0; $j < $this->n - 1; ++$j) {
            $oldC[$j] = $this->c[$j];
        }

        $oldV = $this->v;

        $this->c[$this->n - 1] = -1;
        for ($j = 0; $j < $this->n - 1; ++$j) {
            $this->c[$j] = 0;
        }

        $this->v = 0.0;

        for ($i = 0; $i < $this->m; ++$i) {
            $this->A[$i][$this->n - 1] = 1;
        }

        $this->pivot($k, $this->n - 1);

        while (!$this->iterate());

        if ($this->v !== 0.0) {
            return -1;
        }

        $basicZ = -1;
        for ($i = 0; $i < $this->m; ++$i) {
            if ($this->basic[$i] === $this->n - 1) {
                $basicZ = $i;
                break;
            }
        }

        if ($basicZ !== -1) {
            $this->pivot($basicZ, $this->n - 1);
        }

        $nonbasicZ = -1;
        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->nonbasic[$j] === $this->n - 1) {
                $nonbasicZ = $j;
                break;
            }
        }

        for ($i = 0; $i < $this->m; ++$i) {
            $this->A[$i][$nonbasicZ] = $this->A[$i][$this->n - 1];
        }

        $temp                         = $this->nonbasic[$nonbasicZ];
        $this->nonbasic[$nonbasicZ]   = $this->nonbasic[$this->n - 1];
        $this->nonbasic[$this->n - 1] = $temp;

        --$this->n;
        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->nonbasic[$j] > $this->n) {
                --$this->nonbasic[$j];
            }
        }

        for ($i = 0; $i < $this->m; ++$i) {
            if ($this->basic[$i] > $this->n) {
                --$this->basic[$i];
            }
        }

        for ($j = 0; $j < $this->n; ++$j) {
            $this->c[$j] = 0;
        }

        $this->v = $oldV;

        for ($j = 0; $j < $this->n; ++$j) {
            $ok = false;
            for ($k = 0; $k < $this->n; ++$k) {
                if ($j === $this->nonbasic[$k]) {
                    $this->c[$k] += $oldC[$j];
                    $ok = true;
                    break;
                }
            }

            if ($ok) {
                continue;
            }

            for ($i = 0; $i < $this->m; ++$i) {
                if ($j === $this->basic[$i]) {
                    for ($k = 0; $k < $this->n; ++$k) {
                        $this->c[$k] = $oldC[$j] * $this->A[$i][$k];
                    }

                    $this->v += $oldC[$j] * $this->b[$i];
                    break;
                }
            }
        }

        return 0;
    }

    /**
     * Solve simplex problem
     *
     * @param array<int, array<int|float>> $A Bounding equations
     * @param int[]|float[]             $b Boundings for equations
     * @param int[]|float[]             $c Equation to maximize
     *
     * @return array{0:array<int|float>, 1:float}
     *
     * @since 1.0.0
     */
    public function solve(array $A, array $b, array $c) : array
    {
        $this->A = $A;
        $this->b = $b;
        $this->c = $c;

        // @todo createSlackForm() required?
        // @todo create minimize

        $this->m = \count($A);
        $this->n = \count(\reset($A));

        if ($this->initialize() === -1) {
            return [\array_fill(0, $this->m + $this->n, -2), \INF];
        }

        $code = 0;
        while (!($code = $this->iterate()));

        if ($code === -1) {
            return [\array_fill(0, $this->m + $this->n, -1), \INF];
        }

        $result = [];
        for ($j = 0; $j < $this->n; ++$j) {
            $result[$this->nonbasic[$j]] = 0;
        }

        for ($i = 0; $i < $this->m; ++$i) {
            $result[$this->basic[$i]] = $this->b[$i];
        }

        return [$result, $this->v];
    }
}
