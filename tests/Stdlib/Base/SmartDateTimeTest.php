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
 * @internal
 */
class SmartDateTimeTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $datetime = new SmartDateTime();
        self::assertInstanceOf('\DateTime', $datetime);
    }

    public function testGetSet() : void
    {
        $datetime = new SmartDateTime('1970-01-01');
        self::assertEquals('1970-01-01', $datetime->format('Y-m-d'));

        $new = $datetime->createModify(1, 1, 1);
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
        self::assertEquals(30, $datetime->getDaysOfMonth());
        self::assertEquals(0, $datetime->getFirstDayOfMonth());

        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);
        self::assertEquals($expected->format('Y-m-d H:i:s'), $obj->format('Y-m-d H:i:s'));
        self::assertEquals(\date("Y-m-t", \strtotime($expected->format('Y-m-d'))), $obj->getEndOfMonth()->format('Y-m-d'));
        self::assertEquals(\date("Y-m-01", \strtotime($expected->format('Y-m-d'))), $obj->getStartOfMonth()->format('Y-m-d'));

        self::assertFalse((new SmartDateTime('2103-07-20'))->isLeapYear());
        self::assertTrue((new SmartDateTime('2104-07-20'))->isLeapYear());
        self::assertFalse(SmartDateTime::leapYear(2103));
        self::assertTrue(SmartDateTime::leapYear(2104));

        self::assertEquals(\date('w', $expected->getTimestamp()), SmartDateTime::getDayOfWeek((int) $expected->format('Y'), (int) $expected->format('m'), (int) $expected->format('d')));
        self::assertEquals(\date('w', $expected->getTimestamp()), $obj->getFirstDayOfWeek());

        self::assertCount(42, $obj->getMonthCalendar());
        self::assertCount(42, $obj->getMonthCalendar(1));
    }
}
