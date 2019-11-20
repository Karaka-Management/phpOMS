<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Socket\Server
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Socket\Server;

use phpOMS\Socket\Client\ClientConnection;
use phpOMS\Socket\Client\NullClientConnection;

/**
 * Client manager class.
 *
 * @package phpOMS\Socket\Server
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ClientManager
{
    private $clients = [];

    /**
     * Add client
     *
     * @param ClientConnection $client Client
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function add(ClientConnection $client) : void
    {
        $this->clients[$client->getId()] = $client;
    }

    /**
     * Get client by id
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function get($id)
    {
        return $this->clients[$id] ?? new NullClientConnection($id, null);
    }

    /**
     * Get client by socket
     *
     * @param mixed $socket Socket
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getBySocket($socket)
    {
        foreach ($this->clients as $client) {
            if ($client->getSocket() === $socket) {
                return $client;
            }
        }

        return new NullClientConnection($id, null);
    }

    /**
     * Remove client by id
     *
     * @param mixed $id Client id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove($id) : bool
    {
        if (isset($this->clients[$id])) {
            unset($this->clients[$id]);

            return true;
        }

        return false;
    }
}
