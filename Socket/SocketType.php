<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Socket
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Socket;

use phpOMS\Stdlib\Base\Enum;

/**
 * Socket type enum.
 *
 * @package    phpOMS\Socket
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class SocketType extends Enum
{
    public const TCP_SERVER = 'server';
    public const TCP_CLIENT = 'client';
    public const WEB_SOCKET = 'ws';
}
