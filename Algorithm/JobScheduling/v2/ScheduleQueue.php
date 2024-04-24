<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Scheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling;

/**
 * Scheduler.
 *
 * @package phpOMS\Scheduling
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ScheduleQueue
{
    /**
     * Queue
     *
     * @var Job[]
     * @since 1.0.0
     */
    public array $queue = [];

    /**
     * Get element from queue
     *
     * @param int $size Amount of elements to return
     * @param int $type Priority type to use for return
     *
     * @return Job[]
     *
     * @since 1.0.0
     */
    public function get(int $size = 1, int $type = PriorityMode::FIFO) : array
    {
        $jobs = [];
        $keys = \array_keys($this->queue);

        switch ($type) {
            case PriorityMode::FIFO:
                for ($i = 0; $i < $size; ++$i) {
                    $jobs[$i] = $this->queue[$keys[$i]];
                }

                break;
            case PriorityMode::LIFO:
                for ($i = \count($this->queue) - $size - 1; $i < $size; ++$i) {
                    $jobs[$i] = $this->queue[$keys[$i]];
                }

                break;
            case PriorityMode::PRIORITY:
                $queue = $this->queue;
                \uasort($queue, function (Job $a, Job $b) {
                    return $a->priority <=> $b->priority;
                });

                $jobs = \array_slice($queue, 0, $size, true);

                break;
            case PriorityMode::VALUE:
                $queue = $this->queue;
                \uasort($queue, function (Job $a, Job $b) {
                    return $b->value <=> $a->value;
                });

                $jobs = \array_slice($queue, 0, $size, true);

                break;
            case PriorityMode::COST:
                $queue = $this->queue;
                \uasort($queue, function (Job $a, Job $b) {
                    return $a->cost <=> $b->cost;
                });

                $jobs = \array_slice($queue, 0, $size, true);

                break;
            case PriorityMode::PROFIT:
                $queue = $this->queue;
                \uasort($queue, function (Job $a, Job $b) {
                    return $b->getProfit() <=> $a->getProfit();
                });

                $jobs = \array_slice($queue, 0, $size, true);

                break;
            case PriorityMode::HOLD:
                $queue = $this->queue;
                \uasort($queue, function (Job $a, Job $b) {
                    return $b->onhold <=> $a->onhold;
                });

                $jobs = \array_slice($queue, 0, $size, true);

                break;
            case PriorityMode::EARLIEST_DEADLINE:
                $queue = $this->queue;
                \uasort($queue, function (Job $a, Job $b) {
                    return $a->deadline->getTimestamp() <=> $b->deadline->getTimestamp();
                });

                $jobs = \array_slice($queue, 0, $size, true);

                break;
        }

        return $jobs;
    }

    /**
     * Insert new element into queue
     *
     * @param int $id  Element id
     * @param Job $job Element to add
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function insert(int $id, Job $job) : void
    {
        $this->queue[$id] = $job;
    }

    /**
     * Pop elements from the queue.
     *
     * This also removes the elements from the queue
     *
     * @param int $size Amount of elements to return
     * @param int $type Priority type to use for return
     *
     * @return Job[]
     *
     * @since 1.0.0
     */
    public function pop(int $size = 1, int $type = PriorityMode::FIFO) : array
    {
        $jobs = $this->get($size, $type);
        foreach ($jobs as $id => $_) {
            unset($this->queue[$id]);
        }

        return $jobs;
    }

    /**
     * Increases the hold counter of an element
     *
     * @param int $id Id of the element (0 = all elements)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function bumpHold(int $id = 0) : void
    {
        if ($id === 0) {
            foreach ($this->queue as $job) {
                ++$job->onhold;
            }
        } else {
            ++$this->queue[$id]->onhold;
        }
    }

    /**
     * Change the priority of an element
     *
     * @param int   $id       Id of the element (0 = all elements)
     * @param float $priority Priority to increase by
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function adjustPriority(int $id = 0, float $priority = 0.1) : void
    {
        if ($id === 0) {
            foreach ($this->queue as $job) {
                $job->priority += $priority;
            }
        } else {
            $this->queue[$id]->priority += $priority;
        }
    }

    /**
     * Remove an element from the queue
     *
     * @param int $id Id of the element
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function remove(int $id) : void
    {
        unset($this->queue[$id]);
    }
}
