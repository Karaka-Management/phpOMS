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

use phpOMS\Algorithm\Sort\TimSort;
use phpOMS\Algorithm\Sort\SortOrder;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\Sort\TimSortTest: Tim sort
 *
 * @internal
 */
class TimSortTest extends \PHPUnit\Framework\TestCase
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

    /**
     * @testdox A list with one element returns the list with the element itself
     * @group framework
     */
    public function testSmallList() : void
    {
        $smallList = [new NumericElement(3)];
        $newList   = TimSort::sort($smallList);

        self::assertEquals($smallList, $newList);
    }

    /**
     * @testdox A list ot elements can be sorted in ASC order
     * @group framework
     */
    public function testSortASC() : void
    {
        $newList = TimSort::sort($this->list);
        self::assertEquals(
            [1, 2, 4, 5, 8], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );

        self::assertEquals(
            [5, 1, 4, 2, 8], [$this->list[0]->value, $this->list[1]->value, $this->list[2]->value, $this->list[3]->value, $this->list[4]->value,]
        );

        $list = [
            new NumericElement(25), new NumericElement(94), new NumericElement(45), new NumericElement(77), new NumericElement(11), new NumericElement(4), new NumericElement(100),
            new NumericElement(25), new NumericElement(45), new NumericElement(55), new NumericElement(5), new NumericElement(80), new NumericElement(55), new NumericElement(66),
            new NumericElement(6), new NumericElement(4), new NumericElement(45), new NumericElement(94), new NumericElement(100), new NumericElement(6), new NumericElement(94),
            new NumericElement(77), new NumericElement(30), new NumericElement(55), new NumericElement(4), new NumericElement(80), new NumericElement(9), new NumericElement(77),
            new NumericElement(22), new NumericElement(11), new NumericElement(66), new NumericElement(22), new NumericElement(94), new NumericElement(4), new NumericElement(77),
            new NumericElement(0), new NumericElement(77), new NumericElement(10), new NumericElement(94), new NumericElement(0), new NumericElement(6), new NumericElement(77),
            new NumericElement(0), new NumericElement(0), new NumericElement(11), new NumericElement(94), new NumericElement(4), new NumericElement(66), new NumericElement(10),
            new NumericElement(4), new NumericElement(11), new NumericElement(4), new NumericElement(80), new NumericElement(25), new NumericElement(30), new NumericElement(66),
            new NumericElement(94), new NumericElement(66), new NumericElement(94), new NumericElement(6), new NumericElement(94), new NumericElement(6), new NumericElement(94),
            new NumericElement(45), new NumericElement(4), new NumericElement(25), new NumericElement(55), new NumericElement(35), new NumericElement(4), new NumericElement(10),
            new NumericElement(4), new NumericElement(80), new NumericElement(10), new NumericElement(35), new NumericElement(11), new NumericElement(25), new NumericElement(11),
            new NumericElement(35), new NumericElement(11), new NumericElement(35), new NumericElement(4), new NumericElement(25), new NumericElement(11), new NumericElement(80),
            new NumericElement(22), new NumericElement(94), new NumericElement(4), new NumericElement(30), new NumericElement(6), new NumericElement(66), new NumericElement(11),
            new NumericElement(4), new NumericElement(80), new NumericElement(2), new NumericElement(80), new NumericElement(25), new NumericElement(0), new NumericElement(45),
            new NumericElement(55), new NumericElement(25)
        ];

        $newList = TimSort::sort($list);
        $result = [];
        foreach ($newList as $element) {
            $result[] = $element->value;
        }

        self::assertEquals(
            [0, 0, 0, 0, 0, 2, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 6, 6, 6, 6, 6, 6, 9, 10, 10, 10, 10, 11, 11, 11, 11, 11, 11, 11, 11, 11, 22, 22, 22, 25, 25, 25, 25, 25, 25, 25, 25, 30, 30, 30, 35, 35, 35, 35, 45, 45, 45, 45, 45, 55, 55, 55, 55, 55, 66, 66, 66, 66, 66, 66, 77, 77, 77, 77, 77, 77, 80, 80, 80, 80, 80, 80, 80, 94, 94, 94, 94, 94, 94, 94, 94, 94, 94, 94, 100, 100],
            $result
        );
    }

    /**
     * @testdox A list ot elements can be sorted in DESC order
     * @group framework
     */
    public function testSortDESC() : void
    {
        $newList = TimSort::sort($this->list, SortOrder::DESC);
        self::assertEquals(
            [8, 5, 4, 2, 1], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );

        self::assertEquals(
            [5, 1, 4, 2, 8], [$this->list[0]->value, $this->list[1]->value, $this->list[2]->value, $this->list[3]->value, $this->list[4]->value,]
        );
    }
}
