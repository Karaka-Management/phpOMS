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

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

/**
 * Task factory.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
final class TaskFactory
{
    /**
     * Create task instance.
     *
     * @param string $id Task id
     * @param string   $cmd      Command to run
     *
     * @return TaskInterface
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function create(string $id = null, string $cmd = '') : TaskAbstract
    {
        switch (OperatingSystem::getSystem()) {
            case SystemType::WIN:
                return new Schedule($id, $cmd);
            case SystemType::LINUX:
                return new CronJob($id, $cmd);
            default:
                throw new \Exception('Unsupported system.');
        }
    }
}