<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Auth;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Session\SessionInterface;

/**
 * Auth class.
 *
 * Responsible for authenticating and initializing the connection
 *
 * @category   Framework
 * @package    phpOMS\Auth
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Auth
{
    /**
     * Session instance.
     *
     * @var SessionInterface
     * @since 1.0.0
     */
    private $session = null;

    /**
     * Database connection instance.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    private $connection = null;

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     * @param SessionInterface   $session    Session
     *
     * @since  1.0.0
     */
    public function __construct(ConnectionAbstract $connection, SessionInterface $session)
    {
        $this->connection = $connection;
        $this->session    = $session;
    }

    /**
     * Authenticates user.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function authenticate() : int
    {
        $uid = $this->session->get('UID');

        if (empty($uid)) {
            return 0;
        }

        return $uid;
    }

    /**
     * Logout the given user.
     *
     * @param int $uid User ID
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function logout(int $uid = null) /* : void */
    {
        // TODO: logout other users? If admin wants to kick a user for updates etc.
        $this->session->remove('UID');
    }
}
