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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\TaskSchedule;

use phpOMS\Utils\TaskSchedule\Interval;

/**
 * @testdox phpOMS\tests\Utils\TaskSchedule\IntervalTest: Cron interval
 *
 * @internal
 */
final class IntervalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The interval has the expected default values after initialization
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testDefault() : void
    {
        $dt = new \DateTime('now');

        $interval = new Interval($dt);
        self::assertEquals($dt->format('Y-m-d'), $interval->getStart()->format('Y-m-d'));
        self::assertNull($interval->getEnd());
        self::assertEquals(0, $interval->getMaxDuration());
        self::assertEquals([], $interval->getMinute());
        self::assertEquals([], $interval->getHour());
        self::assertEquals([], $interval->getDayOfMonth());
        self::assertEquals([], $interval->getMonth());
        self::assertEquals([], $interval->getDayOfWeek());
        self::assertEquals([], $interval->getYear());
        self::assertEquals(\json_encode([
                'start'       => $dt->format('Y-m-d H:i:s'),
                'end'         => null,
                'maxDuration' => 0,
                'minute'      => [],
                'hour'        => [],
                'dayOfMonth'  => [],
                'dayOfWeek'   => [],
                'year'        => [],
            ]), $interval->serialize()
        );
    }

    /**
     * @testdox The start date can be set during initialization and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testConstructorInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));
        self::assertEquals('2001-11-25', $interval->getStart()->format('Y-m-d'));
    }

    /**
     * @testdox The start date can set and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testStartInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->setStart(new \DateTime('2015-08-14'));
        self::assertEquals('2015-08-14', $interval->getStart()->format('Y-m-d'));
    }

    /**
     * @testdox The end date can set and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testEndInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->setEnd(new \DateTime('2018-10-30'));
        self::assertEquals('2018-10-30', $interval->getEnd()->format('Y-m-d'));
    }

    /**
     * @testdox The maximum execution duration can set and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testMaxExecutionInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->setMaxDuration(30);
        self::assertEquals(30, $interval->getMaxDuration());
    }

    /**
     * @testdox An execution minute can be added and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testMinuteInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addMinute(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getMinute());
    }

    /**
     * @testdox An execution minute can be overwritten
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testMinuteOverwriteInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addMinute(1, 3, 2);
        $interval->setMinute(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getMinute());
    }

    /**
     * @testdox An execution hour can be added and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testHourInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addHour(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getHour());
    }

    /**
     * @testdox An execution hour can be overwritten
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testHourOverwriteInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addHour(1, 3, 2);
        $interval->setHour(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getHour());
    }

    /**
     * @testdox An execution year can be added and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testYearInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addYear(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getYear());
    }

    /**
     * @testdox An execution year can be overwritten
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testYearOverwriteInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addYear(1, 3, 2);
        $interval->setYear(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getYear());
    }

    /**
     * @testdox An execution day of month can be added and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testDayOfMonthInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addDayOfMonth(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getDayOfMonth());
    }

    /**
     * @testdox An execution day of month can be overwritten
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testDayOfMonthOverwriteInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addDayOfMonth(1, 3, 2);
        $interval->setDayOfMonth(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getDayOfMonth());
    }

    /**
     * @testdox An execution day of week can be added and returned
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testDayOfWeekInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addDayOfWeek(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getDayOfWeek());
    }

    /**
     * @testdox An execution day of week can be overwritten
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testDayOfWeekOverwriteInputOutput() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->addDayOfWeek(1, 3, 2);
        $interval->setDayOfWeek(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getDayOfWeek());
    }

    /**
     * @testdox A interval can be serialized
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testSerialize() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        $interval->setStart(new \DateTime('2015-08-14'));
        $interval->setEnd(new \DateTime('2018-10-30'));
        $interval->setMaxDuration(30);
        $interval->addMinute(1, 3, 2);
        $interval->addHour(1, 3, 2);
        $interval->addYear(1, 3, 2);
        $interval->addDayOfMonth(1, 3, 2);
        $interval->addDayOfWeek(1, 3, 2);

        self::assertEquals(\json_encode([
            'start'       => '2015-08-14 00:00:00',
            'end'         => '2018-10-30 00:00:00',
            'maxDuration' => 30,
            'minute'      => [['start' => 1, 'end' => 3, 'step' => 2]],
            'hour'        => [['start' => 1, 'end' => 3, 'step' => 2]],
            'dayOfMonth'  => [['start' => 1, 'end' => 3, 'step' => 2]],
            'dayOfWeek'   => [['start' => 1, 'end' => 3, 'step' => 2]],
            'year'        => [['start' => 1, 'end' => 3, 'step' => 2]],
        ]), $interval->serialize());
    }

    /**
     * @testdox A serialized interval can be unserialized
     * @covers phpOMS\Utils\TaskSchedule\Interval
     * @group framework
     */
    public function testUnserialize() : void
    {
        $interval = new Interval();
        $interval->setStart(new \DateTime('2015-08-14'));
        $interval->setEnd(new \DateTime('2018-10-30'));
        $interval->setMaxDuration(30);
        $interval->addMinute(1, 3, 2);
        $interval->addHour(1, 3, 2);
        $interval->addYear(1, 3, 2);
        $interval->addDayOfMonth(1, 3, 2);
        $interval->addDayOfWeek(1, 3, 2);

        $interval2 = new Interval(null, $interval->serialize());

        self::assertEquals('2015-08-14', $interval2->getStart()->format('Y-m-d'));
        self::assertEquals('2018-10-30', $interval2->getEnd()->format('Y-m-d'));
        self::assertEquals(30, $interval2->getMaxDuration());

        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval2->getMinute());

        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval2->getHour());

        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval2->getYear());

        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval2->getDayOfMonth());

        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval2->getDayOfWeek());
    }
}
