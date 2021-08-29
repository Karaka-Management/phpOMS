<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Sort;

use phpOMS\Algorithm\Sort\QuickSort;
use phpOMS\Algorithm\Sort\SortOrder;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\Sort\QuickSortTest: Quick sort
 *
 * @internal
 */
class QuickSortTest extends \PHPUnit\Framework\TestCase
{
    protected $list = [];

    /**
     * {@inheritdoc}
     */
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

    /**
     * @testdox A list with one element returns the list with the element itself
     * @group framework
     */
    public function testSmallList() : void
    {
        $smallList = [new NumericElement(3)];
        $newList   = QuickSort::sort($smallList);

        self::assertEquals($smallList, $newList);
    }

    /**
     * @testdox A list ot elements can be sorted in ASC order
     * @group framework
     */
    public function testSortASC() : void
    {
        $newList = QuickSort::sort($this->list);
        self::assertEquals(
            [1, 2, 4, 5, 8], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );

        self::assertEquals(
            [5, 1, 4, 2, 8], [$this->list[0]->value, $this->list[1]->value, $this->list[2]->value, $this->list[3]->value, $this->list[4]->value,]
        );
    }

    /**
     * @testdox A list ot elements can be sorted in DESC order
     * @group framework
     */
    public function testSortDESC() : void
    {
        $newList = QuickSort::sort($this->list, SortOrder::DESC);
        self::assertEquals(
            [8, 5, 4, 2, 1], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );

        self::assertEquals(
            [5, 1, 4, 2, 8], [$this->list[0]->value, $this->list[1]->value, $this->list[2]->value, $this->list[3]->value, $this->list[4]->value,]
        );
    }
}
