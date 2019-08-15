<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\CreditCard;

/**
 * @internal
 */
class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    public function testCreditCard() : void
    {
        self::assertTrue(CreditCard::isValid('4242424242424242'));
        self::assertFalse(CreditCard::isValid('4242424242424241'));

        self::assertTrue(CreditCard::luhnTest('49927398716'));
        self::assertFalse(CreditCard::luhnTest('49927398717'));
        self::assertFalse(CreditCard::luhnTest('4242424242424241'));
    }
}
