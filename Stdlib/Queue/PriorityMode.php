<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Stdlib\Queue
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Queue;

use phpOMS\Stdlib\Base\Enum;

/**
 * Priority type enum.
 *
 * Defines the different priorities in which elements from the queue can be extracted.
 *
 * @package    phpOMS\Stdlib\Queue
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class PriorityMode extends Enum
{
    public const FIFO    = 1; // First in first out
    public const LIFO    = 2; // Last in first out
    public const HIGHEST = 4; // Highest priority first (same as FIFO if all inserted elements got inserted at the same time with the same priority)
    public const LOWEST  = 8; // Lowest priority first (same as LIFO if all inserted lements got inserted at the same time with the same priority)
}
