<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\SchedulerFactory;
use phpOMS\Utils\TaskSchedule\TaskScheduler;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\SchedulerFactoryTest: Scheduler factory for creating cron/task handlers
 *
 * @internal
 */
class SchedulerFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The correct schudeler is crated depending on the operating system
     * @covers phpOMS\Utils\TaskSchedule\SchedulerAbstract
     */
    public function testCreate() : void
    {
        self::assertTrue((SchedulerFactory::create() instanceof Cron) || (SchedulerFactory::create() instanceof TaskScheduler));

        // todo: make full test here by defining schtask or crontab path
        // todo: create task
        // todo: get task
        // todo: remove task
    }
}
