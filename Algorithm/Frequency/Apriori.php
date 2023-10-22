<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Frequency
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Frequency;

/**
 * Apriori algorithm.
 *
 * The algorithm cheks how often a set exists in a given set of sets.
 *
 * @package phpOMS\Algorithm\Frequency
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Apriori
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

    /**
     * Generate all possible subsets
     *
     * @param array $arr Array of eleements
     *
     * @return array<array>
     *
     * @since 1.0.0
     */
    private static function generateSubsets(array $arr) : array
    {
        $subsets = [[]];

        foreach ($arr as $element) {
            $newSubsets = [];

            foreach ($subsets as $subset) {
                $newSubsets[] = $subset;
                $newSubsets[] = \array_merge($subset, [$element]);
            }

            $subsets = $newSubsets;
        }

        unset($subsets[0]);

        return $subsets;
    }

    /**
     * Performs the apriori algorithm.
     *
     * The algorithm cheks how often a set exists in a given set of sets.
     *
     * @param array<array> $sets   Sets of a set (e.g. [[1,2,3,4], [1,2], [1]])
     * @param array        $subset Subset to check for (empty array -> all subsets are checked)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function apriori(array $sets, array $subset = []) : array
    {
        // Unique single items
        $totalSet = [];
        foreach ($sets as &$s) {
            \sort($s);

            foreach ($s as $item) {
                $totalSet[] = $item;
            }
        }

        $totalSet = \array_unique($totalSet);
        \sort($totalSet);

        // Combinations of items
        $combinations = self::generateSubsets($totalSet);

        // Table
        $table = [];
        foreach ($combinations as &$c) {
            \sort($c);
            $table[\implode(':', $c)] = 0;
        }

        foreach ($combinations as $combination) {
            if (!empty($subset) && $combination !== $subset) {
                continue;
            }

            foreach ($sets as $set) {
                foreach ($combination as $item) {
                    if (!\in_array($item, $set)) {
                        continue 2;
                    }
                }

                ++$table[\implode(':', $combination)];
            }
        }

        return $table;
    }
}
