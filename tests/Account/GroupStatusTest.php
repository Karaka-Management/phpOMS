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

use phpOMS\Account\GroupStatus;

/**
 * @testdox phpOMS\tests\Account\GroupStatus: Group status
 * @internal
 */
final class GroupStatusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The group status enum has the correct number of status codes
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(3, GroupStatus::getConstants());
    }

    /**
     * @testdox The group status enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(GroupStatus::getConstants(), \array_unique(GroupStatus::getConstants()));
    }

    /**
     * @testdox The group status enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, GroupStatus::ACTIVE);
        self::assertEquals(2, GroupStatus::INACTIVE);
        self::assertEquals(4, GroupStatus::HIDDEN);
    }
}
