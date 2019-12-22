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

use phpOMS\Socket\SocketAbstract;
use phpOMS\ApplicationAbstract;
use phpOMS\Socket\Server\ClientManager;
use phpOMS\Message\Socket\PacketManager;

/**
 * Client socket class.
 *
 * @package phpOMS\Socket\Client
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Client extends SocketAbstract
{
    /**
     * Packet manager.
     *
     * @var   PacketManager
     * @since 1.0.0
     */
    private $packetManager = null;

    /**
     * Socket application.
     *
     * @var   SocketApplication
     * @since 1.0.0
     */
    private $app = null;

    private $clientManager = null;

    private array $packets = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct(ApplicationAbstract $app)
    {
        $this->app           = $app;
        $this->clientManager = new ClientManager();
        $this->packetManager = new PacketManager($this->app->router, $this->app->dispatcher);
    }

    /**
     * Disconnect from server.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function disconnect() : void
    {
        $this->run = false;
    }

    /**
     * {@inheritdoc}
     */
    public function run() : void
    {
        \socket_connect($this->sock, $this->ip, $this->port);

        $errorCounter = 0;

        while ($this->run) {
            try {
                if (!empty($this->packets)) {
                    $msg = \array_shift($this->packets);

                    \socket_write($this->sock, $msg, \strlen($msg));
                }

                $read = [$this->sock];

                if (\socket_last_error() !== 0) {
                    ++$errorCounter;
                }

                // todo: create reset condition for errorCounter. Probably if a successful read happened

                //if (socket_select($read, $write = null, $except = null, 0) < 1) {
                // error
                // socket_last_error();
                // socket_strerror(socket_last_error());
                //}

                if (\count($read) > 0) {
                    $data = \socket_read($this->sock, 1024);

                    var_dump($data);

                    /* Server no data */
                    if ($data === false) {
                        continue;
                    }

                    /* Normalize */
                    $data = \trim($data);

                    if (!empty($data)) {
                        $data = \explode(' ', $data);
                        $this->commands->trigger($data[0], 0, $data);
                    }
                }

                if ($errorCounter > 10) {
                    $this->run = false;
                }
            } catch (\Throwable $e) {
                $this->run = false;
            }
        }

        $this->close();
    }

    /**
     * Stop the socket connection to the server
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function shutdown() : void
    {
        $this->run = false;
    }

    /**
     * Add packet to be handeld
     *
     * @param mixed $packet Packet to handle
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addPacket($packet) : void
    {
        $this->packets[] = $packet;
    }
}
