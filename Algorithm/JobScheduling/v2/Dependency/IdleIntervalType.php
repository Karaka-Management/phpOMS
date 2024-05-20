<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Scheduling\Dependency
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling\Dependency;

/**
 * Machine type.
 *
 * @package phpOMS\Scheduling\Dependency
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class IdleIntervalType
{
    public const ACTIVE_TIME = 1; // every x hours of activity

    public const JOB_TIME = 2; // every x jobs

    public const FIXED_TIME = 3; // datetime

    public const GENERAL_TIME = 4; // every x hours
}
