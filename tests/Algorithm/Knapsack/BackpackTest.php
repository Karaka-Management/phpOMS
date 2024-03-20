<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
use phpOMS\Algorithm\Knapsack\Item;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Knapsack\Backpack::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Knapsack\BackpackTest: The default backpack or basket which holds all items for the Knapsack algorithm')]
final class BackpackTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The backpack has the expected values after initialization')]
    public function testDefault() : void
    {
        $backpack = new Backpack(3.0);

        self::assertEquals(3.0, $backpack->getMaxCost());
        self::assertEquals(0.0, $backpack->getValue());
        self::assertEquals(0.0, $backpack->getCost());
        self::assertEquals([], $backpack->getItems());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Items can be added to the backpack and automatically change the value and cost the backpack contains')]
    public function testAddItems() : void
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
