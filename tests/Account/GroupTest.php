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

use phpOMS\Account\Group;
use phpOMS\Account\GroupStatus;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Account\Group::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Account\Group: Base group representation')]
final class GroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('The group has the expected default values after initialization')]
    public function testDefault() : void
    {
        $group = new Group();

        /* Testing default values */
        self::assertIsInt($group->id);
        self::assertEquals(0, $group->id);

        self::assertIsString($group->name);
        self::assertEquals('', $group->name);

        self::assertIsInt($group->status);
        self::assertEquals(GroupStatus::INACTIVE, $group->status);

        self::assertIsString($group->description);
        self::assertEquals('', $group->description);

        $array = $group->toArray();
        self::assertIsArray($array);
        self::assertGreaterThan(0, \count($array));
        self::assertEquals(\json_encode($array), $group->__toString());
        self::assertEquals($array, $group->jsonSerialize());
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('The group name and description can be set and retrieved correctly')]
    public function testSetAndGetGroupNameDescription() : void
    {
        $group = new Group();

        $group->name = 'Duck';
        self::assertEquals('Duck', $group->name);

        $group->description = 'Animal';
        self::assertEquals('Animal', $group->description);
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Group permissions can be added')]
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
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Group permissions can be checked for existence')]
    public function testPermissionExists() : void
    {
        $group = new Group();

        $group->addPermission(new class() extends PermissionAbstract {});
        self::assertCount(1, $group->getPermissions());

        self::assertFalse($group->hasPermission(PermissionType::READ, 1, null, 'a', 1, 1, 1));
        self::assertTrue($group->hasPermission(PermissionType::NONE));
    }

    /**
     * @group framework
     */
    #[\PHPUnit\Framework\Attributes\TestDox('Group permissions can be removed')]
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
}
