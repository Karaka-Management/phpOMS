<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\JobScheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\JobScheduling;

/**
 * Job for scheduling
 *
 * @package phpOMS\Algorithm\JobScheduling
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Job implements JobInterface
{
    /**
     * Value of the job
     *
     * @var float
     * @since 1.0.0
     */
    private float $value = 0.0;

    /**
     * Start time of the job
     *
     * @var \DateTime
     * @since 1.0.0
     */
    private \DateTime $start;

    /**
     * End time of the job
     *
     * @var \DateTime
     * @since 1.0.0
     */
    private ?\DateTime $end = null;

    /**
     * Name of the job
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Cosntructor.
     *
     * @param float          $value Value of the job
     * @param \DateTime      $start Start time of the job
     * @param null|\DateTime $end   End time of the job
     *
     * @since 1.0.0
     */
    public function __construct(float $value, \DateTime $start, ?\DateTime $end, string $name = '')
    {
        $this->value = $value;
        $this->start = $start;
        $this->end   = $end;
        $this->name  = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue() : float
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getStart() : \DateTime
    {
        return $this->start;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd() : ?\DateTime
    {
        return $this->end;
    }
}
