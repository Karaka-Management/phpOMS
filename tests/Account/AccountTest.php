<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Account;

use phpOMS\Account\Account;
use phpOMS\Account\AccountStatus;
use phpOMS\Account\AccountType;
use phpOMS\Account\Group;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Account\Account: Base account/user representation
 *
 * @internal
 */
class AccountTest extends \PHPUnit\Framework\TestCase
{
    protected $l11nManager = null;

    protected function setUp() : void
    {
        $this->l11nManager = new L11nManager('Api');
    }

    /**
     * @testdox The account has the expected member variables
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testAttributes() : void
    {
        $account = new Account();
        self::assertInstanceOf('\phpOMS\Account\Account', $account);

        /* Testing members */
        self::assertObjectHasAttribute('id', $account);
        self::assertObjectHasAttribute('name1', $account);
        self::assertObjectHasAttribute('name2', $account);
        self::assertObjectHasAttribute('name3', $account);
        self::assertObjectHasAttribute('email', $account);
        self::assertObjectHasAttribute('origin', $account);
        self::assertObjectHasAttribute('login', $account);
        self::assertObjectHasAttribute('lastActive', $account);
        self::assertObjectHasAttribute('createdAt', $account);
        self::assertObjectHasAttribute('permissions', $account);
        self::assertObjectHasAttribute('groups', $account);
        self::assertObjectHasAttribute('type', $account);
        self::assertObjectHasAttribute('status', $account);
        self::assertObjectHasAttribute('l11n', $account);
    }

    /**
     * @testdox The account has the expected default values after initialization
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        $account = new Account();

        /* Testing default values */
        self::assertIsInt($account->getId());
        self::assertEquals(0, $account->getId());

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

        self::assertIsInt($account->getStatus());
        self::assertEquals(AccountStatus::INACTIVE, $account->getStatus());

        self::assertIsInt($account->getType());
        self::assertEquals(AccountType::USER, $account->getType());

        self::assertEquals([], $account->getPermissions());

        self::assertInstanceOf('\DateTimeInterface', $account->getLastActive());
        self::assertInstanceOf('\DateTimeImmutable', $account->createdAt);

        $array = $account->toArray();
        self::assertIsArray($array);
        self::assertGreaterThan(0, \count($array));
        self::assertEquals(\json_encode($array), $account->__toString());
        self::assertEquals($array, $account->jsonSerialize());
    }

    /**
     * @testdox The account names can be set and retrieved correctly
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
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
     * @testdox Groups can be added to an account
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testAddAndGetGroup() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addGroup(new Group());
        self::assertCount(1, $account->getGroups());
    }

    /**
     * @testdox An account can have a valid email address
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testSetAndGetAccountEmail() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setEmail('d.duck@duckburg.com');
        self::assertEquals('d.duck@duckburg.com', $account->getEmail());
    }

    /**
     * @testdox The default status of the account can be changed to a different valid status
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testChangeStatus() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setStatus(AccountStatus::ACTIVE);
        self::assertEquals(AccountStatus::ACTIVE, $account->getStatus());
    }

    /**
     * @testdox The default type of the account can be changed to a different valid type
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testChangeType() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setType(AccountType::GROUP);
        self::assertEquals(AccountType::GROUP, $account->getType());
    }

    /**
     * @testdox Account permissions can be added
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
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
     * @testdox Account permissions can be checked for existence
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testPermissionExists() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addPermission(new class() extends PermissionAbstract {});
        self::assertCount(1, $account->getPermissions());

        self::assertFalse($account->hasPermission(PermissionType::READ, 1, 'a', 'a', 1, 1, 1));
        self::assertTrue($account->hasPermission(PermissionType::NONE));
    }

    /**
     * @testdox Account permissions can be removed
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
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
     * @testdox An account can have it's own localization
     * @covers phpOMS\Account\Account<extended>
     * @group framework
     */
    public function testLocalization() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->l11n = new Localization();
        self::assertInstanceOf('\phpOMS\Localization\Localization', $account->l11n);
    }

    /**
     * @testdox An account 'last activity' timestamp can be updated and retrieved
     * @group framework
     */
    public function testLastChange() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $datetime = new \DateTime('now');
        $account->updateLastActive();
        self::assertEquals($datetime->format('Y-m-d h:i:s'), $account->getLastActive()->format('Y-m-d h:i:s'));
    }

    /**
     * @testdox An account can only have a valid email
     * @group framework
     */
    public function testEmailException() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        $account = new Account();
        $account->setEmail('d.duck!@#%@duckburg');
    }

    /**
     * @testdox An account can only have valid account status
     * @group framework
     */
    public function testStatusException() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $account = new Account();

        $rand = 0;
        do {
            $rand = \mt_rand(\PHP_INT_MIN, \PHP_INT_MAX);
        } while (AccountStatus::isValidValue($rand));

        $account->setStatus($rand);
    }

    /**
     * @testdox An account can only have valid account types
     * @group framework
     */
    public function testTypeException() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $account = new Account();

        $rand = 0;
        do {
            $rand = \mt_rand(\PHP_INT_MIN, \PHP_INT_MAX);
        } while (AccountType::isValidValue($rand));

        $account->setType($rand);
    }
}
