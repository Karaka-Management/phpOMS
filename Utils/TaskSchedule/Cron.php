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
 * Cron class.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Cron implements ScheduleInterface
{
    public function __construct()
    {

    }

    public function add(TaskInterface $task)
    {
        // TODO: Implement add() method.
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    public function get(string $id)
    {
        // TODO: Implement get() method.
    }

    public function list()
    {
        $output = shell_exec('crontab -l');

        return $output;
    }

    public function set(TaskInterface $task)
    {
        // TODO: Implement set() method.
    }

    public function save()
    {

    }

    public function run(string $cmd)
    {
        // TODO: Implement run() method.
    }
}
