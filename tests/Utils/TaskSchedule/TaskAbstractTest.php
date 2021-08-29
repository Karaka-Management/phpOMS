<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\TaskAbstract;
use phpOMS\Utils\TaskSchedule\TaskFactory;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\TaskAbstractTest: Job/task abstraction
 *
 * @internal
 */
class TaskAbstractTest extends \PHPUnit\Framework\TestCase
{
    private $class = null;

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

    /**
     * @testdox The task abstraction has the expected default values after initialization
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('', $this->class->getId());
        self::assertEquals('', $this->class->getCommand());
        self::assertEquals('', $this->class->getStatus());
        self::assertInstanceOf('\DateTime', $this->class->getNextRunTime());
        self::assertInstanceOf('\DateTime', $this->class->getLastRuntime());
        self::assertEquals('', $this->class->getComment());
        self::assertEquals('', $this->class->getInterval());
    }

    /**
     * @testdox The command can be set and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testCommandInputOutput() : void
    {
        $this->class->setCommand('Command');
        self::assertEquals('Command', $this->class->getCommand());
    }

    /**
     * @testdox The interval can be set and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testIntervalInputOutput() : void
    {
        $this->class->setInterval('Interval');
        self::assertEquals('Interval', $this->class->getInterval());
    }

    /**
     * @testdox The status can be set and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testStatusInputOutput() : void
    {
        $this->class->setStatus('Status');
        self::assertEquals('Status', $this->class->getStatus());
    }

    /**
     * @testdox The comment can be set and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testCommentInputOutput() : void
    {
        $this->class->setComment('Comment');
        self::assertEquals('Comment', $this->class->getComment());
    }

    /**
     * @testdox The last runtime can be set and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testLastRuntimeInputOutput() : void
    {
        $date = new \DateTime('now');
        $this->class->setLastRuntime($date);
        self::assertEquals($date->format('Y-m-d'), $this->class->getLastRuntime()->format('Y-m-d'));
    }

    /**
     * @testdox The next runtime can be set and returned
     * @covers phpOMS\Utils\TaskSchedule\TaskAbstract
     * @group framework
     */
    public function testNextRuntimeInputOutput() : void
    {
        $date = new \DateTime('now');
        $this->class->setNextRuntime($date);
        self::assertEquals($date->format('Y-m-d'), $this->class->getNextRuntime()->format('Y-m-d'));
    }
}
