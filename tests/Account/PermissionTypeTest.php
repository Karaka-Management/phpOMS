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

use phpOMS\Account\PermissionType;

/**
 * @internal
 */
class PermissionTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(6, PermissionType::getConstants());
        self::assertEquals(PermissionType::getConstants(), \array_unique(PermissionType::getConstants()));

        self::assertEquals(1, PermissionType::NONE);
        self::assertEquals(2, PermissionType::READ);
        self::assertEquals(4, PermissionType::CREATE);
        self::assertEquals(8, PermissionType::MODIFY);
        self::assertEquals(16, PermissionType::DELETE);
        self::assertEquals(32, PermissionType::PERMISSION);
    }
}
