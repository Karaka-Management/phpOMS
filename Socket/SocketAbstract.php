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
abstract class SocketAbstract implements SocketInterface
{
    /**
     * Socket ip.
     *
     * @var string
     * @since 1.0.0
     */
    protected $ip = null;

    /**
     * Socket port.
     *
     * @var int
     * @since 1.0.0
     */
    protected $port = null;

    /**
     * Socket running?
     *
     * @var bool
     * @since 1.0.0
     */
    protected $run = true;

    /**
     * Socket.
     *
     * @var null|\Socket
     * @since 1.0.0
     */
    protected $sock;

    /**
     * {@inheritdoc}
     */
    public function create(string $ip, int $port) : void
    {
        $this->ip   = $ip;
        $this->port = $port;
        $this->sock = \socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);
    }

    /**
     * Destructor.
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close() : void
    {
        if ($this->sock !== null) {
            \socket_shutdown($this->sock, 2);
            \socket_close($this->sock);
            $this->sock = null;
        }
    }
}
