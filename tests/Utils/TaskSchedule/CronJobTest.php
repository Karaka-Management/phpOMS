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

use phpOMS\Utils\TaskSchedule\CronJob;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\CronJobTest: Cron job
 *
 * @internal
 */
final class CronJobTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The cron job has the expected default values after initialization
     * @covers phpOMS\Utils\TaskSchedule\CronJob
     * @group framework
     */
    public function testDefault() : void
    {
        $job = new CronJob('');
        self::assertEquals('', $job->__toString());
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\TaskAbstract', $job);
    }

    /**
     * @testdox A cron job can be created from an array and rendered
     * @covers phpOMS\Utils\TaskSchedule\CronJob
     * @group framework
     */
    public function testCreateJobWithData() : void
    {
        $job = CronJob::createWith(['testname', '*', '*', '*', '*', '*', 'testcmd']);
        self::assertEquals('* * * * * testcmd # name="testname" ', $job->__toString());
    }
}
