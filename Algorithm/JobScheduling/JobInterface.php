<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\JobScheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\JobScheduling;

/**
 * Job interface.
 *
 * @package phpOMS\Algorithm\JobScheduling;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface JobInterface
{
    /**
     * Get value of the job
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getValue() : float;

    /**
     * Get start time of the job
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getStart() : \DateTime;

    /**
     * Get end time of the job
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getEnd() : ?\DateTime;
}
