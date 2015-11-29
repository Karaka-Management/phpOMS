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
namespace phpOMS\Socket\Server;

use phpOMS\Socket\CommandManager;
use phpOMS\Socket\Packets\PacketManager;

use phpOMS\Socket\SocketAbstract;

/**
 * Server class.
 *
 * @category   Framework
 * @package    phpOMS\Socket\Server
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Server extends SocketAbstract
{

    /**
     * Socket connection limit.
     *
     * @var \int
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

    /**
     * Socket application.
     *
     * @var \Socket\SocketApplication
     * @since 1.0.0
     */
    private $app = null;

    public static function hasInternet() : \bool
    {
        $connected = @fsockopen("www.google.com", 80);

        if ($connected) {
            fclose($connected);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Constructor.
     *
     * @param \Socket\SocketApplication $app socketApplication
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct($app)
    {
        $this->app           = $app;
        $this->packetManager = new PacketManager(new CommandManager(), new ClientManager());
    }

    /**
     * {@inheritdoc}
     */
    public function create(\string $ip, \int $port)
    {
        parent::create($ip, $port);
        socket_bind($this->sock, $this->ip, $this->port);
    }

    /**
     * Set connection limit.
     *
     * @param \int $limit Connection limit
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLimit(\int $limit)
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        socket_listen($this->sock);
        socket_set_nonblock($this->sock);
        $this->conn[] = $this->sock;

        while ($this->run) {
            $read = $this->conn;

            $write  = null;
            $except = null;

            if (socket_select($read, $write, $except, 0) < 1) {
                // error
                // socket_last_error();
                // socket_strerror(socket_last_error());
                // socket_clear_error();
            }

            if (in_array($this->sock, $read)) {
                $this->conn[] = $newc = socket_accept($this->sock);

                // TODO: init account
                // This is only the initial connection no auth happens here
                // Here the server should request an authentication

                $welcome = "Welcome to the server.\n";
                socket_write($newc, $welcome, strlen($welcome));
                socket_getpeername($newc, $ip);

                unset($read[0]);
            }

            foreach ($read as $key => $client) {
                $data = socket_read($client, 1024);

                if ($this->clientReadError($key)) {
                    continue;
                }

                $this->packetManager->handle(trim($data), $key);
            }
        }

        $this->close();
    }

    /**
     * Handle client read error.
     *
     * @param mixed $key Client key
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function clientReadError($key) : \bool
    {
        if (socket_last_error() === 10054) {
            socket_clear_error();
            unset($this->conn[$key]);
            $this->conn = array_values($this->conn);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        parent::close();
    }

    /**
     * {@inheritdoc}
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Disconnect client.
     *
     * @param mixed $key Client key
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function disconnect($key)
    {
        if (isset($this->conn[$key])) {
            unset($this->conn[$key]);
        }
    }
}
