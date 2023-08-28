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

use phpOMS\Stdlib\Base\Enum;

/**
 * Priority type enum.
 *
 * Defines the different priorities in which elements from the queue can be extracted.
 *
 * @package phpOMS\Scheduling
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PriorityMode extends Enum
{
    public const FIFO = 1; // First in first out

    public const LIFO = 2; // Last in first out

    public const PRIORITY = 4;

    public const VALUE = 8;

    public const COST = 16;

    public const PROFIT = 32;

    public const HOLD = 64; // Longest on hold

    public const EARLIEST_DEADLINE = 128; // EDF
}
