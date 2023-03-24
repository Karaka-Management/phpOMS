<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Knapsack
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\Knapsack;

/**
 * Backpack interface.
 *
 * @package phpOMS\Algorithm\Knapsack;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface BackpackInterface
{
    /**
     * Get the value of the stored items
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getValue() : float;

    /**
     * Get the cost of the stored items
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getCost() : float;

    /**
     * Get the max allowed costs for the items
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getMaxCost() : float;

    /**
     * Get items
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getItems() : array;

    /**
     * Add item to backpack
     *
     * @param ItemInterface $item     Item
     * @param int|float     $quantity Quantity of the item
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addItem(ItemInterface $item, int | float $quantity = 1) : void;
}
