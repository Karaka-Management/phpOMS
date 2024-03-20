<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Socket;

/**
 * Socket class.
 *
 * @package phpOMS\Socket
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
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
     * @since   1.0.0
     */
    public function create(string $ip, int $port) : void;

    /**
     * Close socket.
     *
     * @return void
     *
     * @since   1.0.0
     */
    public function close() : void;

    /**
     * Run socket.
     *
     * @return void
     *
     * @since   1.0.0
     */
    public function run() : void;
}
