<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Socket\Client;

use phpOMS\Socket\CommandManager;
use phpOMS\Socket\SocketAbstract;

/**
 * Client socket class.
 *
 * @category   Framework
 * @package    phpOMS\Socket\Client
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ClientConnection
{
    private $id = 0;
    private $socket = null;
    private $handshake = false;
    private $pid = null;
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

    public function getHandshake()
    {
        return $this->handshake;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function setSocket($socket)
    {
        $this->socket = $socket;
    }

    public function setHandshake($handshake)
    {
        $this->handshake = $handshake;
    }

    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    public function setConnected(bool $connected)
    {
        $this->connected = $connected;
    }
}
