<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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

        self::assertEquals(0, $perm->getId());
        self::assertNull($perm->getUnit());
        self::assertNull($perm->getApp());
        self::assertNull($perm->getModule());
        self::assertEquals(0, $perm->getFrom());
        self::assertNull($perm->getType());
        self::assertNull($perm->getElement());
        self::assertNull($perm->getComponent());
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
                'from'       => 0,
                'type'       => null,
                'element'    => null,
                'component'  => null,
                'permission' => PermissionType::NONE,
            ],
            $perm->jsonSerialize()
        );
    }

    /**
     * @testdox The unit can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testUnitInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setUnit(1);
        self::assertEquals(1, $perm->getUnit());
    }

    /**
     * @testdox The app can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testAppInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setApp('Test');
        self::assertEquals('Test', $perm->getApp());
    }

    /**
     * @testdox The module can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testModuleInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setModule('2');
        self::assertEquals('2', $perm->getModule());
    }

    /**
     * @testdox The from can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testFromInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setFrom('3');
        self::assertEquals('3', $perm->getFrom());
    }

    /**
     * @testdox The type can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testTypeInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setType(4);
        self::assertEquals(4, $perm->getType());
    }

    /**
     * @testdox The element can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testElementInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setElement(5);
        self::assertEquals(5, $perm->getElement());
    }

    /**
     * @testdox The component can be set and returned correctly
     * @covers phpOMS\Account\PermissionAbstract
     * @group framework
     */
    public function testComponentInputOutput() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setComponent(6);
        self::assertEquals(6, $perm->getComponent());
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
        $perm1->setUnit(1);
        $perm1->setPermission(PermissionType::READ);

        self::assertTrue($perm1->isEqual($perm1));

        $perm2 = new class() extends PermissionAbstract {};
        $perm2->setUnit(1);
        $perm2->setPermission(PermissionType::CREATE);

        self::assertFalse($perm1->isEqual($perm2));
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
