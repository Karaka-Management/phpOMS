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

use phpOMS\Account\NullAccount;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Message\Socket\PacketManager;
use phpOMS\Socket\Client\ClientConnection;
use phpOMS\Socket\SocketAbstract;

/**
 * Server class.
 *
 * @package phpOMS\Socket\Server
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Server extends SocketAbstract
{
    /**
     * Socket connection limit.
     *
     * @var int
     * @since 1.0.0
     */
    private $limit = 10;

    /**
     * Client connections.
     *
     * @var array
     * @since 1.0.0
     */
    private $conn = [];

    /**
     * Packet manager.
     *
     * @var PacketManager
     * @since 1.0.0
     */
    private $packetManager = null;

    private $clientManager = null;

    private $verbose = true;

    /**
     * Socket application.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    private $app = null;

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app Socket application
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
     * Has internet connection.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function hasInternet() : bool
    {
        $connected = @\fsockopen("www.google.com", 80);

        if ($connected) {
            \fclose($connected);

            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $ip, int $port) : void
    {
        $this->app->logger->info('Creating socket...');
        parent::create($ip, $port);
        $this->app->logger->info('Binding socket...');
        \socket_bind($this->sock, $this->ip, $this->port);
    }

    /**
     * Set connection limit.
     *
     * @param int $limit Connection limit
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLimit(int $limit) : void
    {
        $this->limit = $limit;
    }

    /**
     * Perform client-server handshake during login
     *
     * @param mixed $client  Client
     * @param mixed $headers Header
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function handshake($client, $headers) : bool
    {
        // todo: different handshake for normal tcp connection
        if ($client !== null) {
            return true;
        }

        if (\preg_match("/Sec-WebSocket-Version: (.*)\r\n/", $headers, $match) === false) {
            return false;
        }

        $version = (int) ($match[1] ?? -1);

        if ($version !== 13) {
            return false;
        }

        if (\preg_match("/GET (.*) HTTP/", $headers, $match)) {
            $root = $match[1];
        }

        if (\preg_match("/Host: (.*)\r\n/", $headers, $match)) {
            $host = $match[1];
        }

        if (\preg_match("/Origin: (.*)\r\n/", $headers, $match)) {
            $origin = $match[1];
        }

        $key = '';
        if (\preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match)) {
            $key = $match[1];
        }

        $acceptKey = $key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
        $acceptKey = \base64_encode(\sha1($acceptKey, true));
        $upgrade   = "HTTP/1.1 101 Switching Protocols\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "Sec-WebSocket-Accept: ${acceptKey}" .
            "\r\n\r\n";
        \socket_write($client->getSocket(), $upgrade);
        $client->setHandshake(true);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function run() : void
    {
        $this->app->logger->info('Start listening...');
        @\socket_listen($this->sock);
        @\socket_set_nonblock($this->sock);
        $this->conn[] = $this->sock;

        $this->app->logger->info('Is running...');
        while ($this->run) {
            $read = $this->conn;

            $write  = null;
            $except = null;

            if (\socket_select($read, $write, $except, 0) < 1) {
                // error
                // socket_last_error();
                // socket_strerror(socket_last_error());
                // socket_clear_error();
                $a = 2;
            }

            foreach ($read as $key => $socket) {
                if ($this->sock === $socket) {
                    $newc = @\socket_accept($this->sock);
                    $this->connectClient($newc);
                } else {
                    $client = $this->clientManager->getBySocket($socket);
                    $data   = @\socket_read($socket, 1024, \PHP_NORMAL_READ);

                    if ($data === false) {
                        \socket_close($socket);
                    }

                    $data = \is_string($data) ? \trim($data) : '';

                    if (!$client->getHandshake()) {
                        $this->app->logger->debug('Doing handshake...');
                        if ($this->handshake($client, $data)) {
                            $client->setHandshake(true);
                            $this->app->logger->debug('Handshake succeeded.');
                        } else {
                            $this->app->logger->debug('Handshake failed.');
                            $this->disconnectClient($client);
                        }
                    } else {
                        $this->packetManager->handle($data, $client);
                    }
                }
            }
        }
        $this->app->logger->info('Is shutdown...');

        $this->close();
    }

    /**
     * Perform server shutdown
     *
     * @param mixed $request Request
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function shutdown($request) : void
    {
        $msg = 'shutdown' . "\n";
        \socket_write($this->clientManager->get($request->getHeader()->getAccount())->getSocket(), $msg, \strlen($msg));

        $this->run = false;
    }

    /**
     * Connect a client
     *
     * @param mixed $socket Socket
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function connectClient($socket) : void
    {
        $this->app->logger->debug('Connecting client...');
        $this->app->accountManager->add(new NullAccount(1));
        $this->clientManager->add($client = new ClientConnection(new NullAccount(1), $socket));
        $this->conn[$client->getId()] = $socket;
        $this->app->logger->debug('Connected client.');
    }

    /**
     * Disconnect a client
     *
     * @param mixed $client Client
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function disconnectClient($client) : void
    {
        $this->app->logger->debug('Disconnecting client...');
        $client->setConnected(false);
        $client->setHandshake(false);
        \socket_shutdown($client->getSocket(), 2);
        \socket_close($client->getSocket());

        if (isset($this->conn[$client->getId()])) {
            unset($this->conn[$client->getId()]);
        }

        $this->clientManager->remove($client->getId());
        $this->app->logger->debug('Disconnected client.');
    }

    /**
     * Unmask payload
     *
     * @param mixed $payload Payload
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function unmask($payload) : string
    {
        $length = \ord($payload[1]) & 127;
        if ($length == 126) {
            $masks = \substr($payload, 4, 4);
            $data  = \substr($payload, 8);
        } elseif ($length == 127) {
            $masks = \substr($payload, 10, 4);
            $data  = \substr($payload, 14);
        } else {
            $masks = \substr($payload, 2, 4);
            $data  = \substr($payload, 6);
        }
        $text       = '';
        $dataLength = \strlen($data);
        for ($i = 0; $i < $dataLength; ++$i) {
            $text .= $data[$i] ^ $masks[$i % 4];
        }

        return $text;
    }
}
