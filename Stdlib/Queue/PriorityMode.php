<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Stdlib\Queue
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Queue;

use phpOMS\Stdlib\Base\Enum;

/**
 * Account type enum.
 *
 * @package    phpOMS\Stdlib\Queue
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class PriorityMode extends Enum
{
    public const FIFO    = 1;
    public const LIFO    = 2;
    public const HIGHEST = 4;
    public const LOWEST  = 8;
}
