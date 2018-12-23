<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Stdlib\Queue;

use phpOMS\Stdlib\Queue\PriorityMode;
use phpOMS\Stdlib\Queue\PriorityQueue;

class PriorityQueueTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $queue = new PriorityQueue();
        self::assertEquals(0, $queue->count());
        self::assertEquals([], $queue->get(1));
        self::assertEquals([], $queue->pop());
        self::assertEquals([], $queue->getAll());
        self::assertEquals(PriorityMode::FIFO, $queue->getType());
    }

    public function testGeneral()
    {
        $queue = new PriorityQueue();

        self::assertGreaterThan(0, $id1 = $queue->insert('a'));
        self::assertEquals(1, $queue->count());

        self::assertGreaterThan(0, $id2 = $queue->insert('b', 2));
        self::assertEquals(2, $queue->count());

        self::assertGreaterThan(0, $id3 = $queue->insert('c', -1));
        self::assertEquals(3, $queue->count());

        $queue->increaseAll(2);
        self::assertEquals([
                ['data' => 'c', 'priority' => 1],
                ['data' => 'b', 'priority' => 4],
                ['data' => 'a', 'priority' => 3],
            ], \array_values($queue->getAll())
        );

        $queue->setPriority($id1, 3);
        self::assertEquals(3, $queue->get($id1)['priority']);

        $queue2 = new PriorityQueue();
        $queue2->unserialize($queue->serialize());

        self::assertEquals($queue->serialize(), $queue2->serialize());
    }

    public function testFIFO()
    {
        $queue = new PriorityQueue(PriorityMode::FIFO);

        self::assertGreaterThan(0, $id1 = $queue->insert('a'));
        self::assertEquals(1, $queue->count());

        self::assertGreaterThan(0, $id2 = $queue->insert('b', 2));
        self::assertEquals(2, $queue->count());

        self::assertGreaterThan(0, $id3 = $queue->insert('c', -1));
        self::assertEquals(3, $queue->count());

        self::assertEquals([
                ['data' => 'c', 'priority' => -1],
                ['data' => 'b', 'priority' => 2],
                ['data' => 'a', 'priority' => 1],
            ], \array_values($queue->getAll())
        );

        $queue->setPriority($id1, -2);
        self::assertEquals(-2, $queue->get($id1)['priority']);

        self::assertEquals([
                ['data' => 'c', 'priority' => -1],
                ['data' => 'b', 'priority' => 2],
                ['data' => 'a', 'priority' => -2],
            ], \array_values($queue->getAll())
        );

        self::assertEquals(['data' => 'a', 'priority' => -2], $queue->get($id1));
        self::assertEquals(['data' => 'a', 'priority' => -2], $queue->pop());
        self::assertEquals(2, $queue->count());

        self::assertTrue($queue->delete($id3));
        self::assertFalse($queue->delete($id3));
        self::assertEquals(1, $queue->count());
    }

    public function testLIFO()
    {
        $queue = new PriorityQueue(PriorityMode::LIFO);

        self::assertGreaterThan(0, $id1 = $queue->insert('a'));
        self::assertEquals(1, $queue->count());

        self::assertGreaterThan(0, $id2 = $queue->insert('b', 2));
        self::assertEquals(2, $queue->count());

        self::assertGreaterThan(0, $id3 = $queue->insert('c', -1));
        self::assertEquals(3, $queue->count());

        self::assertEquals([
                ['data' => 'a', 'priority' => 1],
                ['data' => 'b', 'priority' => 2],
                ['data' => 'c', 'priority' => -1],
            ], \array_values($queue->getAll())
        );

        $queue->setPriority($id1, 3);
        self::assertEquals(3, $queue->get($id1)['priority']);
        self::assertEquals([
                ['data' => 'a', 'priority' => 3],
                ['data' => 'b', 'priority' => 2],
                ['data' => 'c', 'priority' => -1],
            ], \array_values($queue->getAll())
        );

        self::assertEquals(['data' => 'c', 'priority' => -1], $queue->get($id3));
        self::assertEquals(['data' => 'c', 'priority' => -1], $queue->pop());
        self::assertEquals(2, $queue->count());

        self::assertTrue($queue->delete($id1));
        self::assertFalse($queue->delete($id1));
        self::assertEquals(1, $queue->count());
    }

    public function testHighest()
    {
        $queue = new PriorityQueue(PriorityMode::HIGHEST);

        self::assertGreaterThan(0, $id1 = $queue->insert('a'));
        self::assertEquals(1, $queue->count());

        self::assertGreaterThan(0, $id2 = $queue->insert('b', 2));
        self::assertEquals(2, $queue->count());

        self::assertGreaterThan(0, $id3 = $queue->insert('c', -1));
        self::assertEquals(3, $queue->count());

        self::assertEquals([
                ['data' => 'c', 'priority' => -1],
                ['data' => 'a', 'priority' => 1],
                ['data' => 'b', 'priority' => 2],
            ], \array_values($queue->getAll())
        );

        $queue->setPriority($id1, 3);
        self::assertEquals(3, $queue->get($id1)['priority']);
        self::assertEquals([
                ['data' => 'c', 'priority' => -1],
                ['data' => 'b', 'priority' => 2],
                ['data' => 'a', 'priority' => 3],
            ], \array_values($queue->getAll())
        );

        self::assertEquals(['data' => 'a', 'priority' => 3], $queue->get($id1));
        self::assertEquals(['data' => 'a', 'priority' => 3], $queue->pop());
        self::assertEquals(2, $queue->count());

        self::assertTrue($queue->delete($id2));
        self::assertFalse($queue->delete($id2));
        self::assertEquals(1, $queue->count());
    }

    public function testLowest()
    {
        $queue = new PriorityQueue(PriorityMode::LOWEST);

        self::assertGreaterThan(0, $id1 = $queue->insert('a'));
        self::assertEquals(1, $queue->count());

        self::assertGreaterThan(0, $id2 = $queue->insert('b', 2));
        self::assertEquals(2, $queue->count());

        self::assertGreaterThan(0, $id3 = $queue->insert('c', -1));
        self::assertEquals(3, $queue->count());

        self::assertEquals([
                ['data' => 'b', 'priority' => 2],
                ['data' => 'a', 'priority' => 1],
                ['data' => 'c', 'priority' => -1],
            ], \array_values($queue->getAll())
        );

        $queue->setPriority($id3, 2);
        self::assertEquals(2, $queue->get($id3)['priority']);
        self::assertEquals([
                ['data' => 'b', 'priority' => 2],
                ['data' => 'c', 'priority' => 2],
                ['data' => 'a', 'priority' => 1],
            ], \array_values($queue->getAll())
        );

        self::assertEquals(['data' => 'a', 'priority' => 1], $queue->get($id1));
        self::assertEquals(['data' => 'a', 'priority' => 1], $queue->pop());
        self::assertEquals(2, $queue->count());

        self::assertTrue($queue->delete($id2));
        self::assertFalse($queue->delete($id2));
        self::assertEquals(1, $queue->count());
    }

    /**
     * @expectedException \phpOMS\Stdlib\Base\Exception\InvalidEnumValue
     */
    public function testInvalidPriority()
    {
        $queue = new PriorityQueue(99999);
    }
}
