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
 * Perform simulated annealing (SA).
 *
 * @package phpOMS\Algorithm\Optimization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class SimulatedAnnealing
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
    public static function costFunction($x)
    {
        return $x;
    }

    // can be many things, e.g. swapping parameters, increasing/decrising, random generation
    public static function neighbor(array $generation, $parameterCount)
    {
        $newGeneration = $generation;
        $randomIndex1 = \mt_rand(0, $parameterCount - 1);
        $randomIndex2 = \mt_rand(0, $parameterCount - 1);

        // Swap two cities in the route
        $temp = $newGeneration[$randomIndex1];
        $newGeneration[$randomIndex1] = $newGeneration[$randomIndex2];
        $newGeneration[$randomIndex2] = $temp;

        return $newGeneration;
    }
    */

    // Simulated Annealing algorithm
    // @todo allow to create a solution space (currently all soluctions need to be in space)
    // @todo: currently only replacing generations, not altering them
    /**
     * Perform optimization
     *
     * @example See unit test for example use case
     *
     * @param array    $space              List of all elements with ther parameters (i.e. list of "objects" as arrays).
     *                                     The constraints are defined as array values.
     * @param int      $initialTemperature Starting temperature
     * @param \Closure $costFunction       Fitness function calculates score/feasability of solution
     * @param \Closure $neighbor           Neighbor function to find a new solution/neighbor
     * @param float    $coolingRate        Rate at which cooling takes place
     * @param int      $iterations         Number of iterations
     *
     * @return array{solutions:array, costs:float[]}
     *
     * @since 1.0.0
     */
    function optimize(
        array $space,
        int $initialTemperature,
        \Closure $costFunction,
        \Closure $neighbor,
        float $coolingRate = 0.98,
        int $iterations = 1000
    ) : array
    {
        $parameterCount    = \count($space);
        $currentGeneration = \reset($space);

        $currentCost = ($costFunction)($currentGeneration);

        for ($i = 0; $i < $iterations; ++$i) {
            $newGeneration = ($neighbor)($currentGeneration, $parameterCount);

            $newCost = ($costFunction)($newGeneration);

            $temperature = $initialTemperature * pow($coolingRate, $i);

            if ($newCost < $currentCost
                || \mt_rand() / \mt_getrandmax() < \exp(($currentCost - $newCost) / $temperature)
            ) {
                $currentGeneration = $newGeneration;
                $currentCost = $newCost;
            }
        }

        return [
            'solutions' => $currentGeneration,
            'costs'     => $currentCost
        ];
    }
}
