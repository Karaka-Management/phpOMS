<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Datatypes;

/**
 * SmartDateTime.
 *
 * Providing smarter datetimes
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class SmartDateTime extends \DateTime
{
    /**
     * Default format
     *
     * @var string
     * @since 1.0.0
     */
    /* public */ const FORMAT = 'Y-m-d hh:mm:ss';

    /**
     * Default timezone
     *
     * @var string
     * @since 1.0.0
     */
    /* public */ const TIMEZONE = 'UTC';

    /**
     * {@inheritdoc}
     */
    public function __construct($time = 'now', $timezone = null)
    {
        parent::__construct($time, $timezone);
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
     * @since  1.0.0
     */
    public function createModify(int $y, int $m = 0, int $d = 0, int $calendar = CAL_GREGORIAN) : SmartDateTime
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
     * @since  1.0.0
     */
    public function smartModify(int $y, int $m = 0, int $d = 0, int $calendar = CAL_GREGORIAN) : SmartDateTime
    {
        $y_change    = (int) floor(((int) $this->format('m') - 1 + $m) / 12);
        $y_change    = ((int) $this->format('m') - 1 + $m) < 0 && ((int) $this->format('m') - 1 + $m) % 12 === 0 ? $y_change - 1 : $y_change;
        $y_new       = (int) $this->format('Y') + $y + $y_change;
        $m_new       = ((int) $this->format('m') + $m) % 12;
        $m_new       = $m_new === 0 ? 12 : $m_new < 0 ? 12 + $m_new : $m_new;
        $d_month_old = cal_days_in_month($calendar, (int) $this->format('m'), (int) $this->format('Y'));
        $d_month_new = cal_days_in_month($calendar, $m_new, $y_new);
        $d_old       = (int) $this->format('d');

        if ($d_old > $d_month_new) {
            $d_new = $d_month_new;
        } elseif ($d_old < $d_month_new && $d_old === $d_month_old) {
            $d_new = $d_month_new;
        } else {
            $d_new = $d_old;
        }

        $this->setDate($y_new, $m_new, $d_new);

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
     * @since  1.0.0
     */
    public function getEndOfMonth() : SmartDateTime
    {
        return new SmartDateTime($this->format('Y') . '-' . $this->format('m') . '-' . $this->getDaysOfMonth());
    }

    /**
     * Get start of month object
     *
     * @return SmartDateTime
     *
     * @since  1.0.0
     */
    public function getStartOfMonth() : SmartDateTime
    {
        return new SmartDateTime($this->format('Y') . '-' . $this->format('m') . '-01');
    }

    /**
     * Get days of current month
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getDaysOfMonth() : int
    {
        return cal_days_in_month(CAL_GREGORIAN, (int) $this->format('m'), (int) $this->format('Y'));
    }

    /**
     * Get first day of current month
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getFirstDayOfMonth() : int
    {
        return getdate(mktime(0, 0, 0, (int) $this->format('m'), 1, (int) $this->format('Y')))['wday'];
    }

    /**
     * Is leap year in gregorian calendar
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function isLeapYear() : bool
    {
        return self::leapYear((int) $this->format('Y'));
    }

    /**
     * Test year if leap year in gregorian calendar
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function leapYear(int $year) : bool
    {
        $isLeap = false;

        if ($year % 4 == 0) {
            $isLeap = true;
        }

        if ($year % 100 == 0) {
            $isLeap = false;
        }

        if ($year % 400 == 0) {
            $isLeap = true;
        }

        return $isLeap;
    }

    /**
     * Get day of week
     *
     * @return int
     *
     * @since  1.0.0
     */
    public static function getDayOfWeek(int $y, int $m, int $d) : int
    {
        $w  = 1;
        $y  = ($y - 1) % 400 + 1;
        $ly = ($y - 1) / 4;
        $ly = $ly - ($y - 1) / 100;
        $ly = $ly + ($y - 1) / 400;
        $ry = $y - 1 - $ly;
        $w  = $w + $ry;
        $w  = $w + 2 * $ly;
        $w  = $w + date("z", mktime(0, 0, 0, $m, $d, $y)) + 1;;
        $w = ($w - 1) % 7 + 1;

        return $w;
    }

}
