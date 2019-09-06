<?php

/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Algorithm\Knappsack
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\Backpack;

/**
 * Matching a value with a set of coins
 *
 * @package    phpOMS\Algorithm\Knappsack
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Backpack
{
    private $maxCost = 0.0;

    private $value = 0.0;

    private $cost = 0.0;

    private array $items = [];

    public function __construct($maxCost)
    {
        $this->maxCost = $maxCost;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function getItems() : array
    {
        return $this->items;
    }

    public function addItem(Item $item) : void
    {
        $this->items[] = $item;
        $this->value += $item->getValue();
        $this->cost += $item->getCost();
    }
}
