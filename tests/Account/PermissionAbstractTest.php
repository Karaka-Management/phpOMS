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

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;

/**
 * @testdox phpOMS\tests\Account\PermissionAbstractTest: Base permission representation
 *
 * @internal
 */
final class PermissionAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The permission has the expected default values after initialization
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testAbstractDefault() : void
    {
        $perm = new class() extends PermissionAbstract {};

        self::assertEquals(0, $perm->id);
        self::assertNull($perm->unit);
        self::assertNull($perm->app);
        self::assertNull($perm->module);
        self::assertEquals(0, $perm->from);
        self::assertNull($perm->element);
        self::assertNull($perm->component);
        self::assertEquals(PermissionType::NONE, $perm->getPermission());
        self::assertTrue($perm->hasPermission(PermissionType::NONE));
        self::assertTrue($perm->hasPermissionFlags(PermissionType::NONE));
        self::assertFalse($perm->hasPermission(2));
        self::assertFalse($perm->hasPermissionFlags(2));

        self::assertEquals(
            [
                'id'         => 0,
                'unit'       => null,
                'app'        => null,
                'module'     => null,
                'from'       => null,
                'element'    => null,
                'component'  => null,
                'permission' => PermissionType::NONE,
                'category'   => null,
            ],
            $perm->jsonSerialize()
        );
    }

    /**
     * @testdox The permission can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testPermissionInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setPermission(PermissionType::READ);
        self::assertEquals(PermissionType::READ, $perm->getPermission());
    }

    /**
     * @testdox Two permissions can be checked for equality
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testEqualPermissions() : void
    {
        $perm1 = new class() extends PermissionAbstract {};
        $perm1->unit = 1;
        $perm1->setPermission(PermissionType::READ);

        self::assertTrue($perm1->isEqual($perm1));

        $perm2 = new class() extends PermissionAbstract {};
        $perm2->unit = 1;
        $perm2->setPermission(PermissionType::CREATE);

        self::assertFalse($perm1->isEqual($perm2));
    }

    public function testFullPermissions() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->addPermission(PermissionType::READ);
        $perm->addPermission(PermissionType::CREATE);
        $perm->addPermission(PermissionType::MODIFY);
        $perm->addPermission(PermissionType::DELETE);
        $perm->addPermission(PermissionType::PERMISSION);

        self::assertEquals(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION,
            $perm->getPermission()
        );
    }

    /**
     * @testdox Correct permissions are validated
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testValidPermission() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setPermission(PermissionType::READ);
        $perm->addPermission(PermissionType::CREATE);
        self::assertTrue($perm->hasPermission(PermissionType::CREATE));
        self::assertTrue($perm->hasPermission(PermissionType::READ));
    }

    /**
     * @testdox Invalid permissions are not validated
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testInvalidPermission() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setPermission(PermissionType::READ);
        $perm->addPermission(PermissionType::CREATE);
        self::assertFalse($perm->hasPermission(PermissionType::MODIFY));
    }

    /**
     * @testdox Correct permission flags are validated
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testValidPermissionFlag() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setPermission(PermissionType::READ);
        $perm->addPermission(PermissionType::CREATE);
        self::assertTrue($perm->hasPermissionFlags(PermissionType::READ));
        self::assertTrue($perm->hasPermissionFlags(PermissionType::READ | PermissionType::CREATE));
    }

    /**
     * @testdox Invalid permission flags are not validated
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testInvalidPermissionFlag() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setPermission(PermissionType::READ);
        $perm->addPermission(PermissionType::CREATE);
        self::assertFalse($perm->hasPermissionFlags(PermissionType::MODIFY));
    }
}
