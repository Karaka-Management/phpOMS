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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\SchedulerAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\SchedulerAbstractTest: Scheduler abstraction')]
final class SchedulerAbstractTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The scheduler has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertTrue(SchedulerAbstract::getBin() === '' || \is_file(SchedulerAbstract::getBin()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The scheduler binary location path can be guessed')]
    public function testGuessBinary() : void
    {
        self::assertTrue(SchedulerAbstract::guessBin());
    }
}
