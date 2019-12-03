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

use phpOMS\Algorithm\Sort\BucketSort;
use phpOMS\Algorithm\Sort\SortOrder;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\Sort\BucketSortTest: Bucket sort
 *
 * @internal
 */
class BucketSortTest extends \PHPUnit\Framework\TestCase
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
        $newList   = BucketSort::sort($smallList, 2);

        self::assertEquals($smallList, $newList);
    }

    /**
     * @testdox A list ot elements can be sorted in ASC order
     * @group framework
     */
    public function testSortASC() : void
    {
        $newList = BucketSort::sort($this->list, 2, \phpOMS\Algorithm\Sort\SelectionSort::class);
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
        $newList = BucketSort::sort($this->list, 2, \phpOMS\Algorithm\Sort\SelectionSort::class, SortOrder::DESC);
        self::assertEquals(
            [8, 5, 4, 2, 1], [$newList[0]->value, $newList[1]->value, $newList[2]->value, $newList[3]->value, $newList[4]->value,]
        );

        self::assertEquals(
            [5, 1, 4, 2, 8], [$this->list[0]->value, $this->list[1]->value, $this->list[2]->value, $this->list[3]->value, $this->list[4]->value,]
        );
    }

    /**
     * @testdox If no buckets are specified the elements cannot be sorted and an empty result is returned
     * @group framework
     */
    public function testNoBuckets() : void
    {
        $newList = BucketSort::sort($this->list, 0, \phpOMS\Algorithm\Sort\SelectionSort::class);
        self::assertEquals([], $newList);
    }
}
