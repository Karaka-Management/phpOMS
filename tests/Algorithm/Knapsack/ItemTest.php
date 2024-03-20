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

use phpOMS\Algorithm\Knapsack\Item;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Knapsack\Item::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Knapsack\ItemTest: The default item to be added to the backpack or basket')]
final class ItemTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The item has the expected values after initialization')]
    public function testDefault() : void
    {
        $item = new Item(3.0, 2.0, 'abc');

        self::assertEquals(3.0, $item->getValue());
        self::assertEquals(2.0, $item->getCost());
        self::assertEquals('abc', $item->getName());
    }
}
