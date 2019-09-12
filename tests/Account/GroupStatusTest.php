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

use phpOMS\Account\GroupStatus;

/**
 * @internal
 */
class GroupStatusTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(3, GroupStatus::getConstants());
        self::assertEquals(1, GroupStatus::ACTIVE);
        self::assertEquals(2, GroupStatus::INACTIVE);
        self::assertEquals(4, GroupStatus::HIDDEN);
    }
}
