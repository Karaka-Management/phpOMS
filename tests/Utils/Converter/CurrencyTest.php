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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Localization\ISO4217CharEnum;
use phpOMS\Utils\Converter\Currency;

/**
 * @testdox phpOMS\tests\Utils\Converter\CurrencyTest: Currency converter
 *
 * @internal
 */
class CurrencyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A currency can be converted from euro to another currency
     * @covers phpOMS\Utils\Converter\Currency
     * @group framework
     */
    public function testCurrencyFromEur() : void
    {
        self::assertGreaterThan(0, Currency::fromEurTo(1, ISO4217CharEnum::_USD));
    }

    /**
     * @testdox A currency can be converted to euro from another currency
     * @covers phpOMS\Utils\Converter\Currency
     * @group framework
     */
    public function testCurrencyToEur() : void
    {
        self::assertGreaterThan(0, Currency::fromToEur(1, ISO4217CharEnum::_USD));
    }

    /**
     * @testdox A currency can be converted from one currency to another currency
     * @covers phpOMS\Utils\Converter\Currency
     * @group framework
     */
    public function testCurrency() : void
    {
        Currency::resetCurrencies();
        self::assertGreaterThan(0, Currency::convertCurrency(1, ISO4217CharEnum::_USD, ISO4217CharEnum::_GBP));
    }

    /**
     * @testdox A currency conversion from eur to a invalid currency throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Currency
     * @group framework
     */
    public function testInvalidFromEur() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Currency::fromEurTo(1, 'ERROR');
    }

    /**
     * @testdox A currency conversion from a invalid currency to eur throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Currency
     * @group framework
     */
    public function testInvalidToEur() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Currency::fromToEur(1, 'ERROR');
    }

    /**
     * @testdox A currency conversion from a invalid currency to a invalid currency throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Currency
     * @group framework
     */
    public function testInvalidConvert() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Currency::convertCurrency(1, 'ERROR', 'TEST');
    }
}
