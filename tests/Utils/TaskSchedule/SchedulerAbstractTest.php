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

use phpOMS\Utils\TaskSchedule\SchedulerAbstract;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\SchedulerAbstractTest: Scheduler abstraction
 *
 * @internal
 */
final class SchedulerAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The scheduler has the expected default values after initialization
     * @covers \phpOMS\Utils\TaskSchedule\SchedulerAbstract
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertTrue(SchedulerAbstract::getBin() === '' || \is_file(SchedulerAbstract::getBin()));
    }

    /**
     * @testdox The scheduler binary location path can be guessed
     * @covers \phpOMS\Utils\TaskSchedule\SchedulerAbstract
     * @group framework
     */
    public function testGuessBinary() : void
    {
        self::assertTrue(SchedulerAbstract::guessBin());
    }
}
