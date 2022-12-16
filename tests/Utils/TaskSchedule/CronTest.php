<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Cron;
use phpOMS\Utils\TaskSchedule\CronJob;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\CronTest: Cron handler
 *
 * @internal
 */
final class CronTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        if (\stripos(\PHP_OS, 'LINUX') === false) {
            $this->markTestSkipped(
              'The OS is not linux.'
            );
        }
    }

    // * * * * * echo "test" > __DIR__ . '/cronjob.log' // evaluate dir

    /**
     * @testdox The cron handler has the expected default values after initialization
     * @covers phpOMS\Utils\TaskSchedule\Cron<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Utils\TaskSchedule\SchedulerAbstract', new Cron());
    }

    /**
     * @testdox The cron binary location path can be guessed
     * @covers phpOMS\Utils\TaskSchedule\Cron<extended>
     * @group framework
     */
    public function testGuessBinary() : void
    {
        self::assertTrue(Cron::guessBin());
    }

    /**
     * @testdox A cron job can be created and returned
     * @covers phpOMS\Utils\TaskSchedule\Cron<extended>
     * @group framework
     */
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

    /**
     * @testdox A none-existing cron name cannot be returned
     * @covers phpOMS\Utils\TaskSchedule\Cron<extended>
     * @group framework
     */
    public function testInvalidCronJobName() : void
    {
        $cron = new Cron();
        self::assertEquals([], $cron->getAllByName('testCronJob', false));
    }

    /**
     * @testdox A cron job can be updated
     * @covers phpOMS\Utils\TaskSchedule\Cron<extended>
     * @group framework
     */
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

    /**
     * @testdox A cron job can be deleted
     * @covers phpOMS\Utils\TaskSchedule\Cron<extended>
     * @group framework
     */
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
