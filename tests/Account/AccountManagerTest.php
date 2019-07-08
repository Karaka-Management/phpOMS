<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Account;

use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Account\AccountManager: Account/user manager to handle/access loaded accounts
 *
 * @internal
 */
class AccountManagerTest extends \PHPUnit\Framework\TestCase
{
    protected $manager = null;
    protected $account = null;

    protected function setUp() : void
    {
        $this->manager = new AccountManager($GLOBALS['httpSession']);

        $this->account = new Account(3);
        $this->account->generatePassword('abcd');
    }

    /**
     * @testdox The manager has the expected member variables
     */
    public function testAttributes() : void
    {
        self::assertInstanceOf('\phpOMS\Account\AccountManager', $this->manager);

        /* Testing members */
        self::assertObjectHasAttribute('accounts', $this->manager);
    }

    /**
     * @testdox The manager has the expected default values after initialization
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->manager->count());
        self::assertInstanceOf('\phpOMS\Account\Account', $this->manager->get(0));
        self::assertInstanceOf('\phpOMS\Account\NullAccount', $this->manager->get(-1));
    }

    /**
     * @testdox An account can be added to the manager
     */
    public function testAddAccount() : void
    {
        $added = $this->manager->add($this->account);
        self::assertTrue($added);
        self::assertEquals(1, $this->manager->count());
    }

    /**
     * @testdox An account can be retrieved from the manager
     */
    public function testRetrieveAccount() : void
    {
        $this->manager->add($this->account);
        self::assertEquals($this->account, $this->manager->get($this->account->getId()));
    }

    /**
     * @testdox An account can only be added once to the account manager (no duplication)
     */
    public function testNoAccountDuplication() : void
    {
        $this->manager->add($this->account);
        $added = $this->manager->add($this->account);
        self::assertFalse($added);

        self::assertTrue($this->manager->remove($this->account->getId()));
        self::assertFalse($this->manager->remove(-1));
        self::assertEquals(0, $this->manager->count());
    }

    /**
     * @testdox An account can be removed from the account manager
     */
    public function testRemoveAccount() : void
    {
        $this->manager->add($this->account);
        self::assertTrue($this->manager->remove($this->account->getId()));
        self::assertEquals(0, $this->manager->count());
        self::assertFalse($this->manager->remove(-1));
    }

    /**
     * @testdox Only a valid account can be removed from the manager
     */
    public function testRemoveOnlyValidAccount() : void
    {
        $this->manager->add($this->account);
        self::assertFalse($this->manager->remove(-1));
        self::assertEquals(1, $this->manager->count());
        self::assertTrue($this->manager->remove($this->account->getId()));
        self::assertEquals(0, $this->manager->count());
    }
}
