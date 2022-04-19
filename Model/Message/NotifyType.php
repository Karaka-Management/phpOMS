<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Stdlib\Base\Enum;

/**
 * NotifyType class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class NotifyType extends Enum
{
    public const BINARY = 0;

    public const INFO = 1;

    public const WARNING = 2;

    public const ERROR = 3;

    public const FATAL = 4;
}
