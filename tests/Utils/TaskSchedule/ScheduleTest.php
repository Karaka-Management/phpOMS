<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Schedule;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\ScheduleTest: Schedule/task
 *
 * @internal
 */
final class ScheduleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The task has the expected default values after initialization
     * @covers phpOMS\Utils\TaskSchedule\Schedule
     * @group framework
     */
    public function testDefault() : void
    {
        $job = new Schedule('');
        self::assertEquals('', $job->__toString());
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\TaskAbstract', $job);
    }

    /**
     * @testdox A task can be created from an array and rendered
     * @covers phpOMS\Utils\TaskSchedule\Schedule
     *
     * @todo the interval has to be implemented!
     *
     * @group framework
     */
    public function testCreateJobWithData() : void
    {
        $job = Schedule::createWith(['hostname', 'testname', '2018-06-02', 'Ready', 'Background', 'N/A', '1', '', 'testcmd', '/var/usr', 'comment']);
        self::assertEquals('/tn testname asdf testcmd', $job->__toString());
    }
}
