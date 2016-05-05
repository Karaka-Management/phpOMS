<?php

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

final class TaskFactory
{
    public static function create(Interval $interval = null, string $cmd = '') : TaskInterface
    {
        switch (OperatingSystem::getSystem()) {
            case SystemType::WIN:
                return new Schedule($interval, $cmd);
            case SystemType::LINUX:
                return new CronJob($interval, $cmd);
            default:
                throw new \Exception('Unsupported system.');
        }
    }
}