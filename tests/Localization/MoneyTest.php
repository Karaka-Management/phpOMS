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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO4217SymbolEnum;
use phpOMS\Localization\Money;

/**
 * @testdox phpOMS\Localization\Money: Money datatype for internal representation of money
 *
 * @internal
 */
class MoneyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The datatype has the expected member variables and default values
     * @group framework
     */
    public function testDefaultMemberVariables() : void
    {
        $money = new Money(0);
        self::assertObjectHasAttribute('thousands', $money);
        self::assertObjectHasAttribute('decimal', $money);
        self::assertObjectHasAttribute('value', $money);

        self::assertGreaterThan(0, Money::MAX_DECIMALS);

        self::assertEquals(0, $money->getInt());
    }

    /**
     * @testdox The datatype returns the correct default string representation (#,###.##)
     * @group framework
     */
    public function testMoneyDefaultStringRepresentation() : void
    {
        $money = new Money(12345678);
        self::assertEquals('1,234.57', $money->getAmount());
    }

    /**
     * @testdox The datatype returns up to 4 decimal places if requested (#,###.####)
     * @group framework
     */
    public function testMoneyDecimalPlaces() : void
    {
        $money = new Money(12345678);
        self::assertEquals('1,234.5678', $money->getAmount(4));
        self::assertEquals('1,234.5678', $money->getAmount(7));
    }

    /**
     * @testdox The datatype returns the correct integer representation of a string with up to 4 decimal places also considering differences in decimal and thousands characters if requested for different localizations
     * @group framework
     */
    public function testMoneyStringToIntConversion() : void
    {
        self::assertEquals(12345678, Money::toInt('1234.5678'));
        self::assertEquals(12345600, Money::toInt('1,234.56'));
        self::assertEquals(12345600, Money::toInt('1234,56', '.', ','));
    }

    /**
     * @testdox The datatype allows to modify the value by overwriting it with new string characters or integers correctly
     * @group framework
     */
    public function testCorrectValueChange() : void
    {
        $money = new Money(12345678);
        self::assertEquals('999.13', $money->setString('999.13')->getAmount());
        self::assertEquals('999.23', $money->setInt(9992300)->getAmount());
    }

    /**
     * @testdox The datatype can print out money with different thousands, decimals and currency symbols as per definition by the user
     * @group framework
     */
    public function testMoneyLocalization() : void
    {
        $money = new Money(12345678);
        self::assertEquals('€ 9.992,30', $money->setInt(99923000)->setLocalization('.', ',', ISO4217SymbolEnum::_EUR, 0)->getCurrency());
    }

    /**
     * @testdox The string character input is correctly serialized to the numeric representation
     * @group framework
     */
    public function testMoneySerialization() : void
    {
        $money = new Money('999.23');
        self::assertEquals(9992300, $money->serialize());
    }

    /**
     * @testdox The string character input is correctly unserialized from a numeric representation
     * @group framework
     */
    public function testMoneyUnserialization() : void
    {
        $money = new Money('999.23');
        $money->unserialize(3331234);
        self::assertEquals('333.12', $money->getAmount());
    }

    /**
     * @testdox The datatype correctly adds and subtracts the different money representations in string, numeric or Money type
     * @group framework
     */
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

    /**
     * @testdox The datatype correctly multiplies and divides the money with numerics
     * @group framework
     */
    public function testMoneyMultDiv() : void
    {
        $money = new Money(19100);
        self::assertEquals('3.8200', $money->mult(2.0)->getAmount(4));
        self::assertEquals('1.9100', $money->div(2.0)->getAmount(4));
    }

    /**
     * @testdox The datatype correctly handles the absolute value
     * @group framework
     */
    public function testMoneyAbsoluteValue() : void
    {
        $money = new Money(-38200);
        self::assertEquals('3.8200', $money->mult(-1)->abs()->getAmount(4));
    }

    /**
     * @testdox The datatype correctly handles the power operator
     * @group framework
     */
    public function testMoneyPower() : void
    {
        $money = new Money(-38200);
        self::assertEquals('800.0000', $money->setInt(200)->pow(3)->getAmount(4));
    }
}
