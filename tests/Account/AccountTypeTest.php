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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\AccountType;

/**
 * @internal
 */
class AccountTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(2, AccountType::getConstants());
        self::assertEquals(0, AccountType::USER);
        self::assertEquals(1, AccountType::GROUP);
    }
}
