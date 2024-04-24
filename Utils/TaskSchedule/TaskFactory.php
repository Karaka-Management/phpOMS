<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

/**
 * Task factory.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class TaskFactory
{
    /**
     * Create task instance.
     *
     * @param string $id  Task id
     * @param string $cmd Command to run
     *
     * @return TaskAbstract
     *
     * @throws \Exception This exception is thrown if the operating system is not supported
     *
     * @since 1.0.0
     */
    public static function create(string $id = '', string $cmd = '') : TaskAbstract
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
