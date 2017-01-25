<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
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
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ConnectionAbstract $connection, SessionInterface $session)
    {
        $this->connection = $connection;
        $this->session    = $session;
    }

    /**
     * Authenticates user.
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function authenticate()
    {
        $uid = $this->session->get('UID');

        if ($uid === null) {
            $uid = false;
        }

        return $uid;
    }

    /**
     * Login user.
     *
     * @param string $login    Username
     * @param string $password Password
     *
     * @return int Login code
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function login(string $login, string $password) : int
    {
        try {
            $result = null;

            switch ($this->connection->getType()) {
                case DatabaseType::MYSQL:

                    $sth = $this->connection->con->prepare(
                        'SELECT
                            `' . $this->connection->prefix . 'account`.*
                        FROM
                            `' . $this->connection->prefix . 'account`
                        WHERE
                            `account_login` = :login'
                    );
                    $sth->bindValue(':login', $login, \PDO::PARAM_STR);
                    $sth->execute();

                    $result = $sth->fetchAll();
                    break;
            }

            // TODO: check if user is allowed to login on THIS page (backend|frontend|etc...)

            if (!isset($result[0])) {
                return LoginReturnType::WRONG_USERNAME;
            }

            $result = $result[0];

            if ($result['account_tries'] <= 0) {
                return LoginReturnType::WRONG_INPUT_EXCEEDED;
            }

            if (password_verify($password, $result['account_password'])) {
                $this->session->set('UID', $result['account_id']);
                $this->session->save();

                return LoginReturnType::OK;
            }

            return LoginReturnType::WRONG_PASSWORD;
        } catch (\Exception $e) {
            return LoginReturnType::FAILURE;
        }
    }

    /**
     * Logout the given user.
     *
     * @param int $uid User ID
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function logout(int $uid = null) /* : void */
    {
        // TODO: logout other users? If admin wants to kick a user for updates etc.
        $this->session->remove('UID');
    }
}
