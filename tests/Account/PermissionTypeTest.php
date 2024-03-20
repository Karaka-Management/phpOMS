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

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\PermissionType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Account\PermissionType: Permission type')]
final class PermissionTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission type enum has the correct number of type codes')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(6, PermissionType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission type enum has only unique values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(PermissionType::getConstants(), \array_unique(PermissionType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The permission type enum has the correct values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
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
