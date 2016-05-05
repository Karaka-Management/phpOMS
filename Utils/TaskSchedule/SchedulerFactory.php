<?php

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

final class SchedulerFactory
{
    public static function create() : ScheduleInterface
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