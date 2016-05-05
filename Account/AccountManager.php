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
namespace phpOMS\Account;

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
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
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
    public function get(int $id) : Account
    {
        return $this->accounts[$id] ?? new NullAccount();
    }

    /**
     * Set account.
     *
     * @param Account $account Account
     *
     * @return int Account id
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(Account $account)
    {
        if (!isset($this->accounts[$account->getId()])) {
            $this->accounts[$account->getId()] = $account;

            return $account->getId();
        }

        return null;
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
