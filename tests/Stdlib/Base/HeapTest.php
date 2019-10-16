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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\Heap;

/**
 * @internal
 */
class HeapTest extends \PHPUnit\Framework\TestCase
{
    public function testPushAndPop() : void
    {
        $heap = new Heap();
        for ($i = 0; $i < 10; ++$i) {
            $heap->push(\mt_rand());
        }

        $sorted = [];
        while (!$heap->isEmpty()) {
            $sorted[] = $heap->pop();
        }

        $sortedFunction = $sorted;
        \sort($sortedFunction);

        self::assertEquals($sortedFunction, $sorted);
    }

    public function testPushAndPopCustomComparator() : void
    {
        $heap = new Heap(function($a, $b) { return ($a <=> $b) * -1; });
        for ($i = 0; $i < 10; ++$i) {
            $heap->push(\mt_rand());
        }

        $sorted = [];
        while (!$heap->isEmpty()) {
            $sorted[] = $heap->pop();
        }

        $sortedFunction = $sorted;
        \sort($sortedFunction);

        self::assertEquals(\array_reverse($sortedFunction), $sorted);
    }

    public function testReplace() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals(1, $heap->replace(3));
        self::assertEquals([2, 3, 3, 4, 5], $heap->toArray());
    }

    public function testPushPop() : void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals(1, $heap->pushpop(6));

        $heapArray = $heap->toArray();
        \sort($heapArray);
        self::assertEquals([2, 3, 4, 5, 6], $heapArray);
    }

    public function testContains(): void
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

    public function testNSmallest() : void
    {
        $heap = new Heap();
        $heap->push(1);
        $heap->push(3);
        $heap->push(1);
        $heap->push(4);

        self::assertEquals([1, 1, 3], $heap->getNSmallest(3));
    }

    public function testNLargest(): void
    {
        $heap = new Heap();
        $heap->push(1);
        $heap->push(3);
        $heap->push(1);
        $heap->push(4);
        $heap->push(4);

        self::assertEquals([4, 4, 3], $heap->getNLargest(3));
    }

    public function testSize(): void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertEquals(5, $heap->size());
    }

    public function testClear(): void
    {
        $heap = new Heap();
        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        $heap->clear();
        self::assertEquals(0, $heap->size());
    }

    public function testEmpty(): void
    {
        $heap = new Heap();
        self::assertTrue($heap->isEmpty());

        for ($i = 1; $i < 6; ++$i) {
            $heap->push($i);
        }

        self::assertFalse($heap->isEmpty());
    }
}