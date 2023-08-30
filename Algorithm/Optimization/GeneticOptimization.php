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
 * Perform genetic algorithm (GA).
 *
 * @package phpOMS\Algorithm\Optimization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class GeneticOptimization
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
    // Fitness function (may require to pass solution space as \Closure variable)
    // E.g.
    // highest value of some sorts (e.g. profit)
    // most elements (e.g. jobs)
    // lowest costs
    // combination of criteria = points (where some criteria are mandatory/optional)
    public static function fitness($x)
    {
        return $x;
    }

    public static function mutate($parameters, $mutationRate)
    {
        for ($i = 0; $i < \count($parameters); $i++) {
            if (\mt_rand(0, 1000) / 1000 < $mutationRate) {
                $parameters[$i] = 1 - $parameters[$i];
            }
        }

        return $parameters;
    }

    public static function crossover($parent1, $parent2, $parameterCount)
    {
        $crossoverPoint = \mt_rand(1, $parameterCount - 1);

        $child1 = \array_merge(
            \array_slice($parent1, 0, $crossoverPoint),
            \array_slice($parent2, $crossoverPoint)
        );

        $child2 = \array_merge(
            \array_slice($parent2, 0, $crossoverPoint),
            \array_slice($parent1, $crossoverPoint)
        );

        return [$child1, $child2];
    }
    */

    /**
     * Perform optimization
     *
     * @example See unit test for example use case
     *
     * @param array<array> $population   List of all elements with ther parameters (i.e. list of "objects" as arrays).
     *                                   The constraints are defined as array values.
     * @param \Closure     $fitness      Fitness function calculates score/feasability of solution
     * @param \Closure     $mutate       Mutation function to change the parameters of an "object"
     * @param \Closure     $crossover    Crossover function to exchange parameter values between "objects".
     *                                   Sometimes single parameters can be exchanged but sometimes interdependencies exist between parameters which is why this function is required.
     * @param int          $generations  Number of generations to create
     * @param float        $mutationRate Rate at which parameters are changed.
     *                                   How this is used depends on the mutate function.
     *
     * @return array{solutions:array, fitnesses:float[]}
     *
     * @since 1.0.0
     */
    public static function optimize(
        array $population,
        \Closure $fitness,
        \Closure $mutate,
        \Closure $crossover,
        int $generations = 500,
        float $mutationRate = 0.1
    ) : array
    {
        $populationSize = \count($population);
        $parameterCount = \count(\reset($population));

        // Genetic Algorithm Loop
        for ($generation = 0; $generation < $generations; $generation++) {
            $fitnessScores = [];
            foreach ($population as $parameters) {
                $fitnessScores[] = ($fitness)($parameters);
            }

            // Select parents for crossover based on fitness scores
            $parents = [];
            for ($i = 0; $i < $populationSize; $i++) {
                do {
                    $parentIndex1 = \array_rand($population);
                    $parentIndex2 = \array_rand($population);
                } while ($parentIndex1 === $parentIndex2);

                $parents[] = $fitnessScores[$parentIndex1] > $fitnessScores[$parentIndex2]
                    ? $population[$parentIndex1]
                    : $population[$parentIndex2];
            }

            // Crossover and mutation to create next generation
            $newPopulation = [];
            for ($i = 0; $i < $populationSize; $i += 2) {
                $crossover = ($crossover)($parents[$i], $parents[$i + 1], $parameterCount);

                $child1 = ($mutate)($crossover[0], $mutationRate);
                $child2 = ($mutate)($crossover[1], $mutationRate);

                $newPopulation[] = $child1;
                $newPopulation[] = $child2;
            }

            $population = $newPopulation;
        }

        $fitnesses = [];

        foreach ($population as $parameters) {
            $fitnesses[$population] = ($fitness)($parameters);
        }

        \asort($fitnesses);

        return [
            'solutions' => $population,
            'fitnesses' => $fitnesses,
        ];
    }
}
