<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

/**
 * Task scheduler class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
class TaskScheduler extends SchedulerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function create(TaskAbstract $task) : void
    {
        $this->run('/Create ' . $task->__toString());
    }

    /**
     * {@inheritdoc}
     */
    public function update(TaskAbstract $task) : void
    {
        $this->run('/Change ' . $task->__toString());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByName(string $name) : void
    {
        $this->run('/Delete /TN ' . $name);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TaskAbstract $task) : void
    {
        $this->deleteByName($task->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $lines = \explode("\n", $this->normalize($this->run('/query /v /fo CSV')));
        unset($lines[0]);

        $jobs = [];
        foreach ($lines as $line) {
            $jobs[] = Schedule::createWith(\str_getcsv($line));
        }

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllByName(string $name, bool $exact = true) : array
    {
        if ($exact) {
            $lines = \explode("\n", $this->normalize($this->run('/query /v /fo CSV /tn ' . \escapeshellarg($name))));
            unset($lines[0]);

            $jobs = [];
            foreach ($lines as $line) {
                $jobs[] = Schedule::createWith(\str_getcsv($line));
            }
        } else {
            $lines = \explode("\n", $this->normalize($this->run('/query /v /fo CSV')));
            unset($lines[0]);

            $jobs = [];
            foreach ($lines as $line) {
                $line = \str_getcsv($line);

                if (\stripos($line[1], $name) !== false) {
                    $jobs[] = Schedule::createWith($line);
                }
            }
        }

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function reload() : void
    {
    }
}
