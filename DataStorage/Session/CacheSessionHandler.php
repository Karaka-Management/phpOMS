<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Session
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\Connection\ConnectionAbstract;

/**
 * Cache session handler.
 *
 * @package phpOMS\DataStorage\Session
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class CacheSessionHandler implements \SessionHandlerInterface, \SessionIdInterface
{
    /**
     * Cache connection
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    private ConnectionAbstract $con;

    /**
     * Expiration time
     *
     * @var int
     * @since 1.0.0
     */
    private int $expire = 3600;

    /**
     * Constructor
     *
     * @param ConnectionAbstract $con ConnectionAbstract
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $con, int $expire = 3600)
    {
        $this->con    = $con;
        $this->expire = $expire;
    }

    /**
     * Create a session id string
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function create_sid() : string
    {
        return ($sid = \session_create_id('s-')) === false ? '' : $sid;
    }

    /**
     * Open the session storage
     *
     * @param string $savePath    Path of the session data
     * @param string $sessionName Name of the session
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function open(string $savePath, string $sessionName) : bool
    {
        if ($this->con->getStatus() !== CacheStatus::OK) {
            $this->con->connect();
        }

        return $this->con->getStatus() === CacheStatus::OK;
    }

    /**
     * Closing the cache connection doesn't happen in here and must be implemented in the application
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function close() : bool
    {
        return true;
    }

    /**
     * Read the session data (also prolongs the expire)
     *
     * @param string $id Session id
     *
     * @return false|string
     *
     * @since 1.0.0
     */
    public function read(string $id) : string|false
    {
        $data = $this->con->get($id);

        if ($data === null) {
            return false;
        }

        $this->con->updateExpire($this->expire);

        return (string) $data;
    }

    /**
     * Write session data
     *
     * @param string $id   Session id
     * @param string $data Session data
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function write(string $id, string $data) : bool
    {
        $this->con->set($id, $data, -1);

        return true;
    }

    /**
     * Destroy the session
     *
     * @param string $id Session id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function destroy(string $id) : bool
    {
        $this->con->delete($id);

        return true;
    }

    /**
     * Garbage collect session data
     *
     * @param int $maxlifetime Maximum session data life time
     *
     * @return int|false
     *
     * @since 1.0.0
     */
    public function gc(int $maxlifetime) : int|false
    {
        return (int) $this->con->flush($maxlifetime);
    }
}
