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

use phpOMS\Account\AccountStatus;

class AccountStatusTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(4, \count(AccountStatus::getConstants()));
        self::assertEquals(1, AccountStatus::ACTIVE);
        self::assertEquals(2, AccountStatus::INACTIVE);
        self::assertEquals(3, AccountStatus::TIMEOUT);
        self::assertEquals(4, AccountStatus::BANNED);
    }
}
