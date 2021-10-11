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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\Heap;

/**
 * @testdox phpOMS\tests\Stdlib\Base\HeapTest: Heap
 *
 * @internal
 */
class HeapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A list of elements can be turned into a heap
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testHeapify() : void
    {
        $heap = new Heap();
        $heap->heapify([3, 2, 6, 1, 5, 4]);

        self::assertEquals(1, $heap->pop());
        self::assertEquals(2, $heap->pop());
        self::assertEquals(3, $heap->pop());
        self::assertEquals(4, $heap->pop());
        self::assertEquals(5, $heap->pop());
        self::assertEquals(6, $heap->pop());
    }

    /**
     * @testdox Elements get correctly pushed to the heap
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testSize() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals(5, $heap->size());
    }

    /**
     * @testdox A element can be added to a heap at the correct position
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testInsort() : void
    {
        $heap = new Heap();
        $heap->heapify([3, 6, 1, 5, 4]);
        $heap->insort(2);

        self::assertEquals(1, $heap->pop());
        self::assertEquals(2, $heap->pop());
        self::assertEquals(3, $heap->pop());
        self::assertEquals(4, $heap->pop());
        self::assertEquals(5, $heap->pop());
        self::assertEquals(6, $heap->pop());
    }

    /**
     * @testdox Heap elements get returned in the correct order
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testPushAndPop() : void
    {
        $heap = new Heap();
        for ($i = 0; $i < 10; ++$i) {
            $heap->push(mt_rand());
        }

        $sorted = [];
        while (!$heap->isEmpty()) {
            $sorted[] = $heap->pop();
        }

        $sortedFunction = $sorted;
        sort($sortedFunction);

        self::assertEquals($sortedFunction, $sorted);
    }

    /**
     * @testdox Heap elements get returned in the correct order by using a custom comparator
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testPushAndPopCustomComparator() : void
    {
        $heap = new Heap(function($a, $b) { return ($a <=> $b) * -1; });
        for ($i = 0; $i < 10; ++$i) {
            $heap->push(mt_rand());
        }

        $sorted = [];
        while (!$heap->isEmpty()) {
            $sorted[] = $heap->pop();
        }

        $sortedFunction = $sorted;
        sort($sortedFunction);

        self::assertEquals(array_reverse($sortedFunction), $sorted);
    }

    /**
     * @testdox The heap can be turned into an array
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testArray() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals([1, 2, 3, 4, 5], $heap->toArray());
    }

    /**
     * @testdox Heap elements can be replaced
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testReplace() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals(1, $heap->replace(3));
        self::assertEquals([2, 3, 3, 4, 5], $heap->toArray());
    }

    /**
     * @testdox A heap element can be returned while adding a new one
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testPushPop() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals(1, $heap->pushpop(6));

        $heapArray = $heap->toArray();
        sort($heapArray);
        self::assertEquals([2, 3, 4, 5, 6], $heapArray);
    }

    /**
     * @testdox The heap can be checked if it contains certain elements
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testContains() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertTrue($heap->contains(1));
        self::assertTrue($heap->contains(2));
        self::assertTrue($heap->contains(3));
        self::assertTrue($heap->contains(4));
        self::assertTrue($heap->contains(5));
        self::assertFalse($heap->contains(0));
        self::assertFalse($heap->contains(6));
    }

    /**
     * @testdox The heap can be checked if it contains certain custom elements
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testContainsItem() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        self::assertTrue($heap->contains(new HeapItem(1)));
        self::assertTrue($heap->contains(new HeapItem(2)));
        self::assertTrue($heap->contains(new HeapItem(3)));
        self::assertTrue($heap->contains(new HeapItem(4)));
        self::assertTrue($heap->contains(new HeapItem(5)));
        self::assertFalse($heap->contains(new HeapItem(0)));
        self::assertFalse($heap->contains(new HeapItem(6)));
    }

    /**
     * @testdox A heap item can be updated if it exists while maintaining the correct order
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testUpdate() : void
    {
        $heap  = new Heap();
        $items = [];

        for ($i = 1; $i < 7; ++$i) {
            $items[$i] = new HeapItem($i);
        }

        $heap->heapify([$items[3], $items[2], $items[6], $items[1], $items[5], $items[4]]);

        $items[4]->setValue(8);
        self::assertTrue($heap->update($items[4]));

        self::assertEquals(1, $heap->pop()->getValue());
        self::assertEquals(2, $heap->pop()->getValue());
        self::assertEquals(3, $heap->pop()->getValue());
        self::assertEquals(5, $heap->pop()->getValue());
        self::assertEquals(6, $heap->pop()->getValue());
        self::assertEquals(8, $heap->pop()->getValue());

        self::assertFalse($heap->update(new HeapItem(999)));
    }

    /**
     * @testdox The first heap element can be returned without removing it
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testPeek() : void
    {
        $heap = new Heap();

        $heap->push(1);
        self::assertEquals(1, $heap->peek());

        $heap->push(2);
        self::assertEquals(1, $heap->peek());

        $heap->pop();
        self::assertEquals(2, $heap->peek());
    }

    /**
     * @testdox The n smallest elements can be returned from the heap
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testNSmallest() : void
    {
        $heap = new Heap();
        $heap->push(1);
        $heap->push(3);
        $heap->push(1);
        $heap->push(4);

        self::assertEquals([1, 1, 3], $heap->getNSmallest(3));
    }

    /**
     * @testdox The n largest elements can be returned from the heap
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testNLargest() : void
    {
        $heap = new Heap();
        $heap->push(1);
        $heap->push(3);
        $heap->push(1);
        $heap->push(4);
        $heap->push(4);

        self::assertEquals([4, 4, 3], $heap->getNLargest(3));
    }

    /**
     * @testdox The heap can be cleared of all elements
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testClear() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        $heap->clear();
        self::assertEquals(0, $heap->size());
    }

    /**
     * @testdox The heap can be checked if it has elements
     * @covers phpOMS\Stdlib\Base\Heap
     * @group framework
     */
    public function testEmpty() : void
    {
        $heap = new Heap();
        self::assertTrue($heap->isEmpty());

        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertFalse($heap->isEmpty());
    }
}
