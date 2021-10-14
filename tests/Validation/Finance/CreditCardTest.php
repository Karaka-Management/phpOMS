<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\CreditCard;

/**
 * @testdox phpOMS\tests\Validation\Finance\CreditCardTest: Credit card validator
 *
 * @internal
 */
class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A credit card can be validated
     * @covers phpOMS\Validation\Finance\CreditCard
     * @group framework
     */
    public function testCreditCard() : void
    {
        self::assertTrue(CreditCard::isValid('49927398716'));
        self::assertTrue(CreditCard::isValid('4242424242424242'));
        self::assertFalse(CreditCard::isValid('4242424242424241'));

        self::assertTrue(CreditCard::luhnTest('49927398716'));
        self::assertFalse(CreditCard::luhnTest('49927398717'));
        self::assertFalse(CreditCard::luhnTest('4242424242424241'));
    }

    /**
     * @testdox A invalid type cannot be validated
     * @covers phpOMS\Validation\Finance\CreditCard
     * @group framework
     */
    public function testInvalidCreditCardType() : void
    {
        self::assertFalse(CreditCard::isValid(12347));
    }
}
