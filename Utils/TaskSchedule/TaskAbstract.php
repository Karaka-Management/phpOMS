<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

/**
 * Abstract task class.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class TaskAbstract
{
    /**
     * Id.
     *
     * @var string
     * @since 1.0.0
     */
    public string $id = '';

    /**
     * Command used for creating the task
     *
     * @var string
     * @since 1.0.0
     */
    public string $command = '';

    /**
     * Run interval
     *
     * @var string
     * @since 1.0.0
     */
    public string $interval = '';

    /**
     * Status of the task
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = TaskStatus::ACTIVE;

    /**
     * Next runtime
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected \DateTime $nextRunTime;

    /**
     * Last runtime
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected \DateTime $lastRunTime;

    /**
     * Comment
     *
     * @var string
     * @since 1.0.0
     */
    public string $comment = '';

    /**
     * Constructor
     *
     * @param string $name Id/name of the task (on linux the same as the executable script)
     * @param string $cmd  Command to create the task
     *
     * @since 1.0.0
     */
    public function __construct(string $name, string $cmd = '', string $interval = '')
    {
        $this->id          = $name;
        $this->command     = $cmd;
        $this->interval    = $interval;
        $this->lastRunTime = new \DateTime('1900-01-01');
        $this->nextRunTime = new \DateTime('1900-01-01');
    }

    /**
     * Get id.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Stringify task for direct handling
     *
     * @return string
     *
     * @since 1.0.0
     */
    abstract public function __toString() : string;

    /**
     * Get command to create the task
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCommand() : string
    {
        return $this->command;
    }

    /**
     * Set command to create the task
     *
     * @param string $command Command
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCommand(string $command) : void
    {
        $this->command = $command;
    }

    /**
     * Get interval to create the task
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getInterval() : string
    {
        return $this->interval;
    }

    /**
     * Set interval to create the task
     *
     * @param string $interval Interval
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setInterval(string $interval) : void
    {
        $this->interval = $interval;
    }

    /**
     * Get next run time.
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getNextRunTime() : \DateTime
    {
        return $this->nextRunTime;
    }

    /**
     * Set next run time.
     *
     * @param \DateTime $nextRunTime Next run time
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setNextRunTime(\DateTime $nextRunTime) : void
    {
        $this->nextRunTime = $nextRunTime;
    }

    /**
     * Get last run time.
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getLastRuntime() : \DateTime
    {
        return $this->lastRunTime;
    }

    /**
     * Set last run time.
     *
     * @param \DateTime $lastRunTime Last run time
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLastRuntime(\DateTime $lastRunTime) : void
    {
        $this->lastRunTime = $lastRunTime;
    }

    /**
     * Get comment.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getComment() : string
    {
        return $this->comment;
    }

    /**
     * Set comment.
     *
     * @param string $comment Comment
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setComment(string $comment) : void
    {
        $this->comment = $comment;
    }

    /**
     * Create task based on job data
     *
     * @param array $jobData Raw job data
     *
     * @return TaskAbstract
     *
     * @since 1.0.0
     */
    abstract public static function createWith(array $jobData) : self;
}
