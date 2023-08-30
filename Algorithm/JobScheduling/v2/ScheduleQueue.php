<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
    public array $queue = [];

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

    public function insert(int $id, Job $job) : void
    {
        $this->queue[$id] = $job;
    }

    public function pop(int $size = 1, int $type = PriorityMode::FIFO) : array
    {
        $jobs = $this->get($size, $type);
        foreach ($jobs as $id => $_) {
            unset($this->queue[$id]);
        }

        return $jobs;
    }

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

    public function remove(string $id) : void
    {
        unset($this->queue[$id]);
    }
}
