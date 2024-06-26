<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
final class MinimumCoinProblem
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
     * Find the minimum amount of coins that are required to match a value
     *
     * @param array $coins Types of coins available (every coin has infinite availablity)
     * @param int   $value Value to match with the coins
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getMinimumCoinsForValueI(array $coins, int $value) : array
    {
        // amount of required coins for different values
        $table     = [0];
        $usedCoins = [];

        for ($i = 1; $i <= $value; ++$i) {
            $table[$i] = \PHP_INT_MAX;
        }

        $m = \count($coins);

        for ($i = 1; $i <= $value; ++$i) {
            for ($j = 0; $j < $m; ++$j) {
                if ($coins[$j] <= $i) {
                    $subRes = $table[$i - $coins[$j]];

                    if ($subRes !== \PHP_INT_MAX
                        && $subRes + 1 < $table[$i]
                    ) {
                        $table[$i]     = $subRes + 1;
                        $usedCoins[$i] = $coins[$j] === null ? ($usedCoins[$i] ?? []) : \array_merge($usedCoins[$i - $coins[$j]] ?? [], [$coins[$j]]);
                    }
                }
            }
        }

        return $usedCoins[$value] ?? [];
    }
}
