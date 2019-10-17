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
 * Continuous knapsack algorithm
 *
 * @package phpOMS\Algorithm\Knapsack
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Continuous
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
     * Fill the backpack with items
     *
     * @param array             $items    Items to fill the backpack with ['item' => Item, 'quantity' => ?]
     * @param BackpackInterface $backpack Backpack to fill
     *
     * @return BackpackInterface
     *
     * @since 1.0.0
     */
    public static function solve(array $items, BackpackInterface $backpack) : BackpackInterface
    {
        usort($items, function($a, $b) {
            return $a['item']->getValue() / $a['item']->getCost() < $b['item']->getValue() / $b['item']->getCost();
        });

        $availableSpace = $backpack->getMaxCost();

        foreach ($items as $item) {
            if ($availableSpace <= 0.0) {
                break;
            }

            $backpack->addItem(
                $item['item'],
                $quantity = \min($item['quantity'], $availableSpace / $item['item']->getCost())
            );

            $availableSpace -= $quantity * $item['item']->getCost();
        }

        return $backpack;
    }
}