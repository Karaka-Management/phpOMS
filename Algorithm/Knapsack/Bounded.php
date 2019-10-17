<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Knapsack
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\Knapsack;

/**
 * Bounded knapsack algorithm
 *
 * This algorithm only works for integer cost, values and quantities!
 *
 * @package phpOMS\Algorithm\Knapsack
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Bounded
{
    /**
     * Fill the backpack with items
     *
     * This algorithm only works for integer cost, values and quantities!
     *
     * @param array    $items    Items to fill the backpack with ['item' => Item, 'quantity' => ?]
     * @param Backpack $backpack Backpack to fill
     *
     * @return Backpack
     *
     * @since 1.0.0
     */
    public static function solve(array $items, Backpack $backpack) : Backpack
    {
        $n       = \count($items);
        $maxCost = (int) $backpack->getMaxCost();
        $mm      = \array_fill(0, ($maxCost + 1), 0);
        $m       = [];
        $m[0]    = $mm;

        for ($i = 1; $i <= $n; ++$i) {
            $m[$i] = $mm;

            for ($j = 0; $j <= $maxCost; ++$j) {
                $m[$i][$j] = $m[$i - 1][$j];

                for ($k = 1; $k <= $items[$i - 1]['quantity']; $k++) {
                    if ($k * ((int) $items[$i - 1]['item']->getCost()) > $j) {
                        break;
                    }

                    $v = $m[$i - 1][$j - $k * ((int) $items[$i - 1]['item']->getCost())] + $k * ((int) $items[$i - 1]['item']->getValue());

                    if ($v > $m[$i][$j]) {
                        $m[$i][$j] = $v;
                    }
                }
            }
        }

        $s = 0;
        for ($i = $n, $j = $maxCost; $i > 0; --$i) {
            $s = 0;
            $v = $m[$i][$j];

            for ($k = 0; $v !== $m[$i - 1][$j] + $k * ((int) $items[$i - 1]['item']->getValue()); ++$k) {
                $s++;
                $j -= (int) $items[$i - 1]['item']->getCost();
            }

            if ($s > 0) {
                $backpack->addItem($items[$i - 1]['item'], $s);
            }
        }

        return $backpack;
    }
}