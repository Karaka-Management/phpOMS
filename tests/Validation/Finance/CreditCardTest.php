<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\CreditCard;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Validation\Finance\CreditCard::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Validation\Finance\CreditCardTest: Credit card validator')]
final class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A credit card can be validated')]
    public function testCreditCard() : void
    {
        self::assertTrue(CreditCard::isValid('49927398716'));
        self::assertTrue(CreditCard::isValid('4242424242424242'));
        self::assertFalse(CreditCard::isValid('4242424242424241'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid type cannot be validated')]
    public function testInvalidCreditCardType() : void
    {
        self::assertFalse(CreditCard::isValid(12347));
    }
}
