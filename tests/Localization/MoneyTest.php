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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\Money;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\Localization\Money: Money datatype for internal representation of money')]
final class MoneyTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype has the expected member variables and default values')]
    public function testDefaultMemberVariables() : void
    {
        $money = new Money(0);
        self::assertGreaterThan(0, Money::MAX_DECIMALS);
        self::assertEquals(0, $money->getInt());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype returns the correct default string representation (#,###.##)')]
    public function testMoneyDefaultStringRepresentation() : void
    {
        $money = new Money(12345678);
        self::assertEquals('1,234.57', $money->getAmount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype returns up to 4 decimal places if requested (#,###.####)')]
    public function testMoneyDecimalPlaces() : void
    {
        $money = new Money(12345678);
        self::assertEquals('1,234.5678', $money->getAmount(4));
        self::assertEquals('1,234.5678', $money->getAmount(7));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype returns the correct integer representation of a string with up to 4 decimal places also considering differences in decimal and thousands characters if requested for different localizations')]
    public function testMoneyStringToIntConversion() : void
    {
        self::assertEquals(12345678, Money::toInt('1234.5678'));
        self::assertEquals(12345600, Money::toInt('1,234.56'));
        self::assertEquals(12345600, Money::toInt('1234,56', '.', ','));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype allows to modify the value by overwriting it with new string characters or integers correctly')]
    public function testCorrectValueChange() : void
    {
        $money = new Money(12345678);
        self::assertEquals('999.13', $money->setString('999.13')->getAmount());
        self::assertEquals('999.23', $money->setInt(9992300)->getAmount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype can print out money with different thousands, decimals and currency symbols as per definition by the user')]
    public function testMoneyLocalization() : void
    {
        $money = new Money(12345678);
        self::assertEquals('€ 9.992,30', $money->setInt(99923000)->setLocalization('.', ',')->getCurrency(symbol: '€'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The string character input is correctly serialized to the numeric representation')]
    public function testMoneySerialization() : void
    {
        $money = new Money('999.23');
        self::assertEquals(9992300, $money->serialize());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The string character input is correctly unserialized from a numeric representation')]
    public function testMoneyUnserialization() : void
    {
        $money = new Money('999.23');
        $money->unserialize(3331234);
        self::assertEquals('333.12', $money->getAmount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype correctly adds and subtracts the different money representations in string, numeric or Money type')]
    public function testMoneyAddSub() : void
    {
        $money = new Money(10000);
        self::assertEquals('1.0001', $money->add('0.0001')->getAmount(4));
        self::assertEquals('1.0000', $money->sub('0.0001')->getAmount(4));

        self::assertEquals('2.0000', $money->add(1.0)->getAmount(4));
        self::assertEquals('1.0000', $money->sub(1.0)->getAmount(4));

        self::assertEquals('1.0001', $money->add(1)->getAmount(4));
        self::assertEquals('1.0000', $money->sub(1)->getAmount(4));

        self::assertEquals('2.0000', $money->add(new Money(1.0))->getAmount(4));
        self::assertEquals('1.0000', $money->sub(new Money(10000))->getAmount(4));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype correctly multiplies and divides the money with numerics')]
    public function testMoneyMultDiv() : void
    {
        $money = new Money(19100);
        self::assertEquals('3.8200', $money->mult(2.0)->getAmount(4));
        self::assertEquals('1.9100', $money->div(2.0)->getAmount(4));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype correctly handles the absolute value')]
    public function testMoneyAbsoluteValue() : void
    {
        $money = new Money(-38200);
        self::assertEquals('3.8200', $money->mult(-1)->abs()->getAmount(4));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datatype correctly handles the power operator')]
    public function testMoneyPower() : void
    {
        $money = new Money(-38200);
        self::assertEquals('800.0000', $money->setInt(200)->pow(3)->getAmount(4));
    }
}
