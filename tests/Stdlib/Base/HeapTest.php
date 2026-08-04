<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\Heap;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\Heap::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\HeapTest: Heap')]
final class HeapTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A list of elements can be turned into a heap')]
    public function testHeapify() : void
    {
        $heap = new Heap();
        $heap->heapify(
            [
                new HeapItem(3),
                new HeapItem(2),
                new HeapItem(6),
                new HeapItem(1),
                new HeapItem(5),
                new HeapItem(4),
            ]
        );

        self::assertEquals(1, $heap->pop()->getValue());
        self::assertEquals(2, $heap->pop()->getValue());
        self::assertEquals(3, $heap->pop()->getValue());
        self::assertEquals(4, $heap->pop()->getValue());
        self::assertEquals(5, $heap->pop()->getValue());
        self::assertEquals(6, $heap->pop()->getValue());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Elements get correctly pushed to the heap')]
    public function testSize() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        self::assertEquals(5, $heap->size());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A element can be added to a heap at the correct position')]
    public function testInsort() : void
    {
        $heap = new Heap();
        $heap->heapify(
            [
                new HeapItem(3),
                new HeapItem(6),
                new HeapItem(1),
                new HeapItem(5),
                new HeapItem(4),
            ]
        );
        $heap->insort(new HeapItem(2));

        self::assertEquals(1, $heap->pop()->getValue());
        self::assertEquals(2, $heap->pop()->getValue());
        self::assertEquals(3, $heap->pop()->getValue());
        self::assertEquals(4, $heap->pop()->getValue());
        self::assertEquals(5, $heap->pop()->getValue());
        self::assertEquals(6, $heap->pop()->getValue());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Heap elements get returned in the correct order')]
    public function testPushAndPop() : void
    {
        $heap = new Heap();
        for ($i = 0; $i < 10; ++$i) {
            $heap->push(new HeapItem(\mt_rand(0, 100)));
        }

        $sorted = [];
        while (!$heap->isEmpty()) {
            $sorted[] = $heap->pop()->getValue();
        }

        $sortedFunction = $sorted;
        \sort($sortedFunction);

        self::assertEquals($sortedFunction, $sorted);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Heap elements get returned in the correct order by using a custom comparator')]
    public function testPushAndPopCustomComparator() : void
    {
        $heap = new Heap(function($a, $b) { return ($a <=> $b) * -1; });
        for ($i = 0; $i < 10; ++$i) {
            $heap->push(new HeapItem(\mt_rand(0, 100)));
        }

        $sorted = [];
        while (!$heap->isEmpty()) {
            $sorted[] = $heap->pop();
        }

        $sortedFunction = $sorted;
        \sort($sortedFunction);

        self::assertEquals(\array_reverse($sortedFunction), $sorted);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The heap can be turned into an array')]
    public function testArray() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        self::assertEquals(
            [
                new HeapItem(1),
                new HeapItem(2),
                new HeapItem(3),
                new HeapItem(4),
                new HeapItem(5),
            ],
            $heap->toArray()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Heap elements can be replaced')]
    public function testReplace() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        self::assertEquals(1, $heap->replace(new HeapItem(3))->getValue());
        self::assertEquals(
            [
                new HeapItem(2),
                new HeapItem(3),
                new HeapItem(3),
                new HeapItem(4),
                new HeapItem(5),
            ],
            $heap->toArray()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A heap element can be returned while adding a new one')]
    public function testPushPop() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        self::assertEquals(1, $heap->pushpop(new HeapItem(6))->getValue());

        $heapArray = $heap->toArray();
        \sort($heapArray);
        self::assertEquals(
            [
                new HeapItem(2),
                new HeapItem(3),
                new HeapItem(4),
                new HeapItem(5),
                new HeapItem(6),
            ],
            $heapArray
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The heap can be checked if it contains certain elements')]
    public function testContains() : void
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The heap can be checked if it contains certain custom elements')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A heap item can be updated if it exists while maintaining the correct order')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The first heap element can be returned without removing it')]
    public function testPeek() : void
    {
        $heap = new Heap();

        $heap->push($a = new HeapItem(1));
        self::assertEquals($a, $heap->peek());

        $heap->push($b = new HeapItem(2));
        self::assertEquals($a, $heap->peek());

        $heap->pop();
        self::assertEquals($b, $heap->peek());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The n smallest elements can be returned from the heap')]
    public function testNSmallest() : void
    {
        $heap = new Heap();
        $heap->push(new HeapItem(1));
        $heap->push(new HeapItem(3));
        $heap->push(new HeapItem(1));
        $heap->push(new HeapItem(4));

        self::assertEquals([new HeapItem(1), new HeapItem(1), new HeapItem(3)], $heap->getNSmallest(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The n largest elements can be returned from the heap')]
    public function testNLargest() : void
    {
        $heap = new Heap();
        $heap->push(new HeapItem(1));
        $heap->push(new HeapItem(3));
        $heap->push(new HeapItem(1));
        $heap->push(new HeapItem(4));
        $heap->push(new HeapItem(4));

        self::assertEquals([new HeapItem(4), new HeapItem(4), new HeapItem(3)], $heap->getNLargest(3));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The heap can be cleared of all elements')]
    public function testClear() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        $heap->clear();
        self::assertEquals(0, $heap->size());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The heap can be checked if it has elements')]
    public function testEmpty() : void
    {
        $heap = new Heap();
        self::assertTrue($heap->isEmpty());

        for ($i = 1; $i < 6; ++$i) {
            $heap->push(new HeapItem($i));
        }

        self::assertFalse($heap->isEmpty());
    }
}
