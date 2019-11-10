<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Knapsack;

use phpOMS\Algorithm\Knapsack\Bounded;
use phpOMS\Algorithm\Knapsack\Backpack;
use phpOMS\Algorithm\Knapsack\Item;

/**
 * @testdox phpOMS\tests\Algorithm\Knapsack\BoundedTest: A Knapsack implementation for discrete quantities, values and costs and bounded item quantities
 *
 * @internal
 */
class BoundedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The optimal item selection in a backpack is calculated in order to optimize the value/profit while considering the available capacity/cost limit
     * @covers phpOMS\Algorithm\Knapsack\Bounded
     */
    public function testBackpacking() : void
    {
        $items = [
            ['item' => new Item(150, 9, 'map'), 'quantity' => 1],
            ['item' => new Item(35, 13, 'compass'), 'quantity' => 1],
            ['item' => new Item(200, 153, 'water'), 'quantity' => 2],
            ['item' => new Item(60, 50, 'sandwich'), 'quantity' => 2],
            ['item' => new Item(60, 15, 'glucose'), 'quantity' => 2],
            ['item' => new Item(45, 68, 'tin'), 'quantity' => 3],
            ['item' => new Item(60, 27, 'banana'), 'quantity' => 3],
            ['item' => new Item(40, 39, 'apple'), 'quantity' => 3],
            ['item' => new Item(30, 23, 'cheese'), 'quantity' => 1],
            ['item' => new Item(10, 52, 'beer'), 'quantity' => 3],
            ['item' => new Item(70, 11, 'suntan_cream'), 'quantity' => 1],
            ['item' => new Item(30, 32, 'camera'), 'quantity' => 1],
            ['item' => new Item(15, 24, 'Tshirt'), 'quantity' => 2],
            ['item' => new Item(10, 48, 'trousers'), 'quantity' => 2],
            ['item' => new Item(40, 73, 'umbrella'), 'quantity' => 1],
            ['item' => new Item(70, 42, 'waterproof_trousers'), 'quantity' => 1],
            ['item' => new Item(75, 43, 'waterproof_overclothes'), 'quantity' => 1],
            ['item' => new Item(80, 22, 'note_case'), 'quantity' => 1],
            ['item' => new Item(20, 7, 'sunglasses'), 'quantity' => 1],
            ['item' => new Item(12, 18, 'towel'), 'quantity' => 2],
            ['item' => new Item(50, 4, 'socks'), 'quantity' => 1],
            ['item' => new Item(10, 30, 'book'), 'quantity' => 2],
        ];

        $backpack         = new Backpack(400.0);
        $modifiedBackpack = Bounded::solve($items, $backpack);

        self::assertEquals(1010, $modifiedBackpack->getValue());
        self::assertEquals(396, $modifiedBackpack->getCost());

        $backpackItems = $modifiedBackpack->getItems();
        self::assertEquals(11, \count($backpackItems));

        $names = [];
        foreach ($backpackItems as $backpackItem) {
            $names[] = $backpackItem['item']->getName();
        }

        self::assertTrue(
            \in_array('map', $names)
            && \in_array('compass', $names)
            && \in_array('water', $names)
            && \in_array('glucose', $names)
            && \in_array('banana', $names)
            && \in_array('cheese', $names)
            && \in_array('suntan_cream', $names)
            && \in_array('waterproof_overclothes', $names)
            && \in_array('note_case', $names)
            && \in_array('sunglasses', $names)
            && \in_array('socks', $names)
        );
    }
}
