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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Localization\ISO4217CharEnum;
use phpOMS\Utils\Converter\Currency;

class CurrencyTest extends \PHPUnit\Framework\TestCase
{
    public function testCurrency() : void
    {
        self::assertGreaterThan(0, Currency::fromEurTo(1, ISO4217CharEnum::_USD));
        self::assertGreaterThan(0, Currency::fromToEur(1, ISO4217CharEnum::_USD));

        Currency::resetCurrencies();
        self::assertGreaterThan(0, Currency::convertCurrency(1, ISO4217CharEnum::_USD, ISO4217CharEnum::_GBP));
    }

    public function testInvalidFromEur() : void
    {
        self::expectedException(\InvalidArgumentException::class);

        Currency::fromEurTo(1, 'ERROR');
    }

    public function testInvalidToEur() : void
    {
        self::expectedException(\InvalidArgumentException::class);

        Currency::fromToEur(1, 'ERROR');
    }

    public function testInvalidConvert() : void
    {
        self::expectedException(\InvalidArgumentException::class);

        Currency::convertCurrency(1, 'ERROR', 'TEST');
    }
}
