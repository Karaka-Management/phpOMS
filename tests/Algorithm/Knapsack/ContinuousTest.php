<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Knapsack;

use phpOMS\Algorithm\Knapsack\Backpack;
use phpOMS\Algorithm\Knapsack\Continuous;
use phpOMS\Algorithm\Knapsack\Item;

/**
 * @testdox phpOMS\tests\Algorithm\Knapsack\ContinuousTest: A Knapsack implementation for continuous quantities, values and costs
 *
 * @internal
 */
final class ContinuousTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The optimal item selection in a backpack is calculated in order to optimize the value/profit while considering the available capacity/cost limit [discrete quantities]
     * @covers \phpOMS\Algorithm\Knapsack\Continuous
     * @group framework
     */
    public function testBackpacking() : void
    {
        $items = [
            ['item' => new Item(36.0, 3.8, 'beef'), 'quantity' => 1],
            ['item' => new Item(43.0, 5.4, 'pork'), 'quantity' => 1],
            ['item' => new Item(90.0, 3.6, 'ham'), 'quantity' => 1],
            ['item' => new Item(45.0, 2.4, 'greaves'), 'quantity' => 1],
            ['item' => new Item(30.0, 4.0, 'flitch'), 'quantity' => 1],
            ['item' => new Item(56.0, 2.5, 'brawn'), 'quantity' => 1],
            ['item' => new Item(67.0, 3.7, 'welt'), 'quantity' => 1],
            ['item' => new Item(95.0, 3.0, 'salami'), 'quantity' => 1],
            ['item' => new Item(98.0, 5.9, 'sausage'), 'quantity' => 1],
        ];

        $backpack         = new Backpack(15.0);
        $modifiedBackpack = Continuous::solve($items, $backpack);

        self::assertEquals(95 + 90 + 56 + 45 + 3.5 / 3.7 * 67, $modifiedBackpack->getValue());
        self::assertEquals(3.0 + 3.6 + 2.5 + 2.4 + 3.5, $modifiedBackpack->getCost());

        $backpackItems = $modifiedBackpack->getItems();
        self::assertEquals(5, \count($backpackItems));

        $names = [];
        foreach ($backpackItems as $backpackItem) {
            $names[] = $backpackItem['item']->getName();
        }

        self::assertTrue(
            \in_array('salami', $names)
            && \in_array('ham', $names)
            && \in_array('brawn', $names)
            && \in_array('greaves', $names)
            && \in_array('welt', $names)
        );
    }

    /**
     * @testdox The optimal item selection in a backpack is calculated in order to optimize the value/profit while considering the available capacity/cost limit [continuous quantities]
     * @covers \phpOMS\Algorithm\Knapsack\Continuous
     * @group framework
     */
    public function testBackpackingAlternative() : void
    {
        $items = [
            ['item' => new Item(36.0 / 3.8, 1, 'beef'), 'quantity' => 3.8],
            ['item' => new Item(43.0 / 5.4, 1, 'pork'), 'quantity' => 5.4],
            ['item' => new Item(90.0 / 3.6, 1, 'ham'), 'quantity' => 3.6],
            ['item' => new Item(45.0 / 2.4, 1, 'greaves'), 'quantity' => 2.4],
            ['item' => new Item(30.0 / 4.0, 1, 'flitch'), 'quantity' => 4.0],
            ['item' => new Item(56.0 / 2.5, 1, 'brawn'), 'quantity' => 2.5],
            ['item' => new Item(67.0 / 3.7, 1, 'welt'), 'quantity' => 3.7],
            ['item' => new Item(95.0 / 3.0, 1, 'salami'), 'quantity' => 3.0],
            ['item' => new Item(98.0 / 5.9, 1, 'sausage'), 'quantity' => 5.9],
        ];

        $backpack         = new Backpack(15.0);
        $modifiedBackpack = Continuous::solve($items, $backpack);

        self::assertEquals(95 + 90 + 56 + 45 + 3.5 / 3.7 * 67, $modifiedBackpack->getValue());
        self::assertEquals(3.0 + 3.6 + 2.5 + 2.4 + 3.5, $modifiedBackpack->getCost());

        $backpackItems = $modifiedBackpack->getItems();
        self::assertEquals(5, \count($backpackItems));

        $names = [];
        foreach ($backpackItems as $backpackItem) {
            $names[] = $backpackItem['item']->getName();
        }

        self::assertTrue(
            \in_array('salami', $names)
            && \in_array('ham', $names)
            && \in_array('brawn', $names)
            && \in_array('greaves', $names)
            && \in_array('welt', $names)
        );
    }
}
