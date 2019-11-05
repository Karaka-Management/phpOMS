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

use phpOMS\Utils\TaskSchedule\Interval;

/**
 * @internal
 */
class IntervalTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $dt = new \DateTime('now');

        $interval = new Interval($dt);
        self::assertEquals($dt->format('Y-m-d'), $interval->getStart()->format('Y-m-d'));
        self::assertEquals(null, $interval->getEnd());
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

    public function testSetGet() : void
    {
        $interval = new Interval(new \DateTime('2001-11-25'));

        self::assertEquals('2001-11-25', $interval->getStart()->format('Y-m-d'));

        $interval->setStart(new \DateTime('2015-08-14'));
        self::assertEquals('2015-08-14', $interval->getStart()->format('Y-m-d'));

        $interval->setEnd(new \DateTime('2018-10-30'));
        self::assertEquals('2018-10-30', $interval->getEnd()->format('Y-m-d'));

        $interval->setMaxDuration(30);
        self::assertEquals(30, $interval->getMaxDuration());

        $interval->addMinute(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getMinute());
        $interval->setMinute(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getMinute());

        $interval->addHour(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getHour());
        $interval->setHour(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getHour());

        $interval->addYear(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getYear());
        $interval->setYear(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getYear());

        $interval->addDayOfMonth(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getDayOfMonth());
        $interval->setDayOfMonth(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getDayOfMonth());

        $interval->addDayOfWeek(1, 3, 2);
        self::assertEquals([[
            'start' => 1,
            'end'   => 3,
            'step'  => 2,
        ]], $interval->getDayOfWeek());
        $interval->setDayOfWeek(0, 4, 5, 6);
        self::assertEquals([[
            'start' => 4,
            'end'   => 5,
            'step'  => 6,
        ]], $interval->getDayOfWeek());
    }

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
