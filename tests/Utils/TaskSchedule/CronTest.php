<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\CronJob;

/**
 * @internal
 */
class CronTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new Cron());
    }

    public function testCRUD() : void
    {
        if (\stripos(\PHP_OS, 'LINUX') !== false && \stripos(__DIR__, '/travis/') === false) {
            self::assertTrue(Cron::guessBin());
            $cron = new Cron();

            self::assertEquals([], $cron->getAllByName('testCronJob', false));

            $job = new CronJob('testCronJob', 'testFile', '0 0 1 1 *');
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
