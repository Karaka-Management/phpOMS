<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Stdlib\Base\Enum;

/**
 * NotifyType class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class NotifyType extends Enum
{
    public const BINARY  = 0;

    public const INFO    = 1;

    public const WARNING = 2;

    public const ERROR   = 3;

    public const FATAL   = 4;
}
