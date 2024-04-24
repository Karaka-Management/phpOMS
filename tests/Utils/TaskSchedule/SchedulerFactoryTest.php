<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\SchedulerFactory;
use phpOMS\Utils\TaskSchedule\TaskScheduler;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\SchedulerFactory::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\SchedulerFactoryTest: Scheduler factory for creating cron/task handlers')]
final class SchedulerFactoryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The correct scheduler is crated depending on the operating system')]
    public function testCreate() : void
    {
        self::assertTrue((SchedulerFactory::create() instanceof Cron) || (SchedulerFactory::create() instanceof TaskScheduler));
    }
}
