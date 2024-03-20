<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Account;

use phpOMS\Account\AccountManager;
use phpOMS\Account\NullAccount;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Account\AccountManager::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Account\AccountManager: Account/user manager to handle/access loaded accounts')]
final class AccountManagerTest extends \PHPUnit\Framework\TestCase
{
    protected $manager = null;

    protected $account = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->manager = new AccountManager($GLOBALS['session']);

        $this->account = new NullAccount(3);
        $this->account->generatePassword('abcd');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The manager has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->manager->count());
        self::assertInstanceOf('\phpOMS\Account\Account', $this->manager->get(0));
        self::assertInstanceOf('\phpOMS\Account\NullAccount', $this->manager->get(-1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An account can be added to the manager')]
    public function testAddAccount() : void
    {
        $added = $this->manager->add($this->account);
        self::assertTrue($added);
        self::assertEquals(1, $this->manager->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An account can be retrieved from the manager')]
    public function testRetrieveAccount() : void
    {
        $this->manager->add($this->account);
        self::assertEquals($this->account, $this->manager->get($this->account->id));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An account can only be added once to the account manager (no duplication)')]
    public function testNoAccountDuplication() : void
    {
        $this->manager->add($this->account);
        $added = $this->manager->add($this->account);
        self::assertFalse($added);

        self::assertTrue($this->manager->remove($this->account->id));
        self::assertFalse($this->manager->remove(-1));
        self::assertEquals(0, $this->manager->count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('An account can be removed from the account manager')]
    public function testRemoveAccount() : void
    {
        $this->manager->add($this->account);
        self::assertTrue($this->manager->remove($this->account->id));
        self::assertEquals(0, $this->manager->count());
        self::assertFalse($this->manager->remove(-1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Only a valid account can be removed from the manager')]
    public function testRemoveOnlyValidAccount() : void
    {
        $this->manager->add($this->account);
        self::assertFalse($this->manager->remove(-1));
        self::assertEquals(1, $this->manager->count());
        self::assertTrue($this->manager->remove($this->account->id));
        self::assertEquals(0, $this->manager->count());
    }
}
