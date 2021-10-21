<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Schedule;
use phpOMS\Utils\TaskSchedule\TaskScheduler;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\TaskSchedulerTest: Task schedule handler
 *
 * @internal
 */
final class TaskSchedulerTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (\stripos(\PHP_OS, 'WIN') === false) {
            $this->markTestSkipped(
              'The OS is not windows.'
            );
        }
    }

    /**
     * @testdox The task handler has the expected default values after initialization
     * @covers phpOMS\Utils\TaskSchedule\TaskScheduler<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new TaskScheduler());
    }

    /**
     * @testdox The task binary location path can be guessed
     * @covers phpOMS\Utils\TaskSchedule\TaskScheduler<extended>
     * @group framework
     */
    public function testGuessBinary() : void
    {
        self::assertTrue(TaskScheduler::guessBin());
    }

    /**
     * @testdox A task can be created and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskScheduler<extended>
     * @group framework
     */
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

    /**
     * @testdox A none-existing task name cannot be returned
     * @covers phpOMS\Utils\TaskSchedule\TaskScheduler<extended>
     * @group framework
     */
    public function testInvalidTaskScheduleName() : void
    {
        $task = new TaskScheduler();
        self::assertEquals([], $task->getAllByName('testTaskSchedule', false));
    }

    /**
     * @testdox A task can be updated
     * @covers phpOMS\Utils\TaskSchedule\TaskScheduler<extended>
     * @group framework
     */
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

    /**
     * @testdox A task can be deleted
     * @covers phpOMS\Utils\TaskSchedule\TaskScheduler<extended>
     * @group framework
     */
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
