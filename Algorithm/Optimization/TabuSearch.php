<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Optimization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 *
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Optimization;

/**
 * Perform tabu search.
 *
 * @package phpOMS\Algorithm\Optimization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class TabuSearch
{
    // Define your fitness function here
    public static function fitness($solution) {
        // Calculate and return the fitness of the solution
        // This function should be tailored to your specific problem
        return /* ... */;
    }

    // Define your neighborhood generation function here
    public static function generateNeighbor($currentSolution) {
        // Generate a neighboring solution based on the current solution
        // This function should be tailored to your specific problem
        return /* ... */;
    }

    // Define the Tabu Search algorithm
    public static function optimize($initialSolution, \Closure $fitness, \Closure $neighbor, $tabuListSize, $maxIterations) {
        $currentSolution = $initialSolution;
        $bestSolution = $currentSolution;
        $bestFitness = \PHP_FLOAT_MIN;
        $tabuList = [];

        for ($iteration = 0; $iteration < $maxIterations; ++$iteration) {
            $neighbors = [];
            for ($i = 0; $i < $tabuListSize; ++$i) {
                $neighbor = ($neighbor)($currentSolution);
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

            if (\is_null($bestNeighbor)) {
                break;
            }

            $tabuList[] = $bestNeighbor;
            if (\count($tabuList) > $tabuListSize) {
                \array_shift($tabuList);
            }

            $currentSolution = $bestNeighbor;


            if (($score = ($fitness)($bestNeighbor)) > $bestFitness) {
                $bestSolution = $bestNeighbor;
                $bestFitness = $score;
            }
        }

        return $bestSolution;
    }
}
