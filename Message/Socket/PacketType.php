<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Socket;

use phpOMS\Stdlib\Base\Enum;

/**
 * Packet type enum.
 *
 * @package phpOMS\Message\Socket
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PacketType extends Enum
{
    public const CONNECT = 0; /* Client connection (server/sender) */

    public const DISCONNECT = 1; /* Client disconnection (server/sender) */

    public const KICK = 2; /* Kick (server/client/sender) */

    public const PING = 3; /* Ping (server/sender) */

    public const HELP = 4; /* Help (server/sender) */

    public const RESTART = 5; /* Restart server (server/all clients/client) */

    public const MSG = 6; /* Message (server/sender/client/all clients?) */

    public const LOGIN = 7; /* Login (server/sender) */

    public const LOGOUT = 8; /* Logout (server/sender) */

    public const CMD = 9; /* Other command */

    public const DOWNLOAD = 10; /* Download */
}
