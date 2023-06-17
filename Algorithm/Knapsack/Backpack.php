<?php
/**
 * Jingga
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
 * Backpack for the Knapsack problem
 *
 * @package phpOMS\Algorithm\Knapsack
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Backpack implements BackpackInterface
{
    /**
     * Maximum amount of cost this backpack can hold
     *
     * @var float
     * @since 1.0.0
     */
    private float $maxCost = 0.0;

    /**
     * Current value
     *
     * @var float
     * @since 1.0.0
     */
    private float $value = 0.0;

    /**
     * Current cost
     *
     * @var float
     * @since 1.0.0
     */
    private float $cost = 0.0;

    /**
     * Items inside the backpack
     *
     * @var array<int, array{item:ItemInterface, quantity:int|float}>
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
     * {@inheritdoc}
     */
    public function getValue() : float
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getCost() : float
    {
        return $this->cost;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxCost() : float
    {
        return $this->maxCost;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(ItemInterface $item, int | float $quantity = 1) : void
    {
        $this->items[] = ['item' => $item, 'quantity' => $quantity];
        $this->value  += $item->getValue() * $quantity;
        $this->cost   += $item->getCost() * $quantity;
    }
}
