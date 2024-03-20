<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Schedule;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\Schedule::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\ScheduleTest: Schedule/task')]
final class ScheduleTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The task has the expected default values after initialization')]
    public function testDefault() : void
    {
        $job = new Schedule('');
        self::assertEquals('', $job->__toString());
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\TaskAbstract', $job);
    }

    
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A task can be created from an array and rendered')]
    public function testCreateJobWithData() : void
    {
        $job = Schedule::createWith(['hostname', 'testname', '2018-06-02', 'Ready', 'Background', 'N/A', '1', 'INTERVAL', 'testcmd', '/var/usr', 'comment']);
        self::assertEquals('/tn testname INTERVAL testcmd', $job->__toString());
    }
}
