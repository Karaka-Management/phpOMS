<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\CoinMatching
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\CoinMatching;

/**
 * Matching a value with a set of coins
 *
 * @package phpOMS\Algorithm\CoinMatching
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Apriori
{
    private static function generateSubsets(array $arr) {
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
     * $transactions = array(
            array('milk', 'bread', 'eggs'),
            array('milk', 'bread'),
            array('milk', 'eggs'),
            array('bread', 'eggs'),
            array('milk')
        );
     */
    public static function apriori(array $sets) : array
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
