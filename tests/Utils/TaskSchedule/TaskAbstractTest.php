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

use phpOMS\Utils\TaskSchedule\TaskAbstract;
use phpOMS\Utils\TaskSchedule\TaskFactory;
use phpOMS\Utils\TaskSchedule\TaskStatus;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\TaskSchedule\TaskAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\TaskSchedule\TaskAbstractTest: Job/task abstraction')]
final class TaskAbstractTest extends \PHPUnit\Framework\TestCase
{
    private TaskAbstract $class;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->class = new class('') extends TaskAbstract {
            public function __toString() : string
            {
                return '';
            }

            public static function createWith(array $jobData) : TaskAbstract
            {
                return TaskFactory::create();
            }
        };
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The task abstraction has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals('', $this->class->getId());
        self::assertEquals('', $this->class->getCommand());
        self::assertEquals(TaskStatus::ACTIVE, $this->class->status);
        self::assertInstanceOf('\DateTime', $this->class->getNextRunTime());
        self::assertInstanceOf('\DateTime', $this->class->getLastRuntime());
        self::assertEquals('', $this->class->getComment());
        self::assertEquals('', $this->class->getInterval());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The command can be set and returned')]
    public function testCommandInputOutput() : void
    {
        $this->class->setCommand('Command');
        self::assertEquals('Command', $this->class->getCommand());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The interval can be set and returned')]
    public function testIntervalInputOutput() : void
    {
        $this->class->setInterval('Interval');
        self::assertEquals('Interval', $this->class->getInterval());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The comment can be set and returned')]
    public function testCommentInputOutput() : void
    {
        $this->class->setComment('Comment');
        self::assertEquals('Comment', $this->class->getComment());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The last runtime can be set and returned')]
    public function testLastRuntimeInputOutput() : void
    {
        $date = new \DateTime('now');
        $this->class->setLastRuntime($date);
        self::assertEquals($date->format('Y-m-d'), $this->class->getLastRuntime()->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The next runtime can be set and returned')]
    public function testNextRuntimeInputOutput() : void
    {
        $date = new \DateTime('now');
        $this->class->setNextRuntime($date);
        self::assertEquals($date->format('Y-m-d'), $this->class->getNextRuntime()->format('Y-m-d'));
    }
}
