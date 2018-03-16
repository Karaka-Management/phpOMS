<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Socket;

/**
 * Socket class.
 *
 * @package    Socket
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
