<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\Contract\SerializableInterface;

/**
 * Interval class for tasks.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Interval implements SerializableInterface
{
    /**
     * Start of the task.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    private \DateTime $start;

    /**
     * End of the task.
     *
     * @var null|\DateTime
     * @since 1.0.0
     */
    private ?\DateTime $end = null;

    /**
     * Max runtime duration
     *
     * After this duration in seconds the task/job is stopped.
     *
     * 0 = infinite
     *
     * @var int
     * @since 1.0.0
     */
    private int $maxDuration = 0;

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
     * @param null|\DateTime $start    Start of the job/task
     * @param null|string    $interval Interval to unserialize (internal serialization not a cronjob string etc.)
     *
     * @since 1.0.0
     */
    public function __construct(\DateTime $start = null, string $interval = null)
    {
        $this->start = $start ?? new \DateTime('now');

        if ($interval !== null) {
            $this->unserialize($interval);
        }
    }

    /**
     * Get start.
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getStart() : \DateTime
    {
        return $this->start;
    }

    /**
     * Set start.
     *
     * @param \DateTime $start Start date
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setStart(\DateTime $start) : void
    {
        $this->start = $start;
    }

    /**
     * Get end.
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getEnd() : ?\DateTime
    {
        return $this->end;
    }

    /**
     * Set end.
     *
     * @param \DateTime $end End date
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setEnd(\DateTime $end) : void
    {
        $this->end = $end;
    }

    /**
     * Get max runtime duration
     *
     * After this duration a task/job is cancelled
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getMaxDuration() : int
    {
        return $this->maxDuration;
    }

    /**
     * Set max runtime duration
     *
     * After this duration a task/job is cancelled
     *
     * @param int $duration Max duration in seconds
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMaxDuration(int $duration) : void
    {
        $this->maxDuration = $duration;
    }

    /**
     * Get minute.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getMinute() : array
    {
        return $this->minute;
    }

    /**
     * Set minute.
     *
     * @param int $index Index of the value to change
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMinute(int $index, int $start = null, int $end = null, int $step = null) : void
    {
        $this->minute[$index] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Add minute.
     *
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addMinute(int $start = null, int $end = null, int $step = null) : void
    {
        $this->minute[] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Get hour.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getHour() : array
    {
        return $this->hour;
    }

    /**
     * Set hour.
     *
     * @param int $index Index of the value to change
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setHour(int $index, int $start = null, int $end = null, int $step = null) : void
    {
        $this->hour[$index] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Add hour.
     *
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addHour(int $start = null, int $end = null, int $step = null) : void
    {
        $this->hour[] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Get day of month.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDayOfMonth() : array
    {
        return $this->dayOfMonth;
    }

    /**
     * Set day of the month.
     *
     * @param int $index Index of the value to change
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDayOfMonth(int $index, int $start = null, int $end = null, int $step = null) : void
    {
        $this->dayOfMonth[$index] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Add day of the month.
     *
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addDayOfMonth(int $start = null, int $end = null, int $step = null) : void
    {
        $this->dayOfMonth[] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Get day of week.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDayOfWeek() : array
    {
        return $this->dayOfWeek;
    }

    /**
     * Set day of the week.
     *
     * @param int $index Index of the value to change
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDayOfWeek(int $index, int $start = null, int $end = null, int $step = null) : void
    {
        $this->dayOfWeek[$index] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Add day of the week.
     *
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addDayOfWeek(int $start = null, int $end = null, int $step = null) : void
    {
        $this->dayOfWeek[] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Get month.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getMonth() : array
    {
        return $this->month;
    }

    /**
     * Set month.
     *
     * @param int $index Index of the value to change
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMonth(int $index, int $start = null, int $end = null, int $step = null) : void
    {
        $this->month[$index] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Add month.
     *
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addMonth(int $start = null, int $end = null, int $step = null) : void
    {
        $this->month[] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Get year.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getYear() : array
    {
        return $this->year;
    }

    /**
     * Set year.
     *
     * @param int $index Index of the value to change
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setYear(int $index, int $start = null, int $end = null, int $step = null) : void
    {
        $this->year[$index] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Add year.
     *
     * @param int $start Start
     * @param int $end   End
     * @param int $step  Step
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addYear(int $start = null, int $end = null, int $step = null) : void
    {
        $this->year[] = [
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ];
    }

    /**
     * Create string representation.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function serialize() : string
    {
        $serialized = \json_encode([
            'start'       => $this->start->format('Y-m-d H:i:s'),
            'end'         => $this->end === null ? null : $this->end->format('Y-m-d H:i:s'),
            'maxDuration' => $this->maxDuration,
            'minute'      => $this->minute,
            'hour'        => $this->hour,
            'dayOfMonth'  => $this->dayOfMonth,
            'dayOfWeek'   => $this->dayOfWeek,
            'year'        => $this->year,
        ]);

        return $serialized === false ? '{}' : $serialized;
    }

    /**
     * Unserialize.
     *
     * @param string $serialized String to unserialize
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function unserialize(mixed $serialized) : void
    {
        /** @var array $data */
        $data = \json_decode($serialized, true);

        $this->start       = new \DateTime($data['start']);
        $this->end         = $data['end'] === null ? null : new \DateTime($data['end']);
        $this->maxDuration = $data['maxDuration'];
        $this->minute      = $data['minute'];
        $this->hour        = $data['hour'];
        $this->dayOfMonth  = $data['dayOfMonth'];
        $this->dayOfWeek   = $data['dayOfWeek'];
        $this->year        = $data['year'];
    }
}
