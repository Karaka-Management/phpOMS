<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Optimization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Optimization;

/**
 * Perform tabu search.
 *
 * @package phpOMS\Algorithm\Optimization
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class TabuSearch
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

    /*
    // Define your fitness function here
    public static function fitness($solution) {
        // Calculate and return the fitness of the solution
        // This function should be tailored to your specific problem
        return $solution;
    }

    // Define your neighborhood generation function here
    public static function generateNeighbor($currentSolution) {
        // Generate a neighboring solution based on the current solution
        // This function should be tailored to your specific problem
        return $currentSolution;
    }
    */

    /**
     * Perform optimization
     *
     * @example See unit test for example use case
     *
     * @param array    $initialSolution List of all elements with ther parameters (i.e. list of "objects" as arrays).
     *                                  The constraints are defined as array values.
     * @param \Closure $fitness         Fitness function calculates score/feasability of solution
     * @param \Closure $neighbor        Neighbor function to find a new solution/neighbor
     * @param int      $tabuListSize    ????
     * @param int      $iterations      Number of iterations
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function optimize(
        array $initialSolution,
        \Closure $fitness,
        \Closure $neighbor,
        int $tabuListSize,
        int $iterations
    ) : array
    {
        $currentSolution = $initialSolution;
        $bestSolution    = $currentSolution;
        $bestFitness     = \PHP_FLOAT_MIN;
        $tabuList        = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $neighbors = [];
            for ($j = 0; $j < $tabuListSize; ++$j) {
                $neighbor    = ($neighbor)($currentSolution);
                $neighbors[] = $neighbor;
            }

            $bestNeighbor = null;
            foreach ($neighbors as $neighbor) {
                if (!\in_array($neighbor, $tabuList) &&
                    ($bestNeighbor === null
                        || ($fitness)($neighbor) > ($fitness)($bestNeighbor))
                ) {
                    $bestNeighbor = $neighbor;
                }
            }

            if ($bestNeighbor === null) {
                break;
            }

            $tabuList[] = $bestNeighbor;
            if (\count($tabuList) > $tabuListSize) {
                \array_shift($tabuList);
            }

            $currentSolution = $bestNeighbor;

            if (($score = ($fitness)($bestNeighbor)) > $bestFitness) {
                $bestSolution = $bestNeighbor;
                $bestFitness  = $score;
            }
        }

        return $bestSolution;
    }
}
