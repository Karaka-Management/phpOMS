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
namespace phpOMS\Socket;

/**
 * Socket class.
 *
 * @category   Socket
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
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
     * @var resource
     * @since 1.0.0
     */
    protected $sock = null;

    /**
     * {@inheritdoc}
     */
    public function create(string $ip, int $port)
    {
        $this->ip   = $ip;
        $this->port = $port;

        // todo: if local network connect use AF_UNIX
        $this->sock = \socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    /**
     * Destructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if ($this->sock !== null) {
            socket_shutdown($this->sock, 2);
            socket_close($this->sock);
            $this->sock = null;
        }
    }
}
