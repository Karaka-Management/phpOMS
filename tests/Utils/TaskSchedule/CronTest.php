<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\CronJob;

class CronTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new Cron());
    }

    public function testCRUD()
    {
        if (\stristr(PHP_OS, 'LINUX')) {
            $cron = new Cron();

            self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\NullCronJob', $cron->getAllByName('testCronJob', false));
            
            $cron->create(
                new CronJob('testCronJob', 'testFile')
            );
            self::assertEquals('testFile', $cron->getRun());

            $cron->update(
                new CronJob('testCronJob', 'testFile2')
            );
            self::assertEquals('testFile2', $cron->getRun());

            $cron->delete(
                new CronJob('testCronJob', 'testFile2')
            );
            self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\NullCronJob', $cron->getAllByName('testCronJob', false));
        }
    }
}
