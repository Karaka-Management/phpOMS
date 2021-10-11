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

use phpOMS\Account\Group;
use phpOMS\Account\GroupStatus;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Account\Group: Base group representation
 *
 * @internal
 */
class GroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The group has the expected member variables
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testAttributes() : void
    {
        $group = new Group();
        self::assertInstanceOf('\phpOMS\Account\Group', $group);

        /* Testing members */
        self::assertObjectHasAttribute('id', $group);
        self::assertObjectHasAttribute('name', $group);
        self::assertObjectHasAttribute('description', $group);
        self::assertObjectHasAttribute('members', $group);
        self::assertObjectHasAttribute('parents', $group);
        self::assertObjectHasAttribute('permissions', $group);
        self::assertObjectHasAttribute('status', $group);
    }

    /**
     * @testdox The group has the expected default values after initialization
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        $group = new Group();

        /* Testing default values */
        self::assertIsInt($group->getId());
        self::assertEquals(0, $group->getId());

        self::assertIsString($group->name);
        self::assertEquals('', $group->name);

        self::assertIsInt($group->getStatus());
        self::assertEquals(GroupStatus::INACTIVE, $group->getStatus());

        self::assertIsString($group->description);
        self::assertEquals('', $group->description);

        $array = $group->toArray();
        self::assertIsArray($array);
        self::assertGreaterThan(0, \count($array));
        self::assertEquals(json_encode($array), $group->__toString());
        self::assertEquals($array, $group->jsonSerialize());
    }

    /**
     * @testdox The group name and description can be set and retrieved correctly
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testSetAndGetGroupNameDescription() : void
    {
        $group = new Group();

        $group->name = 'Duck';
        self::assertEquals('Duck', $group->name);

        $group->description = 'Animal';
        self::assertEquals('Animal', $group->description);
    }

    /**
     * @testdox Group permissions can be added
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testPermissionAdd() : void
    {
        $group = new Group();

        $group->addPermission(new class() extends PermissionAbstract {});
        self::assertCount(1, $group->getPermissions());

        $group->setPermissions([
            new class() extends PermissionAbstract {},
            new class() extends PermissionAbstract {},
        ]);
        self::assertCount(2, $group->getPermissions());

        $group->addPermissions([
            new class() extends PermissionAbstract {},
            new class() extends PermissionAbstract {},
        ]);
        self::assertCount(4, $group->getPermissions());

        $group->addPermissions([[
            new class() extends PermissionAbstract {},
            new class() extends PermissionAbstract {},
        ]]);
        self::assertCount(6, $group->getPermissions());
    }

    /**
     * @testdox Group permissions can be checked for existence
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testPermissionExists() : void
    {
        $group = new Group();

        $group->addPermission(new class() extends PermissionAbstract {});
        self::assertCount(1, $group->getPermissions());

        self::assertFalse($group->hasPermission(PermissionType::READ, 1, 'a', 'a', 1, 1, 1));
        self::assertTrue($group->hasPermission(PermissionType::NONE));
    }

    /**
     * @testdox Group permissions can be removed
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testPermissionRemove() : void
    {
        $group = new Group();

        $perm = new class() extends PermissionAbstract {};
        $perm->setPermission(PermissionType::READ);

        $group->addPermission($perm);
        self::assertCount(1, $group->getPermissions());

        $group->removePermission($perm);
        self::assertCount(0, $group->getPermissions());
    }

    /**
     * @testdox The default status of the group can be changed to a different valid status
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testChangeStatus() : void
    {
        $group = new Group();

        $group->setStatus(GroupStatus::ACTIVE);
        self::assertEquals(GroupStatus::ACTIVE, $group->getStatus());
    }

    /**
     * @testdox A group can only have valid group status
     * @covers phpOMS\Account\Group<extended>
     * @group framework
     */
    public function testStatusException() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $group = new Group();

        $rand = 0;
        do {
            $rand = mt_rand(\PHP_INT_MIN, \PHP_INT_MAX);
        } while (GroupStatus::isValidValue($rand));

        $group->setStatus($rand);
    }
}
