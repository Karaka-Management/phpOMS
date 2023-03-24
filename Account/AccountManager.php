<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Auth\Auth;
use phpOMS\DataStorage\Session\SessionInterface;

/**
 * Account manager class.
 *
 * The account manager is used to manage accounts.
 *
 * @package phpOMS\Account
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class AccountManager implements \Countable
{
    /**
     * Accounts.
     *
     * @var Account[]
     * @since 1.0.0
     */
    private array $accounts = [];

    /**
     * Session.
     *
     * @var SessionInterface
     * @since 1.0.0
     */
    private SessionInterface $session;

    /**
     * Constructor.
     *
     * @param SessionInterface $session Session
     *
     * @since 1.0.0
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Get account.
     *
     * @param int $id Account id
     *
     * @return Account
     *
     * @since 1.0.0
     */
    public function get(int $id = 0) : Account
    {
        if ($id === 0) {
            $account = new Account(Auth::authenticate($this->session));

            if (!isset($this->accounts[$account->getId()])) {
                $this->accounts[$account->getId()] = $account;
            }

            return $account;
        }

        return $this->accounts[$id] ?? new NullAccount();
    }

    /**
     * Add account.
     *
     * @param Account $account Account
     *
     * @return bool Returns true if the account could be added otherwise false is returned
     *
     * @since 1.0.0
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
     * @return bool Returns true if the account could be removed otherwise false
     *
     * @since 1.0.0
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
     * @return int Returns the amount of accounts in the manager (>= 0)
     *
     * @since 1.0.0
     */
    public function count() : int
    {
        return \count($this->accounts);
    }
}
