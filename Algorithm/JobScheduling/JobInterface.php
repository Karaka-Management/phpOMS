<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Algorithm\JobScheduling
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */

declare(strict_types=1);

namespace phpOMS\Algorithm\JobScheduling;

/**
 * Job interface.
 *
 * @package phpOMS\Algorithm\JobScheduling;
 * @license OMS License 1.0
 * @link    https://karaka.app
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
