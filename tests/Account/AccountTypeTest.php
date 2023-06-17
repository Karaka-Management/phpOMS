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

use phpOMS\Account\AccountType;

/**
 * @testdox phpOMS\tests\Account\AccountType: Account type
 * @internal
 */
final class AccountTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The account type enum has the correct number of type codes
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(2, AccountType::getConstants());
    }

    /**
     * @testdox The account type enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(AccountType::getConstants(), \array_unique(AccountType::getConstants()));
    }

    /**
     * @testdox The account type enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, AccountType::USER);
        self::assertEquals(1, AccountType::GROUP);
    }
}
