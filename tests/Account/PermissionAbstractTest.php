<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;

/**
 * @internal
 */
class PermissionAbstractTest extends \PHPUnit\Framework\TestCase
{
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

    public function testAbstractGetSet() : void
    {
        $perm = new class() extends PermissionAbstract {};

        $perm->setUnit(1);
        self::assertEquals(1, $perm->getUnit());

        $perm->setApp('Test');
        self::assertEquals('Test', $perm->getApp());

        $perm->setModule('2');
        self::assertEquals('2', $perm->getModule());

        $perm->setFrom(3);
        self::assertEquals(3, $perm->getFrom());

        $perm->setType(4);
        self::assertEquals(4, $perm->getType());

        $perm->setElement(5);
        self::assertEquals(5, $perm->getElement());

        $perm->setComponent(6);
        self::assertEquals(6, $perm->getComponent());

        $perm->setPermission(PermissionType::READ);
        self::assertEquals(PermissionType::READ, $perm->getPermission());

        $perm->addPermission(PermissionType::CREATE);
        self::assertTrue($perm->hasPermission(PermissionType::CREATE));
        self::assertTrue($perm->hasPermission(PermissionType::READ));
        self::assertFalse($perm->hasPermission(PermissionType::MODIFY));

        self::assertTrue($perm->hasPermissionFlags(PermissionType::READ));
        self::assertTrue($perm->hasPermissionFlags(PermissionType::READ & PermissionType::CREATE));
        self::assertFalse($perm->hasPermissionFlags(PermissionType::MODIFY));
    }
}
