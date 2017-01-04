<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Datatypes;

/**
 * SmartDateTime.
 *
 * Providing smarter datetimes
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function smartModify(int $y, int $m = 0, int $d = 0, int $calendar = CAL_GREGORIAN) : SmartDateTime
    {
        $y_change    = floor(((int) $this->format('m') - 1 + $m) / 12);
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

    public function getEndOfMonth() : SmartDateTime
    {
        return new SmartDateTime($this->format('Y') . '-' . $this->format('m') . '-' . $this->getDaysOfMonth());
    }

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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDaysOfMonth() : int
    {
        return cal_days_in_month(CAL_GREGORIAN, $this->format('m'), $this->format('Y'));
    }

    /**
     * Get first day of current month
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getFirstDayOfMonth() : int
    {
        return getdate(mktime(null, null, null, $this->format('m'), 1, $this->format('Y')))['wday'];
    }

}
