<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * Item in the Knapsack
 *
 * @package phpOMS\Algorithm\Knapsack
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Item implements ItemInterface
{
    /**
     * Value of the item
     *
     * @var float
     * @since 1.0.0
     */
    private float $value = 0.0;

    /**
     * Cost of the item
     *
     * @var float
     * @since 1.0.0
     */
    private float $cost = 0.0;

    /**
     * Name of the item
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Constructor.
     *
     * @param float $value Value of the item
     * @param float $cost  Cost of the item
     *
     * @since 1.0.0
     */
    public function __construct(float $value, float $cost, string $name = '')
    {
        $this->value = $value;
        $this->cost  = $cost;
        $this->name  = $name;
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
    public function getName() : string
    {
        return $this->name;
    }
}
