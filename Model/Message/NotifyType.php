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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Stdlib\Base\Enum;

/**
 * NotifyType class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class NotifyType extends Enum
{
    public const BINARY = 'binary';

    public const OK = 'ok';

    public const INFO = 'info';

    public const WARNING = 'warning';

    public const ERROR = 'error';

    public const FATAL = 'fatal';

    public const HIDDEN = 'hidden';
}
