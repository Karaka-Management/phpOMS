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

    private $minute     = [];
    private $hour       = [];
    private $dayOfMonth = [];
    private $month      = [];
    private $dayOfWeek  = [];
    private $year       = [];

    public function __construct(\string $interval)
    {
        $this->parse($interval);
    }

    public function parse(\string $interval)
    {
        $elements = explode(' ', $interval);
    }

    public function parseMinute(\string $minute) : array
    {

    }

    public function parseHour(\string $hour) : array
    {

    }

    public function parseDayOfMonth(\string $dayOfMonth) : array
    {

    }

    public function parseMonth(\string $month) : array
    {

    }

    public function parseDayOfWeek(\string $dayOfWeek) : array
    {

    }

    public function parseYear(\string $year) : array
    {

    }

    public function setMinute(array $min, \int $start = 0, \int $end = 0, \int $step = 0, \bool $any = false)
    {
        $this->minute = [
            'minutes' => $min,
            'start'   => $start,
            'end'     => $end,
            'step'    => $step,
            'any'     => $any,
        ];

        $this->validateMinute();
    }

    public function setHour(array $hour, \int $start = 0, \int $end = 0, \int $step = 0, \bool $any = false)
    {
        $this->hour = [
            'hours' => $hour,
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
            'any'   => $any,
        ];

        $this->validateHour();
    }

    public function setDayOfMonth(array $dayOfMonth, \int $start = 0, \int $end = 0, \int $step = 0, \bool $any = false, \bool $last = false, \int $nearest = 0)
    {
        $this->dayOfMonth = [
            'dayOfMonth' => $dayOfMonth,
            'start'      => $start,
            'end'        => $end,
            'step'       => $step,
            'any'        => $any,
            'last'       => $last,
            'nearest'    => $nearest,
        ];

        $this->validateDayOfMonth();
    }

    public function setMonth(array $month, \int $start = 0, \int $end = 0, \int $step = 0, \bool $any = false)
    {
        $this->month = [
            'month' => $month,
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
            'any'   => $any,
        ];

        $this->validateMonth();
    }

    public function setDayOfWeek(array $dayOfWeek, \int $start = 0, \int $end = 0, \int $step = 0, \bool $any = false, \bool $last = false)
    {
        $this->dayOfWeek = [
            'dayOfWeek' => $dayOfWeek,
            'start'     => $start,
            'end'       => $end,
            'step'      => $step,
            'any'       => $any,
            'last'      => $last,
        ];

        $this->validateDayOfWeek();
    }

    public function setYear(array $year, \int $start = 0, \int $end = 0, \int $step = 0, \bool $any = false)
    {
        $this->year = [
            'year'  => $year,
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
            'any'   => $any,
        ];

        $this->validateYear();
    }

    public function getMinute() : array
    {
        return $this->minute;
    }

    public function getHour() : array
    {
        return $this->hour;
    }

    public function getDayOfMonth() : array
    {
        return $this->dayOfMonth;
    }

    public function getDayOfWeek() : array
    {
        return $this->dayOfWeek;
    }

    public function getMonth() : array
    {
        return $this->month;
    }

    public function getYear() : array
    {
        return $this->year;
    }

    public function validate() : \bool
    {
        if (!$this->validateMinute()) return false;
        if (!$this->validateHour()) return false;
        if (!$this->validateDayOfMonth()) return false;
        if (!$this->validateMonth()) return false;
        if (!$this->validateDayOfWeek()) return false;
        if (!$this->validateYear()) return false;

        return true;
    }

    private function validateMinute() : \bool
    {
        foreach ($this->minute['minutes'] as $minute) {
            if ($minute > 59 || $minute < 0) return false;
        }

        if ($this->minute['start'] > 59 || $this->minute['start'] < 0) return false;
        if ($this->minute['end'] > 59 || $this->minute['end'] < 0) return false;
        if ($this->minute['step'] > 59 || $this->minute['step'] < 0) return false;

        return true;
    }

    private function validateHour() : \bool
    {
        foreach ($this->hour['hours'] as $hour) {
            if ($hour > 23 || $hour < 0) return false;
        }

        if ($this->hour['start'] > 23 || $this->hour['start'] < 0) return false;
        if ($this->hour['end'] > 23 || $this->hour['end'] < 0) return false;
        if ($this->hour['step'] > 23 || $this->hour['step'] < 0) return false;

        return true;
    }

    private function validateDayOfMonth() : \bool
    {
        foreach ($this->dayOfMonth['dayOfMonth'] as $dayOfMonth) {
            if ($dayOfMonth > 31 || $dayOfMonth < 1) return false;
        }

        if ($this->dayOfMonth['start'] > 31 || $this->dayOfMonth['start'] < 1) return false;
        if ($this->dayOfMonth['end'] > 31 || $this->dayOfMonth['end'] < 1) return false;
        if ($this->dayOfMonth['step'] > 31 || $this->dayOfMonth['step'] < 1) return false;
        if ($this->dayOfMonth['nearest'] > 31 || $this->dayOfMonth['nearest'] < 1) return false;

        return true;
    }

    private function validateMonth() : \bool
    {
        foreach ($this->month['month'] as $month) {
            if ($month > 12 || $month < 1) return false;
        }

        if ($this->month['start'] > 12 || $this->month['start'] < 1) return false;
        if ($this->month['end'] > 12 || $this->month['end'] < 1) return false;
        if ($this->month['step'] > 12 || $this->month['step'] < 1) return false;

        return true;
    }

    private function validateDayOfWeek() : \bool
    {
        foreach ($this->dayOfWeek['dayOfWeek'] as $dayOfWeek) {
            if ($dayOfWeek > 7 || $dayOfWeek < 1) return false;
        }

        if ($this->dayOfWeek['start'] > 7 || $this->dayOfWeek['start'] < 1) return false;
        if ($this->dayOfWeek['end'] > 7 || $this->dayOfWeek['end'] < 1) return false;
        if ($this->dayOfWeek['step'] > 5 || $this->dayOfWeek['step'] < 1) return false;

        return true;
    }

    private function validateYear() : \bool
    {
        return true;
    }

    public function __toString()
    {
        /* Parsing minutes */
        if (($count = count($this->minute['minutes'])) > 0) {
            $minute = implode(',', $this->minute['minutes']);
        } elseif ($this->minute['start'] !== 0 && $this->minute['end']) {
            $minute = $this->minute['start'] . '-' . $this->minute['end'];
            $count  = 2;
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
        } elseif ($this->hour['start'] !== 0 && $this->hour['end']) {
            $hour  = $this->hour['start'] . '-' . $this->hour['end'];
            $count = 2;
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
        } elseif ($this->dayOfMonth['start'] !== 0 && $this->dayOfMonth['end']) {
            $dayOfMonth = $this->dayOfMonth['start'] . '-' . $this->dayOfMonth['end'];
            $count      = 2;
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
        } elseif ($this->month['start'] !== 0 && $this->month['end']) {
            $month = $this->month['start'] . '-' . $this->month['end'];
            $count = 2;
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
        } elseif ($this->dayOfWeek['start'] !== 0 && $this->dayOfWeek['end']) {
            $dayOfWeek = $this->dayOfWeek['start'] . '-' . $this->dayOfWeek['end'];
            $count     = 2;
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
        } elseif ($this->year['start'] !== 0 && $this->year['end']) {
            $year  = $this->year['start'] . '-' . $this->year['end'];
            $count = 2;
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
