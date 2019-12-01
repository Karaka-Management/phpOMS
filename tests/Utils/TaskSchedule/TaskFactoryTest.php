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

use phpOMS\Utils\TaskSchedule\CronJob;
use phpOMS\Utils\TaskSchedule\Schedule;
use phpOMS\Utils\TaskSchedule\TaskFactory;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\TaskFactoryTest: Task factory for creating cron jobs/tasks
 *
 * @internal
 */
class TaskFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The correct task is crated depending on the operating system
     * @covers phpOMS\Utils\TaskSchedule\TaskFactory
     */
    public function testFactory() : void
    {
        self::assertTrue((TaskFactory::create() instanceof CronJob) || (TaskFactory::create() instanceof Schedule));
    }
}
