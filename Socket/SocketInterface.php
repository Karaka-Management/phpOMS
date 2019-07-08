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

/**
 * Socket class.
 *
 * @package    phpOMS\Socket
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
interface SocketInterface
{

    /**
     * Create the socket.
     *
     * @param string $ip   IP address
     * @param int    $port Port
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function create(string $ip, int $port) : void;

    /**
     * Close socket.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function close() : void;

    /**
     * Run socket.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function run() : void;
}
