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

namespace phpOMS\tests\Stdlib\Queue;

use phpOMS\Stdlib\Queue\PriorityMode;
use phpOMS\Stdlib\Queue\PriorityQueue;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Queue\PriorityQueue::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Queue\PriorityQueueTest: Priority queue')]
final class PriorityQueueTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The queue has the expected default values and functionality after initialization')]
    public function testDefault() : void
    {
        $queue = new PriorityQueue();
        self::assertEquals(0, $queue->count());
        self::assertEquals([], $queue->get(1));
        self::assertEquals([], $queue->pop());
        self::assertEquals([], $queue->getAll());
        self::assertEquals(PriorityMode::FIFO, $queue->getType());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Queue elements can be added with the default value of 1.0 and returned')]
    public function testInputOutput() : void
    {
        $queue = new PriorityQueue();

        self::assertGreaterThan(0, $id = $queue->insert('a'));
        self::assertEquals(['data' => 'a', 'priority' => 1.0], $queue->get($id));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Queue elements can be added with a priority')]
    public function testAddWithPriority() : void
    {
        $queue = new PriorityQueue();

        self::assertGreaterThan(0, $id = $queue->insert('b', 2));
        self::assertEquals(2, $queue->get($id)['priority']);

        self::assertGreaterThan(0, $id = $queue->insert('c', -1));
        self::assertEquals(-1, $queue->get($id)['priority']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The priority queue returns the correct amount of elements it holds')]
    public function testCount() : void
    {
        $queue = new PriorityQueue();

        $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);
        self::assertEquals(3, $queue->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A queue element can be removed')]
    public function testRemove() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        $queue->insert('b', 2);
        $queue->insert('c', -1);

        self::assertTrue($queue->delete($id));
        self::assertEquals(2, $queue->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing queue element id cannot be removed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A removed or none-existing queue element returns a empty data')]
    public function testInvalidGet() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        $queue->delete($id);

        self::assertEquals([], $queue->get($id));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The priority of all queue elements can be uniformly increased')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The priority or a queue element can be changed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The queue can be serialized and unserialized')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A queue element can be popped from the que which also removes it from the queue')]
    public function testPop() : void
    {
        $queue = new PriorityQueue();

        $id = $queue->insert('a');
        self::assertEquals(['data' => 'a', 'priority' => 1.0], $queue->pop());

        self::assertEquals([], $queue->get($id));
        self::assertEquals(0, $queue->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A FIFO queue returns the elements in FIFO order')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A LIFO queue returns the elements in LIFO order')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A highest queue returns the elements in highest priority order')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A lowest queue returns the elements in lowest priority order')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid priority queue type throws a InvalidEnumValue')]
    public function testInvalidPriority() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $queue = new PriorityQueue(99999);
    }
}
