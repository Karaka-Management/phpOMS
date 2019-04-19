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
 * @link       http://website.orange-management.de
 */

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
     */
    public function testDefault() : void
    {
        $account = new Account();

        /* Testing default values */
        self::assertTrue(\is_int($account->getId()));
        self::assertEquals(0, $account->getId());

        self::assertInstanceOf('\phpOMS\Localization\Localization', $account->getL11n());

        self::assertEquals([], $account->getGroups());

        self::assertEquals(null, $account->getName());

        self::assertTrue(\is_string($account->getName1()));
        self::assertEquals('', $account->getName1());

        self::assertTrue(\is_string($account->getName2()));
        self::assertEquals('', $account->getName2());

        self::assertTrue(\is_string($account->getName3()));
        self::assertEquals('', $account->getName3());

        self::assertTrue(\is_string($account->getEmail()));
        self::assertEquals('', $account->getEmail());

        self::assertTrue(\is_int($account->getStatus()));
        self::assertEquals(AccountStatus::INACTIVE, $account->getStatus());

        self::assertTrue(\is_int($account->getType()));
        self::assertEquals(AccountType::USER, $account->getType());

        self::assertEquals([], $account->getPermissions());

        self::assertInstanceOf('\DateTime', $account->getLastActive());
        self::assertInstanceOf('\DateTime', $account->getCreatedAt());

        $array = $account->toArray();
        self::assertTrue(\is_array($array));
        self::assertGreaterThan(0, \count($array));
        self::assertEquals(\json_encode($array), $account->__toString());
        self::assertEquals($array, $account->jsonSerialize());
    }

    /**
     * @testdox The account names can be set and retrieved correctly
     */
    public function testSetAndGetAccountNames() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setName('Login');
        self::assertEquals('Login', $account->getName());

        $account->setName1('Donald');
        self::assertEquals('Donald', $account->getName1());

        $account->setName2('Fauntleroy');
        self::assertEquals('Fauntleroy', $account->getName2());

        $account->setName3('Duck');
        self::assertEquals('Duck', $account->getName3());

        $account->setName('Login');
        self::assertEquals('Login', $account->getName());
    }

    /**
     * @testdox Groups can be added to an account
     */
    public function testAddAndGetGroup() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addGroup(new Group());
        self::assertEquals(1, \count($account->getGroups()));
    }

    /**
     * @testdox An account can have a valid email address
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
     */
    public function testChangeType() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setType(AccountType::GROUP);
        self::assertEquals(AccountType::GROUP, $account->getType());
    }

    /**
     * @testdox Account permissions can be added and checked for existence
     */
    public function testPermissionHandling() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->addPermission(new class extends PermissionAbstract {});
        self::assertEquals(1, \count($account->getPermissions()));

        $account->setPermissions([
            new class extends PermissionAbstract {},
            new class extends PermissionAbstract {},
        ]);
        self::assertEquals(2, \count($account->getPermissions()));

        $account->addPermissions([
            new class extends PermissionAbstract {},
            new class extends PermissionAbstract {},
        ]);
        self::assertEquals(4, \count($account->getPermissions()));

        $account->addPermissions([[
            new class extends PermissionAbstract {},
            new class extends PermissionAbstract {},
        ]]);
        self::assertEquals(6, \count($account->getPermissions()));

        self::assertFalse($account->hasPermission(PermissionType::READ, 1, 'a', 'a', 1, 1, 1));
        self::assertTrue($account->hasPermission(PermissionType::NONE));
    }

    /**
     * @testdox An account can have it's own localization
     */
    public function testLocalization() : void
    {
        $account = new Account();
        $account->generatePassword('abcd');

        $account->setL11n(new Localization());
        self::assertInstanceOf('\phpOMS\Localization\Localization', $account->getL11n());
    }

    /**
     * @testdox An account 'last activity' timestamp can be updated and retrieved
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
     */
    public function testEmailException() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $account = new Account();
        $account->setEmail('d.duck!@#%@duckburg');
    }

    /**
     * @testdox An account can only have valid account status
     */
    public function testStatusException() : void
    {
        self::expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $account = new Account();

        $rand = 0;
        do {
            $rand = \mt_rand(PHP_INT_MIN, PHP_INT_MAX);
        } while (AccountStatus::isValidValue($rand));

        $account->setStatus($rand);
    }

    /**
     * @testdox An account can only have valid account types
     */
    public function testTypeException() : void
    {
        self::expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $account = new Account();

        $rand = 0;
        do {
            $rand = \mt_rand(PHP_INT_MIN, PHP_INT_MAX);
        } while (AccountType::isValidValue($rand));

        $account->setType($rand);
    }
}
