<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
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
final class TaskFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The correct task is crated depending on the operating system
     * @covers phpOMS\Utils\TaskSchedule\TaskFactory
     * @group framework
     */
    public function testFactory() : void
    {
        self::assertTrue((TaskFactory::create() instanceof CronJob) || (TaskFactory::create() instanceof Schedule));
    }
}
