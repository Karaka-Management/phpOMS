<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Socket\Client
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Socket\Client;

/**
 * Client socket class.
 *
 * @package phpOMS\Socket\Client
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ClientConnection
{
    private $id        = 0;
    private $socket    = null;
    private $handshake = false;
    private $pid       = null;
    private $connected = true;

    public function __construct($id, $socket)
    {
        $this->id     = $id;
        $this->socket = $socket;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSocket()
    {
        return $this->socket;
    }

    public function setSocket($socket) : void
    {
        $this->socket = $socket;
    }

    public function getHandshake()
    {
        return $this->handshake;
    }

    public function setHandshake($handshake) : void
    {
        $this->handshake = $handshake;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function setPid($pid) : void
    {
        $this->pid = $pid;
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function setConnected(bool $connected) : void
    {
        $this->connected = $connected;
    }
}
