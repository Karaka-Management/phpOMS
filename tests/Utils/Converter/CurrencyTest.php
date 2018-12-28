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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFromEur() : void
    {
        Currency::fromEurTo(1, 'ERROR');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidToEur() : void
    {
        Currency::fromToEur(1, 'ERROR');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConvert() : void
    {
        Currency::convertCurrency(1, 'ERROR', 'TEST');
    }
}
