<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Stdlib\Base\Enum;

/**
 * Notification level enum.
 *
 * @package phpOMS\Message
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class NotificationLevel extends Enum
{
    public const OK = 'ok';

    public const INFO = 'info';

    public const WARNING = 'warning';

    public const ERROR = 'error';

    public const HIDDEN = 'hidden';
}
