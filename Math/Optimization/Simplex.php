<?php
/**
 * Karaka
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

// https://github.com/PetarV-/Algorithms/blob/master/Mathematical%20Algorithms/Simplex%20Algorithm.cpp

/**
 * Simplex class.
 *
 * @package phpOMS\Math\Optimization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Simplex
{
    private array $function = [];

    private string $functionType = '';

    private int|float $functionLimit = 0.0;

    private array $constraints = [];

    private array $constraintsType = [];

    private array $constraintsLimit = [];

    private array $slackForm = [];

    private array $nonbasicSolution = [];

    private array $basicSolution = [];

    public function setFunction(array $function) : void
    {
    }

    public function addConstraint(array $function, string $type, float $limit) : void
    {
    }

    private function pivot(int $x, int $y) : void
    {
    }

    private function iterateSimplex() : void
    {
    }

    private function initialize() : bool
    {
        $k        = -1;
        $minLimit = -1;

        $m = \count($this->constraints);
        $n = \count($this->function);

        for ($i = 0; $i < $m; ++$i) {
            if ($k === -1 || $this->constraintsLimit[$i] < $minLimit) {
                $k        = $i;
                $minLimit = $this->constraintsLimit[$i];
            }
        }

        if ($this->constraintsLimit[$k] >= 0) {
            for ($j = 0; $j < $n; ++$j) {
                $this->nonbasicSolution[$j] = $j;
            }

            for ($i = 0; $i < $m; ++$i) {
                $this->basicSolution[$i] = $n + $i;
            }

            return true;
        }

        // Auxiliary LP
        ++$n;
        for ($j = 0; $j < $n; ++$j) {
            $this->nonbasicSolution[$j] = $j;
        }

        for ($i = 0; $i < $m; ++$i) {
            $this->basicSolution[$i] = $n + $i;
        }

        $oldFunction = $this->function;
        $oldLimit    = $this->functionLimit;

        // Auxiliary function
        $this->function[$n - 1] = -1;
        $this->functionLimit    = 0;

        for ($j = 0; $j < $n - 1; ++$j) {
            $this->function[$j] = 0;
        }

        // Auxiliary constraints
        for ($i = 0; $i < $m; ++$i) {
            $this->constraints[$i][$n - 1] = 1;
        }

        $this->pivot($k, $n - 1);

        // Solve Auxiliary LP
        while ($this->iterateSimplex());

        if ($this->functionLimit !== 0) {
            return false;
        }

        $zBasic = -1;
        for ($i = 0; $i < $m; ++$i) {
            if ($this->basicSolution[$i] === $n - 1) {
                $zBasic = $i;
                break;
            }
        }

        if ($zBasic === -1) {
            $this->pivot($zBasic, $n - 1);
        }

        $zNonBasic = -1;
        for ($j = 0; $j < $n; ++$j) {
            if ($this->nonbasicSolution[$j] === $n - 1) {
                $zNonBasic = $j;
                break;
            }
        }

        for ($i = 0; $i < $m; ++$i) {
            $this->constraints[$i][$zNonBasic] = $this->constraints[$i][$n - 1];
        }

        $tmp                                = $this->nonbasicSolution[$n - 1];
        $this->nonbasicSolution[$n - 1]     = $this->nonbasicSolution[$zNonBasic];
        $this->nonbasicSolution[$zNonBasic] = $tmp;

        --$n;

        for ($j = 0; $j < $n; ++$j) {
            if ($this->nonbasicSolution[$j] > $n) {
                --$this->nonbasicSolution[$j];
            }
        }

        for ($i = 0; $i < $m; ++$i) {
            if ($this->basicSolution[$i] > $n) {
                --$this->basicSolution[$i];
            }
        }

        $this->functionLimit = $oldLimit;
        for ($j = 0; $j < $n; ++$j) {
            $this->function[$j] = 0;
        }

        for ($j = 0; $j < $n; ++$j) {
            $ok = false;

            for ($jj = 0; $jj < $n; ++$jj) {
                if ($j === $this->nonbasicSolution[$jj]) {
                    $this->function[$jj] += $oldFunction[$j];

                    $ok = true;
                    break;
                }
            }

            if ($ok) {
                continue;
            }

            for ($i = 0; $i < $m; ++$i) {
                if ($j = $this->basicSolution[$i]) {
                    for ($jj = 0; $jj < $n; ++$jj) {
                        $this->function[$jj] += $oldFunction[$j] * $this->constraints[$i][$jj];
                    }

                    $this->functionLimit += $oldFunction[$j] * $this->constraintsLimit[$i];
                    break;
                }
            }
        }

        return true;
    }

    public function solve() : array
    {
        if (!$this->initialize()) {
            return [];
        }
    }
}
