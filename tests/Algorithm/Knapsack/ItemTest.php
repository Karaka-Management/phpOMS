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

use phpOMS\Algorithm\Knapsack\Item;

/**
 * @testdox phpOMS\Algorithm\Knapsack\Item: Test the item for the Knapsack implementations
 *
 * @internal
 */
class ItemTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $item = new Item(3.0, 2.0, 'abc');

        self::assertEquals(3.0, $item->getValue());
        self::assertEquals(2.0, $item->getCost());
        self::assertEquals('abc', $item->getName());
    }
}
