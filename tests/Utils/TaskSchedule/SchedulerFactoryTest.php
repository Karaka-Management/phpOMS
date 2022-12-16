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

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\SchedulerFactory;
use phpOMS\Utils\TaskSchedule\TaskScheduler;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\SchedulerFactoryTest: Scheduler factory for creating cron/task handlers
 *
 * @internal
 */
final class SchedulerFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The correct scheduler is crated depending on the operating system
     * @covers phpOMS\Utils\TaskSchedule\SchedulerFactory
     * @group framework
     */
    public function testCreate() : void
    {
        self::assertTrue((SchedulerFactory::create() instanceof Cron) || (SchedulerFactory::create() instanceof TaskScheduler));
    }
}
