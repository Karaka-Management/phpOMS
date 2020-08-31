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

namespace phpOMS\tests\Stdlib\Queue;

use phpOMS\Stdlib\Queue\PriorityMode;
use phpOMS\Stdlib\Queue\PriorityQueue;

/**
 * @testdox phpOMS\tests\Stdlib\Queue\PriorityQueueTest: Priority queue
 *
 * @internal
 */
class PriorityQueueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The queue has the expected default values and functionality after initialization
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testDefault() : void
    {
        $queue = new PriorityQueue();
        self::assertEquals(0, $queue->count());
        self::assertEquals([], $queue->get(1));
        self::assertEquals([], $queue->pop());
        self::assertEquals([], $queue->getAll());
        self::assertEquals(PriorityMode::FIFO, $queue->getType());
    }

    /**
     * @testdox Queue elements can be added with the default value of 1.0 and returned
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testInputOutput() : void
    {
        $queue = new PriorityQueue();

        self::assertGreaterThan(0, $id = $queue->insert('a'));
        self::assertEquals(['data' => 'a', 'priority' => 1.0], $queue->get($id));
    }

    /**
     * @testdox Queue elements can be added with a priority
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testAddWithPriority() : void
    {
        $queue = new PriorityQueue();

        self::assertGreaterThan(0, $id = $queue->insert('b', 2));
        self::assertEquals(2, $queue->get($id)['priority']);

        self::assertGreaterThan(0, $id = $queue->insert('c', -1));
        self::assertEquals(-1, $queue->get($id)['priority']);
    }

    /**
     * @testdox The priority queue returns the correct amount of elements it holds
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testCount() : void
    {
        $queue = new PriorityQueue();

        $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);
        self::assertEquals(3, $queue->count());
    }

    /**
     * @testdox A queue element can be removed
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testRemove() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        self::assertTrue($queue->delete($id));
        self::assertEquals(2, $queue->count());
    }

    /**
     * @testdox A none-existing queue element id cannot be removed
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        $queue->delete($id);
        self::assertFalse($queue->delete($id));
        self::assertEquals(2, $queue->count());
    }

    /**
     * @testdox A removed or none-existing queue element returns a empty data
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testInvalidGet() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        $queue->delete($id);

        self::assertEquals([], $queue->get($id));
    }

    /**
     * @testdox The priority of all queue elements can be uniformly increased
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testPriorityIncreaseAll() : void
    {
        $queue = new PriorityQueue();

        $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        $queue->increaseAll(2);
        self::assertEquals([
                ['data' => 'c', 'priority' => 1],
                ['data' => 'b', 'priority' => 4],
                ['data' => 'a', 'priority' => 3],
            ], \array_values($queue->getAll())
        );
    }

    /**
     * @testdox The priority or a queue element can be changed
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testPriorityChange() : void
    {
        $queue = new PriorityQueue();
        $id1   = $queue->insert('a');
        $queue->setPriority($id1, 3);
        self::assertEquals(3, $queue->get($id1)['priority']);

        $queue = new PriorityQueue(PriorityMode::HIGHEST);
        $id1   = $queue->insert('a');
        $queue->setPriority($id1, 3);
        self::assertEquals(3, $queue->get($id1)['priority']);
    }

    /**
     * @testdox The queue can be serialized and unserialized
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testSerialize() : void
    {
        $queue = new PriorityQueue();

        $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        $queue2 = new PriorityQueue();
        $queue2->unserialize($queue->serialize());

        self::assertEquals($queue->serialize(), $queue2->serialize());
    }

    /**
     * @testdox A queue element can be popped from the que which also removes it from the queue
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testPop() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        self::assertEquals(['data' => 'a', 'priority' => 1.0], $queue->pop());

        self::assertEquals([], $queue->get($id));
        self::assertEquals(0, $queue->count());
    }

    /**
     * @testdox A FIFO queue returns the elements in FIFO order
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testFIFO() : void
    {
        $queue = new PriorityQueue(PriorityMode::FIFO);

        $queue->insert('a', -2);
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        self::assertEquals(['data' => 'a', 'priority' => -2], $queue->pop());
        self::assertEquals(['data' => 'b', 'priority' => 2], $queue->pop());
        self::assertEquals(['data' => 'c', 'priority' => -1], $queue->pop());
    }

    /**
     * @testdox A LIFO queue returns the elements in LIFO order
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testLIFO() : void
    {
        $queue = new PriorityQueue(PriorityMode::LIFO);

        $queue->insert('a', -2);
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        self::assertEquals(['data' => 'c', 'priority' => -1], $queue->pop());
        self::assertEquals(['data' => 'b', 'priority' => 2], $queue->pop());
        self::assertEquals(['data' => 'a', 'priority' => -2], $queue->pop());
    }

    /**
     * @testdox A highest queue returns the elements in highest priority order
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testHighest() : void
    {
        $queue = new PriorityQueue(PriorityMode::HIGHEST);

        $queue->insert('a', -2);
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        self::assertEquals(['data' => 'b', 'priority' => 2], $queue->pop());
        self::assertEquals(['data' => 'c', 'priority' => -1], $queue->pop());
        self::assertEquals(['data' => 'a', 'priority' => -2], $queue->pop());
    }

    /**
     * @testdox A lowest queue returns the elements in lowest priority order
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testLowest() : void
    {
        $queue = new PriorityQueue(PriorityMode::LOWEST);

        $queue->insert('a', -2);
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        self::assertEquals(['data' => 'a', 'priority' => -2], $queue->pop());
        self::assertEquals(['data' => 'c', 'priority' => -1], $queue->pop());
        self::assertEquals(['data' => 'b', 'priority' => 2], $queue->pop());
    }

    /**
     * @testdox A invalid priority queue type throws a InvalidEnumValue
     * @covers phpOMS\Stdlib\Queue\PriorityQueue
     * @group framework
     */
    public function testInvalidPriority() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $queue = new PriorityQueue(99999);
    }
}
