<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   TBD
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Socket\Server;

use phpOMS\Socket\Client\ClientConnection;
use phpOMS\Socket\Client\NullClientConnection;

class ClientManager
{
    private $clients = [];

    public function add(ClientConnection $client) : void
    {
        $this->clients[$client->getId()] = $client;
    }

    public function get($id)
    {
        return $this->clients[$id] ?? new NullClientConnection($id, null);
    }

    public function getBySocket($socket)
    {
        foreach ($this->clients as $client) {
            if ($client->getSocket() === $socket) {
                return $client;
            }
        }

        return new NullClientConnection($id, null);
    }

    public function remove($id)
    {
        if (isset($this->clients[$id])) {
            unset($this->clients[$id]);

            return true;
        }

        return false;
    }
}
