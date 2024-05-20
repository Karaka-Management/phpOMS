<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Schedule;
use phpOMS\Utils\TaskSchedule\SchedulerAbstract;
use phpOMS\Utils\TaskSchedule\TaskScheduler;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\TaskScheduler::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\TaskSchedulerTest: Task schedule handler')]
final class TaskSchedulerTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (\stripos(\PHP_OS, 'WIN') === false) {
            $this->markTestSkipped(
              'The OS is not windows.'
            );
        }

        if (!SchedulerAbstract::guessBin()) {
            $this->markTestSkipped(
                'No scheduler available'
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The task handler has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new TaskScheduler());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The task binary location path can be guessed')]
    public function testGuessBinary() : void
    {
        self::assertTrue(TaskScheduler::guessBin());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A task can be created and returned')]
    public function testTaskScheduleInputOutput() : void
    {
        $task = new TaskScheduler();
        $job  = new Schedule('testTaskSchedule', 'testFile', '0 0 1 1 *');
        $task->create($job);

        self::assertTrue(!empty($task->getAllByName('testTaskSchedule', false)));
        if (!empty($task->getAllByName('testTaskSchedule', false))) {
            self::assertEquals('testFile', $task->getAllByName('testTaskSchedule', false)[0]->getCommand());
        }

        $task->delete($job);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing task name cannot be returned')]
    public function testInvalidTaskScheduleName() : void
    {
        $task = new TaskScheduler();
        self::assertEquals([], $task->getAllByName('testTaskSchedule', false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A task can be updated')]
    public function testTaskScheduleUpdate() : void
    {
        $task = new TaskScheduler();
        $job  = new Schedule('testTaskSchedule', 'testFile', '0 0 1 1 *');
        $task->create($job);

        $job->setCommand('testFile2');
        $task->update($job);

        self::assertTrue(!empty($task->getAllByName('testTaskSchedule', false)));
        if (!empty($task->getAllByName('testTaskSchedule', false))) {
            self::assertEquals('testFile2', $task->getAllByName('testTaskSchedule', false)[0]->getCommand());
        }

        $task->delete($job);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A task can be deleted')]
    public function testDelete() : void
    {
        $task = new TaskScheduler();
        $job  = new Schedule('testTaskSchedule', 'testFile', '0 0 1 1 *');
        $task->create($job);

        self::assertTrue(!empty($task->getAllByName('testTaskSchedule', false)));
        $task->delete($job);
        self::assertEquals([], $task->getAllByName('testTaskSchedule', false));
    }
}
