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
 * Backpack for the Knapsack problem
 *
 * @package phpOMS\Algorithm\Knapsack
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Backpack
{
    /**
     * Maximum amount of cost this backpack can hold
     *
     * @var   float
     * @since 1.0.0
     */
    private float $maxCost = 0.0;

    /**
     * Current value
     *
     * @var   float
     * @since 1.0.0
     */
    private float $value = 0.0;

    /**
     * Current cost
     *
     * @var  float
     * @since 1.0.0
     */
    private float $cost = 0.0;

    /**
     * Items inside the backpack
     *
     * @var   Item[]
     * @since 1.0.0
     */
    private array $items = [];

    /**
     * Constructor.
     *
     * @param float $maxCost Maximum amount of costs the backpack can hold
     *
     * @since 1.0.0
     */
    public function __construct(float $maxCost)
    {
        $this->maxCost = $maxCost;
    }

    /**
     * Get the value of the stored items
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getValue() : float
    {
        return $this->value;
    }

    /**
     * Get the cost of the stored items
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getCost() : float
    {
        return $this->cost;
    }

    /**
     * Get the max allowed costs for the items
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getMaxCost() : float
    {
        return $this->maxCost;
    }

    /**
     * Get items
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * Add item to backpack
     *
     * @param Item  $item     Item
     * @param mixed $quantity Quantity of the item
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addItem(Item $item, $quantity = 1) : void
    {
        $this->items[] = ['item' => $item, 'quantity' => $quantity];
        $this->value  += $item->getValue() * $quantity;
        $this->cost   += $item->getCost() * $quantity;
    }
}
