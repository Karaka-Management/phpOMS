<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

/**
 * Scheduler factory.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class SchedulerFactory
{
    /**
     * Create scheduler instance.
     *
     * @return SchedulerAbstract
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public static function create() : SchedulerAbstract
    {
        switch (OperatingSystem::getSystem()) {
            case SystemType::WIN:
                return new TaskScheduler();
            case SystemType::LINUX:
                return new Cron();
            default:
                throw new \Exception('Unsupported system.');
        }
    }
}
