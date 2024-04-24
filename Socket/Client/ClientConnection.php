<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Socket\Client
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Socket\Client;

use phpOMS\Account\Account;

/**
 * Client socket class.
 *
 * @package phpOMS\Socket\Client
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ClientConnection
{
    private $id = 0;

    private $socket = null;

    private $handshake = false;

    private $pid = null;

    private $connected = true;

    public Account $account;

    /**
     * Constructor.
     *
     * @param Account $account Account
     * @param mixed   $socket  Socket connection
     *
     * @since 1.0.0
     */
    public function __construct(Account $account, $socket)
    {
        $this->id      = $account->id;
        $this->account = $account;
        $this->socket  = $socket;
    }

    /**
     * Get client id.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get account
     *
     * @return Account
     *
     * @since 1.0.0
     */
    public function getAccount() : Account
    {
        return $this->account;
    }

    /**
     * Get socket
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * Set socket
     *
     * @param mixed $socket Socket connection
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSocket($socket) : void
    {
        $this->socket = $socket;
    }

    /**
     * Get handshake data
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getHandshake()
    {
        return $this->handshake;
    }

    /**
     * Set handshake data
     *
     * @param mixed $handshake Handshake
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setHandshake($handshake) : void
    {
        $this->handshake = $handshake;
    }

    /**
     * Is connected?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isConnected() : bool
    {
        return $this->connected;
    }

    /**
     * Set connected
     *
     * @param bool $connected Is connected?
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setConnected(bool $connected) : void
    {
        $this->connected = $connected;
    }
}
