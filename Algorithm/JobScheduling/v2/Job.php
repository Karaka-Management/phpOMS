<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Scheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling;

/**
 * Job.
 *
 * @package phpOMS\Scheduling
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Job
{
    /**
     * Id
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Time of the execution
     *
     * @var int
     * @since 1.0.0
     */
    public int $executionTime = 0;

    /**
     * Priority.
     *
     * @var float
     * @since 1.0.0
     */
    public float $priority = 0.0;

    /**
     * Value this job generates.
     *
     * @var float
     * @since 1.0.0
     */
    public float $value = 0.0;

    /**
     * Cost of executing this job.
     *
     * @var float
     * @since 1.0.0
     */
    public float $cost = 0.0;

    /**
     * How many iterations has this job been on hold in the queue.
     *
     * @var int
     * @since 1.0.0
     */
    public int $onhold = 0;

    /**
     * How many iterations has this job been in process in the queue.
     *
     * @var int
     * @since 1.0.0
     */
    public int $inprocessing = 0;

    /**
     * What is the deadline for this job?
     *
     * @param \DateTime
     * @since 1.0.0
     */
    public \DateTime $deadline;

    /**
     * Which steps must be taken during the job execution
     *
     * @var JobStep[]
     * @since 1.0.0
     */
    public array $steps = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->deadline = new \DateTime('now');
    }

    /**
     * Get the profit of the job
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getProfit() : float
    {
        return $this->value - $this->cost;
    }
}
