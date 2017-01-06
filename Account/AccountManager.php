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
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Account;

use phpOMS\Auth\Auth;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Session\SessionInterface;

/**
 * Account manager class.
 *
 * @category   Framework
 * @package    phpOMS\Account
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class AccountManager implements \Countable
{

    /**
     * Accounts.
     *
     * @var Account[]
     * @since 1.0.0
     */
    private $accounts = [];

    /**
     * Session.
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
     * Authenticator.
     *
     * @var Auth
     * @since 1.0.0
     */
    private $auth = null;

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
        $this->auth       = new Auth($this->connection, $this->session);
    }

    /**
     * Get account.
     *
     * @param int $id Account id
     *
     * @return Account
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get(int $id = 0) : Account
    {
        if ($id === 0) {
            $account = new Account($this->auth->authenticate());

            if (!isset($this->accounts[$account->getId()])) {
                $this->accounts[$account->getId()] = $account;
            }

            return $account;
        }

        return $this->accounts[$id] ?? new NullAccount();
    }

    /**
     * Login user.
     *
     * @param int    $account  Account id
     * @param string $login    Username
     * @param string $password Password
     *
     * @return int
     *
     * @throws \Exception Throws this exception if the account to login is not found in the AccountManager.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function login(int $account, string $login, string $password) : int
    {
        if (!isset($this->accounts[$account])) {
            throw new \Exception('Account not found in the account manager.');
        }

        return $this->auth->login($login, $password);
    }

    /**
     * Add account.
     *
     * @param Account $account Account
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add(Account $account) : bool
    {
        if (!isset($this->accounts[$account->getId()])) {
            $this->accounts[$account->getId()] = $account;

            return true;
        }

        return false;
    }

    /**
     * Remove account.
     *
     * @param int $id Account id
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(int $id) : bool
    {
        if (isset($this->accounts[$id])) {
            unset($this->accounts[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get accounts count.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function count() : int
    {
        return count($this->accounts);
    }

}
