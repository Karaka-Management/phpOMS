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

use phpOMS\Utils\TaskSchedule\CronJob;
use phpOMS\Utils\TaskSchedule\Schedule;
use phpOMS\Utils\TaskSchedule\TaskFactory;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\TaskFactory::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\TaskFactoryTest: Task factory for creating cron jobs/tasks')]
final class TaskFactoryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The correct task is crated depending on the operating system')]
    public function testFactory() : void
    {
        self::assertTrue((TaskFactory::create() instanceof CronJob) || (TaskFactory::create() instanceof Schedule));
    }
}
