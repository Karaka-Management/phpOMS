<?php
/**
 * Karaka
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

use phpOMS\Account\PermissionType;

/**
 * @testdox phpOMS\tests\Account\PermissionType: Permission type
 * @internal
 */
final class PermissionTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The permission type enum has the correct number of type codes
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(6, PermissionType::getConstants());
    }

    /**
     * @testdox The permission type enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(PermissionType::getConstants(), \array_unique(PermissionType::getConstants()));
    }

    /**
     * @testdox The permission type enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, PermissionType::NONE);
        self::assertEquals(2, PermissionType::READ);
        self::assertEquals(4, PermissionType::CREATE);
        self::assertEquals(8, PermissionType::MODIFY);
        self::assertEquals(16, PermissionType::DELETE);
        self::assertEquals(32, PermissionType::PERMISSION);
    }
}
