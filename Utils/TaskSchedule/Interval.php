<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
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
namespace phpOMS\Utils\TaskSchedule;

/**
 * Array utils.
 *
 * @category   Framework
 * @package    Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Interval
{

    private $start = null;

    private $end = null;

    /**
     * Minute.
     *
     * @var array
     * @since 1.0.0
     */
    private $minute = [];

    /**
     * Hour.
     *
     * @var array
     * @since 1.0.0
     */
    private $hour = [];

    /**
     * Day of month.
     *
     * @var array
     * @since 1.0.0
     */
    private $dayOfMonth = [];

    /**
     * Month.
     *
     * @var array
     * @since 1.0.0
     */
    private $month = [];

    /**
     * Day of week.
     *
     * @var array
     * @since 1.0.0
     */
    private $dayOfWeek = [];

    /**
     * Year.
     *
     * @var array
     * @since 1.0.0
     */
    private $year = [];

    /**
     * Constructor.
     *
     * @param string $interval Interval to parse
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $interval = null)
    {
        $this->start = new \DateTime('now');

        if (isset($interval)) {
            $this->parse($interval);
        }
    }

    /**
     * Parse interval.
     *
     * @param string $interval Interval to parse
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parse(string $interval)
    {
        $elements = explode(' ', trim($interval));

        $this->minute = $this->parseMinute($elements[0]);
        $this->hour = $this->parseHour($elements[1]);
        $this->dayOfMonth = $this->parseDayOfMonth($elements[2]);
        $this->month = $this->parseMonth($elements[3]);
        $this->dayOfWeek = $this->parseDayOfWeek($elements[4]);
        $this->year = $this->parseYear($elements[5]);
    }

    /**
     * Parse element.
     *
     * @param string $minute Minute
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseMinute(string $minute) : array
    {

    }

    /**
     * Parse element.
     *
     * @param string $hour Hour
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseHour(string $hour) : array
    {

    }

    /**
     * Parse element.
     *
     * @param string $dayOfMonth Day of month
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseDayOfMonth(string $dayOfMonth) : array
    {

    }

    /**
     * Parse element.
     *
     * @param string $month Month
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseMonth(string $month) : array
    {

    }

    /**
     * Parse element.
     *
     * @param string $dayOfWeek Day of week
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseDayOfWeek(string $dayOfWeek) : array
    {

    }

    /**
     * Parse element.
     *
     * @param string $year Year
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function parseYear(string $year) : array
    {

    }

    public function setStart(\DateTime $start) {
        $this->start = $start;
    }

    public function getStart() : \DateTime 
    {
        return $this->start;
    }

    public function getEnd() 
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end) {
        $this->end = $end;
    }

    /**
     * Set mintue.
     *
     * @param array $minute Minute
     * @param int  $start  Start/first
     * @param int  $end    End/last
     * @param int  $step   Step
     * @param bool  $any    Any
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setMinute(array $minute, int $step = 0, bool $any = false)
    {
        if ($this->validateMinute($arr = [
            'minutes' => $minute,
            'step'    => $step,
            'any'     => $any,
        ])
        ) {
            $this->hour = $arr;
        } else {
            throw new \Exception('Invalid format.');
        }
    }

    /**
     * Set hour.
     *
     * @param array $hour  Hour
     * @param int  $start Start/first
     * @param int  $end   End/last
     * @param int  $step  Step
     * @param bool  $any   Any
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setHour(array $hour, int $step = 0, bool $any = false)
    {
        if ($this->validateHour($arr = [
            'hours' => $hour,
            'step'  => $step,
            'any'   => $any,
        ])
        ) {
            $this->hour = $arr;
        } else {
            throw new \Exception('Invalid format.');
        }
    }

    /**
     * Set day of month.
     *
     * @param array $dayOfMonth Day of month
     * @param int  $start      Start/first
     * @param int  $end        End/last
     * @param int  $step       Step
     * @param bool  $any        Any
     * @param bool  $last       Last
     * @param int  $nearest    Nearest day
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDayOfMonth(array $dayOfMonth, int $step = 0, bool $any = false, bool $last = false, int $nearest = 0)
    {
        if ($this->validateDayOfMonth($arr = [
            'dayOfMonth' => $dayOfMonth,
            'step'       => $step,
            'any'        => $any,
            'last'       => $last,
            'nearest'    => $nearest,
        ])
        ) {
            $this->hour = $arr;
        } else {
            throw new \Exception('Invalid format.');
        }
    }

    /**
     * Set month.
     *
     * @param array $month Month
     * @param int  $start Start/first
     * @param int  $end   End/last
     * @param int  $step  Step
     * @param bool  $any   Any
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setMonth(array $month, int $step = 0, bool $any = false)
    {
        if ($this->validateMonth($arr = [
            'month' => $month,
            'step'  => $step,
            'any'   => $any,
        ])
        ) {
            $this->hour = $arr;
        } else {
            throw new \Exception('Invalid format.');
        }
    }

    /**
     * Set day of week.
     *
     * @param array $dayOfWeek Day of week
     * @param int  $start     Start/first
     * @param int  $end       End/last
     * @param int  $step      Step
     * @param bool  $any       Any
     * @param bool  $last      Last
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDayOfWeek(array $dayOfWeek, int $step = 0, bool $any = false, bool $last = false)
    {
        if ($this->validateDayOfWeek($arr = [
            'dayOfWeek' => $dayOfWeek,
            'step'      => $step,
            'any'       => $any,
            'last'      => $last,
        ])
        ) {
            $this->hour = $arr;
        } else {
            throw new \Exception('Invalid format.');
        }
    }

    /**
     * Set yaer.
     *
     * @param array $year  Year
     * @param int  $start Start/first
     * @param int  $end   End/last
     * @param int  $step  Step
     * @param bool  $any   Any
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setYear(array $year, int $step = 0, bool $any = false)
    {
        if ($this->validateYear($arr = [
            'year'  => $year,
            'step'  => $step,
            'any'   => $any,
        ])
        ) {
            $this->hour = $arr;
        } else {
            throw new \Exception('Invalid format.');
        }
    }

    /**
     * Get minute.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getMinute() : array
    {
        return $this->minute;
    }

    /**
     * Get hour.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getHour() : array
    {
        return $this->hour;
    }

    /**
     * Get day of month.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDayOfMonth() : array
    {
        return $this->dayOfMonth;
    }

    /**
     * Get day of week.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDayOfWeek() : array
    {
        return $this->dayOfWeek;
    }

    /**
     * Get month.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getMonth() : array
    {
        return $this->month;
    }

    /**
     * Get year.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getYear() : array
    {
        return $this->year;
    }

    /**
     * Validate minute.
     *
     * @param array $array Element to validate
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validateMinute(array $array) : bool
    {
        foreach ($array['minutes'] as $minute) {
            if ($minute > 59 || $minute < 0) {
                return false;
            }
        }

        if ($array['step'] > 59 || $array['step'] < 0) {
            return false;
        }

        return true;
    }

    /**
     * Validate hour.
     *
     * @param array $array Element to validate
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validateHour(array $array) : bool
    {
        foreach ($array['hours'] as $hour) {
            if ($hour > 23 || $hour < 0) {
                return false;
            }
        }

        if ($array['step'] > 23 || $array['step'] < 0) {
            return false;
        }

        return true;
    }

    /**
     * Validate day of month.
     *
     * @param array $array Element to validate
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validateDayOfMonth(array $array) : bool
    {
        foreach ($array['dayOfMonth'] as $dayOfMonth) {
            if ($dayOfMonth > 31 || $dayOfMonth < 1) {
                return false;
            }
        }

        if ($array['step'] > 31 || $array['step'] < 1) {
            return false;
        }
        if ($array['nearest'] > 31 || $array['nearest'] < 1) {
            return false;
        }

        return true;
    }

    /**
     * Validate month.
     *
     * @param array $array Element to validate
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validateMonth(array $array) : bool
    {
        foreach ($array['month'] as $month) {
            if ($month > 12 || $month < 1) {
                return false;
            }
        }

        if ($array['step'] > 12 || $array['step'] < 1) {
            return false;
        }

        return true;
    }

    /**
     * Validate day of week.
     *
     * @param array $array Element to validate
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validateDayOfWeek(array $array) : bool
    {
        foreach ($array['dayOfWeek'] as $dayOfWeek) {
            if ($dayOfWeek > 7 || $dayOfWeek < 1) {
                return false;
            }
        }

        if ($array['step'] > 5 || $array['step'] < 1) {
            return false;
        }

        return true;
    }

    /**
     * Validate year.
     *
     * @param array $array Element to validate
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function validateYear(array $array) : bool
    {
        return true;
    }

    /**
     * Create string representation.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __toString() : string
    {
        /* Parsing minutes */
        if (($count = count($this->minute['minutes'])) > 0) {
            $minute = implode(',', $this->minute['minutes']);
        } else {
            $minute = '*';
            $count  = 1;
        }

        if ($count === 0 && $this->minute['step'] !== 0) {
            $minute .= '/' . $this->minute['step'];
        }

        /* Parsing hours */
        if (($count = count($this->hour['hours'])) > 0) {
            $hour = implode(',', $this->hour['hours']);
        } else {
            $hour  = '*';
            $count = 1;
        }

        if ($count === 0 && $this->hour['step'] !== 0) {
            $hour .= '/' . $this->hour['step'];
        }

        /* Parsing day of month */
        if (($count = count($this->dayOfMonth['dayOfMonth'])) > 0) {
            $dayOfMonth = implode(',', $this->dayOfMonth['dayOfMonth']);
        } else {
            $dayOfMonth = '*';
            $count      = 1;
        }

        if ($count === 0 && $this->dayOfMonth['step'] !== 0) {
            $dayOfMonth .= '/' . $this->dayOfMonth['step'];
        }

        if ($this->dayOfMonth['last']) {
            $dayOfMonth .= 'L';
        }

        /* Parsing month */
        if (($count = count($this->month['month'])) > 0) {
            $month = implode(',', $this->month['month']);
        } else {
            $month = '*';
            $count = 1;
        }

        if ($count === 0 && $this->month['step'] !== 0) {
            $month .= '/' . $this->month['step'];
        }

        /* Parsing day of week */
        if (($count = count($this->dayOfWeek['dayOfWeek'])) > 0) {
            $dayOfWeek = implode(',', $this->dayOfWeek['dayOfWeek']);
        } else {
            $dayOfWeek = '*';
            $count     = 1;
        }

        if ($count === 0 && $this->dayOfWeek['step'] !== 0) {
            $dayOfWeek .= '#' . $this->dayOfWeek['step'];
        }

        if ($this->dayOfWeek['last']) {
            $dayOfWeek .= 'L';
        }

        /* Parsing year */
        if (($count = count($this->year['year'])) > 0) {
            $year = implode(',', $this->year['year']);
        } else {
            $year  = '*';
            $count = 1;
        }

        if ($count === 0 && $this->year['step'] !== 0) {
            $year .= '/' . $this->year['step'];
        }

        return $minute . ' ' . $hour . ' ' . $dayOfMonth . ' ' . $month . ' ' . $dayOfWeek . ' ' . $year;
    }
}
