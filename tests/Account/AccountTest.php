<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Account;

use phpOMS\Account\Account;
use phpOMS\Account\AccountStatus;
use phpOMS\Account\AccountType;
use phpOMS\Account\Group;
use phpOMS\Account\NullGroup;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Account\Account::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Account\Account: Base account/user representation')]
final class AccountTest extends \PHPUnit\Framework\TestCase
{
    protected $l11nManager = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->l11nManager = new L11nManager('Api');
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('The account has the expected default values after initialization')]
    public function testDefault() : void
    {
        $account = new Account();

        /* Testing default values */
        self::assertIsInt($account->id);
        self::assertEquals(0, $account->id);

        self::assertInstanceOf('\phpOMS\Localization\Localization', $account->l11n);

        self::assertEquals([], $account->getGroups());

        self::assertNull($account->login);

        self::assertIsString($account->name1);
        self::assertEquals('', $account->name1);

        self::assertIsString($account->name2);
        self::assertEquals('', $account->name2);

        self::assertIsString($account->name3);
        self::assertEquals('', $account->name3);

        self::assertIsString($account->getEmail());
        self::assertEquals('', $account->getEmail());

        self::assertIsInt($account->status);
        self::assertEquals(AccountStatus::INACTIVE, $account->status);

        self::assertIsInt($account->type);
        self::assertEquals(AccountType::USER, $account->type);

        self::assertEquals([], $account->getPermissions());
        self::assertFalse($account->hasGroup(2));

        self::assertInstanceOf('\DateTimeInterface', $account->getLastActive());
        self::assertInstanceOf('\DateTimeImmutable', $account->createdAt);

        $array = $account->toArray();
        self::assertIsArray($array);
        self::assertGreaterThan(0, \count($array));
        self::assertEquals(\json_encode($array), $account->__toString());
        self::assertEquals($array, $account->jsonSerialize());
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('The account names can be set and retrieved correctly')]
    public function testSetAndGetAccountNames() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->login = 'Login';
        self::assertEquals('Login', $account->login);

        $account->name1 = 'Donald';
        self::assertEquals('Donald', $account->name1);

        $account->name2 = 'Fauntleroy';
        self::assertEquals('Fauntleroy', $account->name2);

        $account->name3 = 'Duck';
        self::assertEquals('Duck', $account->name3);
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Groups can be added to an account')]
    public function testAddAndGetGroup() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addGroup(new NullGroup(2));
        self::assertCount(1, $account->getGroups());
        self::assertTrue($account->hasGroup(2));
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('An account can have a valid email address')]
    public function testSetAndGetAccountEmail() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setEmail('d.duck@duckburg.com');
        self::assertEquals('d.duck@duckburg.com', $account->getEmail());
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Account permissions can be added')]
    public function testPermissionAdd() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addPermission(new class() extends PermissionAbstract {});
        self::assertCount(1, $account->getPermissions());

        $account->setPermissions([
            new class() extends PermissionAbstract {},
            new class() extends PermissionAbstract {},
        ]);
        self::assertCount(2, $account->getPermissions());

        $account->addPermissions([
            new class() extends PermissionAbstract {},
            new class() extends PermissionAbstract {},
        ]);
        self::assertCount(4, $account->getPermissions());

        $account->addPermissions([[
            new class() extends PermissionAbstract {},
            new class() extends PermissionAbstract {},
        ]]);
        self::assertCount(6, $account->getPermissions());
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Account permissions can be checked for existence')]
    public function testPermissionExists() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addPermission(new class() extends PermissionAbstract {});
        self::assertCount(1, $account->getPermissions());

        self::assertFalse($account->hasPermission(PermissionType::READ, 1, 2, 'a', 1, 1, 1));
        self::assertTrue($account->hasPermission(PermissionType::NONE));
    }

    public function testGroupPmerissionExists() : void
    {
        $account = new Account();
        $group   = new NullGroup(2);

        $perm = new class() extends PermissionAbstract {};
        $perm->addPermission(PermissionType::CREATE);

        $group->addPermission($perm);
        $account->hasPermission(PermissionType::READ);
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Account permissions can be removed')]
    public function testPermissionRemove() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $perm = new class() extends PermissionAbstract {};
        $perm->setPermission(PermissionType::READ);

        $account->addPermission($perm);
        self::assertCount(1, $account->getPermissions());

        $account->removePermission($perm);
        self::assertCount(0, $account->getPermissions());
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox("An account can have it's own localization")]
    public function testLocalization() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->l11n = new Localization();
        self::assertInstanceOf('\phpOMS\Localization\Localization', $account->l11n);
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox("An account 'last activity' timestamp can be updated and retrieved")]
    public function testLastChange() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $datetime = new \DateTime('now');
        $account->updateLastActive();
        self::assertEquals($datetime->format('Y-m-d h:i:s'), $account->getLastActive()->format('Y-m-d h:i:s'));
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('An account can only have a valid email')]
    public function testEmailException() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $account = new Account();
        $account->setEmail('d.duck!@#%@duckburg');
    }
}
