<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Stdlib\Queue
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Queue;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;

/**
 * Priority queue class.
 *
 * @package phpOMS\Stdlib\Queue
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class PriorityQueue implements \Countable, \Serializable
{
    /**
     * Queue type.
     *
     * @var int
     * @since 1.0.0
     */
    private int $type = PriorityMode::FIFO;

    /**
     * Queue.
     *
     * @var array<int, array{data:mixed, priority:float}>
     * @since 1.0.0
     */
    private array $queue = [];

    /**
     * Constructor.
     *
     * @throws InvalidEnumValue This exception is thrown if the priority mode is invalid
     *
     * @since 1.0.0
     */
    public function __construct(int $type = PriorityMode::FIFO)
    {
        if (!PriorityMode::isValidValue($type)) {
            throw new InvalidEnumValue($type);
        }

        $this->type = $type;
    }

    /**
     * Get priority queue type
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Insert element into queue.
     *
     * @param mixed $data     Queue element
     * @param float $priority Priority of this element
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function insert(mixed $data, float $priority = 1.0) : int
    {
        do {
            $key = \mt_rand();
        } while (isset($this->queue[$key]));

        if (\count($this->queue) === 0) {
            $this->queue[$key] = ['data' => $data, 'priority' => $priority];
        } else {
            $pos = $this->getInsertPosition($priority);

            $this->queue = \array_slice($this->queue, 0, $pos, true)
                + [$key => ['data' => $data, 'priority' => $priority]]
                + \array_slice($this->queue, $pos, null, true);
        }

        return $key;
    }

    /**
     * Get insert position
     *
     * @param float $priority Priority of new element
     *
     * @return int
     *
     * @throws InvalidEnumValue
     *
     * @since 1.0.0
     */
    private function getInsertPosition(float $priority) : int
    {
        switch($this->type) {
            case PriorityMode::FIFO:
                return $this->getInsertFIFO($priority);
            case PriorityMode::LIFO:
                return $this->getInsertLIFO($priority);
            case PriorityMode::HIGHEST:
                return $this->getInsertHighest($priority);
            case PriorityMode::LOWEST:
                return $this->getInsertLowest($priority);
            default:
                throw new InvalidEnumValue($this->type); // @codeCoverageIgnore
        }
    }

    /**
     * Get insert position
     *
     * @param float $priority Priority of new element
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function getInsertFIFO(float $priority) : int
    {
        return 0;
    }

    /**
     * Get insert position
     *
     * @param float $priority Priority of new element
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function getInsertLIFO(float $priority) : int
    {
        return \count($this->queue);
    }

    /**
     * Get insert position
     *
     * @param float $priority Priority of new element
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function getInsertHighest(float $priority) : int
    {
        $pos = 0;
        foreach ($this->queue as $ele) {
            if ($ele['priority'] > $priority) {
                break;
            }

            ++$pos;
        }

        return $pos;
    }

    /**
     * Get insert position
     *
     * @param float $priority Priority of new element
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function getInsertLowest(float $priority) : int
    {
        $pos = 0;
        foreach ($this->queue as $ele) {
            if ($ele['priority'] < $priority) {
                break;
            }

            ++$pos;
        }

        return $pos;
    }

    /**
     * Increase all queue priorities.
     *
     * @param float $increase Value to increase every element
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function increaseAll(float $increase = 1.0) : void
    {
        foreach ($this->queue as $key => &$ele) {
            $ele['priority'] += $increase;
        }
    }

    /**
     * Pop element.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function pop() : array
    {
        return \array_pop($this->queue) ?? [];
    }

    /**
     * Delete element.
     *
     * @param int $id Element to delete
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function delete(int $id) : bool
    {
        if (isset($this->queue[$id])) {
            unset($this->queue[$id]);

            return true;
        }

        return false;
    }

    /**
     * Set element priority.
     *
     * @param int   $id       Element ID
     * @param float $priority Element priority
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPriority(int $id, float $priority) : void
    {
        if ($this->type === PriorityMode::FIFO || $this->type === PriorityMode::LIFO) {
            $this->queue[$id]['priority'] = $priority;

            return;
        }

        $temp = $this->queue[$id];
        $this->delete($id);

        $pos = $this->getInsertPosition($priority);

        $this->queue = \array_slice($this->queue, 0, $pos, true)
            + [$id => ['data' => $temp['data'], 'priority' => $priority]]
            + \array_slice($this->queue, $pos, null, true);
    }

    /**
     * Get element
     *
     * @param int $id Element ID
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function get(int $id) : array
    {
        return $this->queue[$id] ?? [];
    }

    /**
     * Get element
     *
     * @return array<int, array{data:mixed, priority:float}>
     *
     * @since 1.0.0
     */
    public function getAll() : array
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return \count($this->queue);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return (string) \json_encode($this->queue);
    }

    /**
     * Unserialize queue.
     *
     * @param string $data Data to unserialze
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function unserialize($data) : void
    {
        $this->queue = \json_decode($data, true);
    }
}
