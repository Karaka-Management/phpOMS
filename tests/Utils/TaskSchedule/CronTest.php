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

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\CronJob;
use phpOMS\Utils\TaskSchedule\SchedulerAbstract;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\Cron::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\CronTest: Cron handler')]
final class CronTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (\stripos(\PHP_OS, 'LINUX') === false) {
            $this->markTestSkipped(
              'The OS is not linux.'
            );
        }

        if (!SchedulerAbstract::guessBin()) {
            $this->markTestSkipped(
                'No scheduler available'
            );
        }

        try {
            $cron = new Cron();
            $cron->getAll();
        } catch (\Throwable $_) {
            $this->markTestSkipped(
                'No scheduler available'
            );
        }
    }

    // * * * * * echo "test" > __DIR__ . '/cronjob.log' // evaluate dir
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cron handler has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new Cron());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The cron binary location path can be guessed')]
    public function testGuessBinary() : void
    {
        self::assertTrue(Cron::guessBin());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A cron job can be created and returned')]
    public function testCronJobInputOutput() : void
    {
        $cron = new Cron();
        $job  = new CronJob('testCronJob', 'testFile', '0 0 1 1 *');
        $cron->create($job);

        self::assertTrue(!empty($cron->getAllByName('testCronJob', false)));
        if (!empty($cron->getAllByName('testCronJob', false))) {
            self::assertEquals('testFile', $cron->getAllByName('testCronJob', false)[0]->getCommand());
        }

        $cron->delete($job);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing cron name cannot be returned')]
    public function testInvalidCronJobName() : void
    {
        $cron = new Cron();
        self::assertEquals([], $cron->getAllByName('testCronJob', false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A cron job can be updated')]
    public function testCronJobUpdate() : void
    {
        $cron = new Cron();
        $job  = new CronJob('testCronJob', 'testFile', '0 0 1 1 *');
        $cron->create($job);

        $job->setCommand('testFile2');
        $cron->update($job);

        self::assertTrue(!empty($cron->getAllByName('testCronJob', false)));
        if (!empty($cron->getAllByName('testCronJob', false))) {
            self::assertEquals('testFile2', $cron->getAllByName('testCronJob', false)[0]->getCommand());
        }

        $cron->delete($job);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A cron job can be deleted')]
    public function testDelete() : void
    {
        $cron = new Cron();
        $job  = new CronJob('testCronJob', 'testFile', '0 0 1 1 *');
        $cron->create($job);

        self::assertTrue(!empty($cron->getAllByName('testCronJob', false)));
        $cron->delete($job);
        self::assertEquals([], $cron->getAllByName('testCronJob', false));
    }
}
