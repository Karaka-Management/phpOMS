<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\TaskSchedule
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

use phpOMS\Stdlib\Base\Enum;

/**
 * Job status enum.
 *
 * @package phpOMS\Utils\TaskSchedule
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class TaskStatus extends Enum
{
    public const RUNNING = 1;

    public const WAITING = 2;

    public const FINISHED = 3;

    public const FAILED = 4;

    public const ACTIVE = 5;

    public const INACTIVE = 6;
}
