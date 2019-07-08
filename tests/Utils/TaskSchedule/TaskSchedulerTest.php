<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Schedule;
use phpOMS\Utils\TaskSchedule\TaskScheduler;

/**
 * @internal
 */
class TaskSchedulerTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new TaskScheduler());
    }

    public function testCRUD() : void
    {
        if (\stristr(\PHP_OS, 'WIN')) {
            self::assertTrue(TaskScheduler::guessBin());
            $cron = new TaskScheduler();

            self::assertEquals([], $cron->getAllByName('testCronJob', false));

            $job = new Schedule('testCronJob', 'testFile', '0 0 1 1 *');
            $cron->create($job);

            self::assertTrue(!empty($cron->getAllByName('testCronJob', false)));
            if (!empty($cron->getAllByName('testCronJob', false))) {
                self::assertEquals('testFile', $cron->getAllByName('testCronJob', false)[0]->getCommand());
            }

            $job->setCommand('testFile2');
            $cron->update($job);

            self::assertTrue(!empty($cron->getAllByName('testCronJob', false)));
            if (!empty($cron->getAllByName('testCronJob', false))) {
                self::assertEquals('testFile2', $cron->getAllByName('testCronJob', false)[0]->getCommand());
            }

            $cron->delete($job);
            self::assertEquals([], $cron->getAllByName('testCronJob', false));
        }
    }
}
