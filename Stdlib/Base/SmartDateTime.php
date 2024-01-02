<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

use phpOMS\Math\Functions\Functions;

/**
 * SmartDateTime.
 *
 * Providing smarter datetimes
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class SmartDateTime extends \DateTime
{
    /**
     * Default format
     *
     * @var string
     * @since 1.0.0
     */
    public const FORMAT = 'Y-m-d hh:mm:ss';

    /**
     * Default timezone
     *
     * @var string
     * @since 1.0.0
     */
    public const TIMEZONE = 'UTC';

    /**
     * Constructor.
     *
     * @param string             $datetime DateTime string
     * @param null|\DateTimeZone $timezone Timezone
     *
     * @since 1.0.0
     */
    public function __construct(string $datetime = 'now', \DateTimeZone $timezone = null)
    {
        $parsed = \str_replace(
            ['Y', 'm', 'd'],
            [\date('Y'), \date('m'), \date('d')],
            $datetime
        );

        parent::__construct($parsed, $timezone);
    }

    /**
     * Create object from DateTime
     *
     * @param \DateTime $date DateTime to extend
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public static function createFromDateTime(\DateTime $date) : self
    {
        return new self($date->format('Y-m-d H:i:s'), $date->getTimezone());
    }

    /**
     * Modify datetime in a smart way.
     *
     * @param int $y        Year
     * @param int $m        Month
     * @param int $d        Day
     * @param int $calendar Calendar
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function createModify(int $y = 0, int $m = 0, int $d = 0, int $calendar = \CAL_GREGORIAN) : self
    {
        $dt = clone $this;
        $dt->smartModify($y, $m, $d, $calendar);

        return $dt;
    }

    /**
     * Modify datetime in a smart way.
     *
     * @param int $y        Year
     * @param int $m        Month
     * @param int $d        Day
     * @param int $calendar Calendar
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function smartModify(int $y = 0, int $m = 0, int $d = 0, int $calendar = \CAL_GREGORIAN) : self
    {
        $yearChange = (int) \floor(((int) $this->format('m') - 1 + $m) / 12);
        $yearNew    = (int) $this->format('Y') + $y + $yearChange;

        $monthNew = (int) $this->format('m') + $m;
        $monthNew = $monthNew <= 0
            ? 12 + ($monthNew - 1) % 12 + 1
            : ($monthNew - 1) % 12 + 1;

        $dayMonthOld = \cal_days_in_month($calendar, (int) $this->format('m'), (int) $this->format('Y'));
        $dayMonthNew = \cal_days_in_month($calendar, $monthNew, $yearNew);
        $dayOld      = (int) $this->format('d');

        $dayNew = $dayOld > $dayMonthNew || $dayOld === $dayMonthOld ? $dayMonthNew : $dayOld;

        $this->setDate($yearNew, $monthNew, $dayNew);

        if ($d !== 0) {
            $this->modify($d . ' day');
        }

        return $this;
    }

    /**
     * Get end of month object
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function getEndOfMonth() : self
    {
        return new self($this->format('Y-m') . '-' . $this->getDaysOfMonth() . ' 23:59:59');
    }

    /**
     * Get start of month object
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function getStartOfMonth() : self
    {
        return new self($this->format('Y') . '-' . $this->format('m') . '-01');
    }

    /**
     * Get start of the week
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function getStartOfWeek() : self
    {
        $w = (int) \strtotime('-' . \date('w', $this->getTimestamp()) .' days', $this->getTimestamp());

        return new self(\date('Y-m-d', $w));
    }

    /**
     * Get end of the week
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function getEndOfWeek() : self
    {
        $w = (int) \strtotime('+' . (6 - (int) \date('w', $this->getTimestamp())) .' days', $this->getTimestamp());

        return new self(\date('Y-m-d', $w));
    }

    /**
     * Get days of current month
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getDaysOfMonth() : int
    {
        return (int) $this->format('t');
    }

    /**
     * Get first day of current month
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getFirstDayOfMonth() : int
    {
        $time = \mktime(0, 0, 0, (int) $this->format('m'), 1, (int) $this->format('Y'));

        if ($time === false) {
            return -1; // @codeCoverageIgnore
        }

        return \getdate($time)['wday'];
    }

    /**
     * Get the end of the day
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function getEndOfDay() : self
    {
        return new self(\date('Y-m-d', $this->getTimestamp()) . ' 23:59:59');
    }

    /**
     * Get the start of the day
     *
     * @return SmartDateTime
     *
     * @since 1.0.0
     */
    public function getStartOfDay() : self
    {
        return new self(\date('Y-m-d', $this->getTimestamp()) . ' 00:00:00');
    }

    /**
     * Is leap year in gregorian calendar
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isLeapYear() : bool
    {
        return self::leapYear((int) $this->format('Y'));
    }

    /**
     * Test year if leap year in gregorian calendar
     *
     * @param int $year Year to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function leapYear(int $year) : bool
    {
        $isLeap = false;

        if ($year % 4 === 0) {
            $isLeap = true;
        }

        if ($year % 100 === 0) {
            $isLeap = false;
        }

        if ($year % 400 === 0) {
            $isLeap = true;
        }

        return $isLeap;
    }

    /**
     * Get day of week
     *
     * @param int $y Year
     * @param int $m Month
     * @param int $d Day
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function dayOfWeek(int $y, int $m, int $d) : int
    {
        $time = \strtotime($d . '-' . $m . '-' . $y);

        if ($time === false) {
            return -1;
        }

        return (int) \date('w', $time);
    }

    /**
     * Get day of week
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getDayOfWeek() : int
    {
        return self::dayOfWeek((int) $this->format('Y'), (int) $this->format('m'), (int) $this->format('d'));
    }

    /**
     * Create calendar array
     *
     * @param int $weekStartsWith Day of the week start (0 = Sunday)
     *
     * @return \DateTime[]
     *
     * @since 1.0.0
     */
    public function getMonthCalendar(int $weekStartsWith = 0) : array
    {
        $days = [];

        // get day of first day in month
        $firstDay = $this->getFirstDayOfMonth();

        // calculate difference to $weekStartsWith
        $diffToWeekStart = Functions::mod($firstDay - $weekStartsWith, 7);
        $diffToWeekStart = $diffToWeekStart === 0 ? 7 : $diffToWeekStart;

        // get days of previous month
        $previousMonth     = $this->createModify(0, -1);
        $daysPreviousMonth = $previousMonth->getDaysOfMonth();

        // add difference to $weekStartsWith counting backwards from days of previous month (reorder so that lowest value first)
        for ($i = $daysPreviousMonth - $diffToWeekStart; $i < $daysPreviousMonth; ++$i) {
            $days[] = new \DateTime($previousMonth->format('Y') . '-' . $previousMonth->format('m') . '-' . ($i + 1));
        }

        // add normal count of current days
        $daysMonth = $this->getDaysOfMonth();
        for ($i = 1; $i <= $daysMonth; ++$i) {
            $days[] = new \DateTime($this->format('Y') . '-' . $this->format('m') . '-' . ($i));
        }

        // add remaining days to next month (7*6 - difference+count of current month)
        $remainingDays = 42 - $diffToWeekStart - $daysMonth;
        $nextMonth     = $this->createModify(0, 1);

        for ($i = 1; $i <= $remainingDays; ++$i) {
            $days[] = new \DateTime($nextMonth->format('Y') . '-' . $nextMonth->format('m') . '-' . ($i));
        }

        return $days;
    }

    /**
     * Get the start of the year based on a custom starting month
     *
     * @param int $month Start of the year (i.e. fiscal year)
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function startOfYear(int $month = 1) : \DateTime
    {
        return new \DateTime(\date('Y') . '-' . \sprintf('%02d', $month) . '-01');
    }

    /**
     * Get the end of the year based on a custom starting month
     *
     * @param int $month Start of the year (i.e. fiscal year)
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function endOfYear(int $month = 1) : \DateTime
    {
        return new \DateTime(\date('Y') . '-' . self::calculateMonthIndex(13 - $month, $month) . '-31');
    }

    /**
     * Get the start of the month
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function startOfMonth() : \DateTime
    {
        return new \DateTime(\date('Y-m') . '-01');
    }

    /**
     * Get the end of the month
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function endOfMonth() : \DateTime
    {
        return new \DateTime(\date('Y-m-t'));
    }

    /**
     * Calculate the difference in months between two dates
     *
     * @param \DateTime $d1 First datetime
     * @param \DateTime $d2 Second datetime
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function monthDiff(\DateTime $d1, \DateTime $d2) : int
    {
        $interval = $d1->diff($d2);

        return ($interval->y * 12) + $interval->m;
    }

    /**
     * Calculates the current month index based on the start of the fiscal year.
     *
     * @param int $month Current month
     * @param int $start Start of the fiscal year (01 = January)
     *
     * @return int
     *
     * @since 1.0.0;
     */
    public static function calculateMonthIndex(int $month, int $start = 1) : int
    {
        $mod = ($month - $start);

        return \abs(($mod < 0 ? 12 + $mod : $mod) % 12) + 1;
    }

    public static function formatDuration(int $duration) : string
    {
        $days = \floor($duration / (24 * 3600));
        $hours = \floor(($duration % (24 * 3600)) / 3600);
        $minutes = \floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        $result = '';

        if ($days > 0) {
            $result .= \sprintf('%02dd', $days);
        }

        if ($hours > 0) {
            $result .= \sprintf('%02dh', $hours);
        }

        if ($minutes > 0) {
            $result .= \sprintf('%02dm', $minutes);
        }

        if ($seconds > 0) {
            $result .= \sprintf('%02ds', $seconds);
        }

        return \rtrim($result, ' ');
    }
}
