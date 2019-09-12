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

namespace phpOMS\tests\Algorithm\Sort;

use phpOMS\Algorithm\Sort\CycleSort;
use phpOMS\Algorithm\Sort\SortOrder;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\Sort: Cycle sort test
 *
 * @internal
 */
class CycleSortTest extends \PHPUnit\Framework\TestCase
{
    protected $list = [];

    protected function setUp() : void
    {
        $this->list = [
            new NumericElement(5),
            new NumericElement(1),
            new NumericElement(4),
            new NumericElement(2),
            new NumericElement(8),
        ];
    }

    public function testSortASC() : void
    {
        $newList = CycleSort::sort($this->list);
        self::assertEquals(
            [1, 2, 4, 5, 8], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );
    }

    public function testSortDESC() : void
    {
        $newList = CycleSort::sort($this->list, SortOrder::DESC);
        self::assertEquals(
            [8, 5, 4, 2, 1], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );
    }
}
