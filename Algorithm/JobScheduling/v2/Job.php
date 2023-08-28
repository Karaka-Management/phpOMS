<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Scheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling;

/**
 * Job.
 *
 * @package phpOMS\Scheduling
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Job
{
    public int $id = 0;

    public int $executionTime = 0;

    public float $priority = 0.0;

    public float $value = 0.0;

    public float $cost = 0.0;

    /** How many iterations has this job been on hold in the queue */
    public int $onhold = 0;

    /** How many iterations has this job been in process in the queue */
    public int $inprocessing = 0;

    public \DateTime $deadline;

    public array $steps = [];

    public function __construct()
    {
        $this->deadline = new \DateTime('now');
    }

    public function getProfit()
    {
        return $this->value - $this->cost;
    }
}
