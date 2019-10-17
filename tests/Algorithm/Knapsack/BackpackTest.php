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

namespace phpOMS\Algorithm\Knapsack;

use phpOMS\Algorithm\Knapsack\Backpack;

/**
 * @testdox phpOMS\Algorithm\Knapsack\Backpack: Test the backpack for the Knapsack implementations
 *
 * @internal
 */
class BackpackTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $backpack = new Backpack(3.0);

        self::assertEquals(3.0, $backpack->getMaxCost());
        self::assertEquals(0.0, $backpack->getValue());
        self::assertEquals(0.0, $backpack->getCost());
        self::assertEquals([], $backpack->getItems());
    }

    public function testGetSet() : void
    {
        $backpack = new Backpack(3.0);
        $backpack->addItem(new Item(2, 1), 2);
        $backpack->addItem(new Item(2, 1), 1);

        self::assertEquals(3.0, $backpack->getMaxCost());
        self::assertEquals(6.0, $backpack->getValue());
        self::assertEquals(3.0, $backpack->getCost());
        self::assertEquals(2, \count($backpack->getItems()));
    }
}
