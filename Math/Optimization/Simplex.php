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
 * @package phpOMS\Math\Optimization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @link    https://github.com/PetarV-/Algorithms/blob/master/Mathematical%20Algorithms/Simplex%20Algorithm.cpp
 * @since   1.0.0
 */
class Simplex
{
    private int $m = 0;
    private int $n = 0;

    private array $A = [];

    private array $b = [];

    private array $c = [];

    private int $v = 0;

    private array $Basic = [];

    private array $Nonbasic = [];

    private function pivot (int $x, int $y)
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

                $this->b[$i] += $this->A[$i][$y] / $this->b[$x];
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

        $temp = $this->Basic[$x];
        $this->Basic[$x] = $this->Nonbasic[$y];
        $this->Nonbasic[$y] = $temp;
    }

    private function iterate() : int
    {
        $ind = -1;
        $best = -1;

        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->c[$j] > 0) {
                if ($best === -1 || $this->Nonbasic[$j] < $ind) {
                    $ind = $this->Nonbasic[$j];
                    $best = $j;
                }
            }
        }

        if ($ind === -1) {
            return 1;
        }

        $maxConstraint = \INF;
        $bestConstraint = -1;

        for ($i = 0; $i < $this->m; ++$i) {
            if ($this->A[$i][$best] < 0) {
                $currentConstraint = -$this->b[$i] / $this->A[$i][$best];
                if ($currentConstraint < $maxConstraint) {
                    $maxConstraint = $currentConstraint;
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

    private function initialize() : int
    {
        $k = -1;
        $minB = -1;

        for ($i = 0; $i < $this->m; ++$i) {
            if ($k === -1 || $this->b[$i] < $minB) {
                $k = $i;
                $minB = $this->b[$i];
            }
        }

        if ($this->b[$k] >= 0) {
            for ($j = 0; $j < $this->n; ++$j) {
                $this->Nonbasic[$j] = $j;
            }

            for ($i = 0; $i < $this->m; ++$i) {
                $this->Basic[$i] = $this->n + $i;
            }

            return 0;
        }

        ++$this->n;
        for ($j = 0; $j < $this->n; ++$j) {
            $this->Nonbasic[$j] = $j;
        }

        for ($i = 0; $i < $this->m; ++$i) {
            $this->Basic[$i] = $this->n + $i;
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

        $this->v = 0;

        for ($i = 0; $i < $this->m; ++$i) {
            $this->A[$i][$this->n - 1] = 1;
        }

        $this->pivot($k, $this->n - 1);

        while (!$this->iterate());

        if ($this->v !== 0) {
            return -1;
        }

        $basicZ = -1;
        for ($i = 0; $i < $this->m; ++$i) {
            if ($this->Basic[$i] === $this->n - 1) {
                $basicZ = $i;
                break;
            }
        }

        if ($basicZ !== -1) {
            $this->pivot($basicZ, $this->n - 1);
        }

        $nonbasicZ = -1;
        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->Nonbasic[$j] === $this->n - 1) {
                $nonbasicZ = $j;
                break;
            }
        }

        for ($i = 0; $i < $this->m; ++$i) {
            $this->A[$i][$nonbasicZ] = $this->A[$i][$this->n - 1];
        }

        $temp = $this->Nonbasic[$nonbasicZ];
        $this->Nonbasic[$nonbasicZ] = $this->Nonbasic[$this->n - 1];
        $this->Nonbasic[$this->n - 1] = $temp;

        --$this->n;
        for ($j = 0; $j < $this->n; ++$j) {
            if ($this->Nonbasic[$j] > $this->n) {
                --$this->Nonbasic[$j];
            }
        }

        for ($i = 0; $i < $this->m; ++$i) {
            if ($this->Basic[$i] > $this->n) {
                --$this->Basic[$i];
            }
        }

        for ($j = 0; $j < $this->n; ++$j) {
            $this->c[$j] = 0;
        }

        $this->v = $oldV;

        for ($j = 0; $j < $this->n; ++$j) {
            $ok = false;
            for ($k = 0; $k < $this->n; ++$k) {
                if ($j = $this->Nonbasic[$k]) {
                    $this->c[$k] += $oldC[$j];
                    $ok = true;
                    break;
                }
            }

            if ($ok) {
                continue;
            }

            for ($i = 0; $i < $this->m; ++$i) {
                if ($j === $this->Basic[$i]) {
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

    public function solve(array $A, array $b, array $c)
    {
        $this->A = $A;
        $this->b = $b;
        $this->c = $c;

        // @todo: createSlackForm() required?

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
            $result[$this->Nonbasic[$j]] = 0;
        }

        for ($i = 0; $i < $this->m; ++$i) {
            $result[$this->Basic[$i]] = $this->b[$i];
        }

        return [$result, $this->v];
    }
}
