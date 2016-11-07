<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\TaskSchedule;

/**
 * Scheduler abstract.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class SchedulerAbstract
{
    /**
     * tasks.
     *
     * @var TaskAbstract[]
     * @since 1.0.0
     */
    protected $tasks = [];

    /**
     * Add task
     *
     * @param TaskAbstract $task Task to add
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function add(TaskAbstract $task)
    {
        $this->tasks[$task->getId()] = $task;
    }

    /**
     * Remove task
     *
     * @param mixed $id Task id
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function remove(string $id) : bool
    {
        if (isset($this->tasks[$id])) {
            unset($this->tasks[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get task
     *
     * @param mixed $id Task id
     *
     * @return TaskAbstract|null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function get(string $id)
    {
        return $this->tasks[$id] ?? null;
    }

    /**
     * Get all tasks
     *
     * @return TaskAbstract[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getAll() : array
    {
        return $this->tasks;
    }

    /**
     * Set task
     *
     * @param TaskAbstract $task Task to edit
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function set(TaskAbstract $task)
    {
        $this->tasks[$task->getId()] = $task;
    }

    /**
     * Save tasks
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    abstract public function save();
}
