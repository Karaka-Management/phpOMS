<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\TaskAbstract;

/**
 * @internal
 */
class TaskAbstractTest extends \PHPUnit\Framework\TestCase
{
    private $class = null;

    protected function setUp() : void
    {
        $this->class = new class('') extends TaskAbstract {
            public function __toString() : string
            {
                return '';
            }

            public static function createWith(array $jobData) : TaskAbstract
            {

            }
        };
    }

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

    public function testGetSet() : void
    {
        $this->class->setCommand('Command');
        self::assertEquals('Command', $this->class->getCommand());

        $this->class->setStatus('Status');
        self::assertEquals('Status', $this->class->getStatus());

        $this->class->setComment('Comment');
        self::assertEquals('Comment', $this->class->getComment());

        $date = new \DateTime('now');
        $this->class->setLastRuntime($date);
        self::assertEquals($date->format('Y-m-d'), $this->class->getLastRuntime()->format('Y-m-d'));

        $this->class->setNextRuntime($date);
        self::assertEquals($date->format('Y-m-d'), $this->class->getNextRuntime()->format('Y-m-d'));
    }
}
