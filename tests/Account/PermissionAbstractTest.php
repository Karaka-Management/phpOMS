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

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;

class PermissionAbstractTest extends \PHPUnit\Framework\TestCase
{
    public function testAbstractDefault()
    {
        $perm = new class extends PermissionAbstract {};

        self::assertEquals(0, $perm->getId());
        self::assertEquals(null, $perm->getUnit());
        self::assertEquals(null, $perm->getApp());
        self::assertEquals(null, $perm->getModule());
        self::assertEquals(0, $perm->getFrom());
        self::assertEquals(null, $perm->getType());
        self::assertEquals(null, $perm->getElement());
        self::assertEquals(null, $perm->getComponent());
        self::assertEquals(PermissionType::NONE, $perm->getPermission());

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

    public function testAbstractGetSet()
    {
        $perm = new class extends PermissionAbstract {};

        $perm->setUnit(1);
        self::assertEquals(1, $perm->getUnit());

        $perm->setApp('Test');
        self::assertEquals('Test', $perm->getApp());

        $perm->setModule(2);
        self::assertEquals(2, $perm->getModule());

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
    }
}
