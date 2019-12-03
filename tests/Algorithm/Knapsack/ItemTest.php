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

use phpOMS\Algorithm\Knapsack\Item;

/**
 * @testdox phpOMS\tests\Algorithm\Knapsack\ItemTest: The default item to be added to the backpack or basket
 *
 * @internal
 */
class ItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The item has the expected values after initialization
     * @covers phpOMS\Algorithm\Knapsack\Item
     * @group framework
     */
    public function testDefault() : void
    {
        $item = new Item(3.0, 2.0, 'abc');

        self::assertEquals(3.0, $item->getValue());
        self::assertEquals(2.0, $item->getCost());
        self::assertEquals('abc', $item->getName());
    }
}
