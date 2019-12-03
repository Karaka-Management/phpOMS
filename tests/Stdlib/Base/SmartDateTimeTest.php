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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\SmartDateTime;

/**
 * @testdox phpOMS\tests\Stdlib\Base\SmartDateTimeTest: DateTime type with additional functionality
 *
 * @internal
 */
class SmartDateTimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The smart datetime extends the datetime
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testAttributes() : void
    {
        $datetime = new SmartDateTime();
        self::assertInstanceOf('\DateTime', $datetime);
    }

    /**
     * @testdox The smart datetime can be formatted like the datetime
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testFormat() : void
    {
        $datetime = new SmartDateTime('1970-01-01');
        self::assertEquals('1970-01-01', $datetime->format('Y-m-d'));
    }

    /**
     * @testdox The smart datetime can be modified an creates a new smart datetime
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testCreateModify() : void
    {
        $datetime = new SmartDateTime('1970-01-01');
        $new      = $datetime->createModify(1, 1, 1);

        self::assertEquals('1970-01-01', $datetime->format('Y-m-d'));
        self::assertEquals('1971-02-02', $new->format('Y-m-d'));

        $datetime = new SmartDateTime('1975-06-01');
        self::assertEquals('1976-07-01', $datetime->createModify(0, 13)->format('Y-m-d'));
        self::assertEquals('1976-01-01', $datetime->createModify(0, 7)->format('Y-m-d'));
        self::assertEquals('1975-03-01', $datetime->createModify(0, -3)->format('Y-m-d'));
        self::assertEquals('1974-11-01', $datetime->createModify(0, -7)->format('Y-m-d'));
        self::assertEquals('1973-11-01', $datetime->createModify(0, -19)->format('Y-m-d'));
        self::assertEquals('1973-12-01', $datetime->createModify(0, -19, 30)->format('Y-m-d'));
        self::assertEquals('1973-12-31', $datetime->createModify(0, -18, 30)->format('Y-m-d'));
    }

    /**
     * @testdox The days of the month can be returned
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testDaysOfMonth() : void
    {
        $datetime = new SmartDateTime('1975-06-01');
        self::assertEquals(30, $datetime->getDaysOfMonth());
    }

    /**
     * @testdox The week day index of the first day of the month can be returned
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testFirstDayOfMonth() : void
    {
        $datetime = new SmartDateTime('1975-06-01');
        self::assertEquals(0, $datetime->getFirstDayOfMonth());
    }

    /**
     * @testdox A smart datetime can be created from a datetime
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testCreateFromDateTime() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);
        self::assertEquals($expected->format('Y-m-d H:i:s'), $obj->format('Y-m-d H:i:s'));
    }

    /**
     * @testdox A smart datetime can be returned of the last day of the month
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testEndOfMonth() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertEquals(\date("Y-m-t", \strtotime($expected->format('Y-m-d'))), $obj->getEndOfMonth()->format('Y-m-d'));
    }

    /**
     * @testdox A smart datetime can be returned of the fist day of the month
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testStartOfMonth() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertEquals(\date("Y-m-01", \strtotime($expected->format('Y-m-d'))), $obj->getStartOfMonth()->format('Y-m-d'));
    }

    /**
     * @testdox A date or year can be checked if it is a leap year
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testLeapYear() : void
    {
        self::assertFalse((new SmartDateTime('2103-07-20'))->isLeapYear());
        self::assertTrue((new SmartDateTime('2104-07-20'))->isLeapYear());
        self::assertFalse(SmartDateTime::leapYear(2103));
        self::assertTrue(SmartDateTime::leapYear(2104));
    }

    /**
     * @testdox The day of the week index can be retruned from a date
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testDayOfWeek() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertEquals(\date('w', $expected->getTimestamp()), SmartDateTime::dayOfWeek((int) $expected->format('Y'), (int) $expected->format('m'), (int) $expected->format('d')));
        self::assertEquals(\date('w', $expected->getTimestamp()), $obj->getDayOfWeek());
    }

    /**
     * @testdox A calendar sheet is retunred containing all days of the month and some days of the previous and next month
     * @covers phpOMS\Stdlib\Base\SmartDateTime
     * @group framework
     */
    public function testCalendarSheet() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertCount(42, $obj->getMonthCalendar());
        self::assertCount(42, $obj->getMonthCalendar(1));
    }
}
