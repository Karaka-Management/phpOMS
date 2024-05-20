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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Localization\ISO4217CharEnum;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\Converter\Currency;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Converter\Currency::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Converter\CurrencyTest: Currency converter')]
final class CurrencyTest extends \PHPUnit\Framework\TestCase
{
    private static $reachable;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (!isset(self::$reachable)) {
            try {
                $request = new HttpRequest(new HttpUri('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml'));
                $request->setMethod(RequestMethod::GET);

                Rest::request($request)->getBody();
                self::$reachable = true;
            } catch (\Throwable $_) {
                self::$reachable = false;
            }
        }

        if (!self::$reachable) {
            $this->markTestSkipped(
                'External currency conversion not available.'
            );
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A currency can be converted from euro to another currency')]
    public function testCurrencyFromEur() : void
    {
        self::assertGreaterThan(0, Currency::fromEurTo(1, ISO4217CharEnum::_USD));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A currency can be converted to euro from another currency')]
    public function testCurrencyToEur() : void
    {
        self::assertGreaterThan(0, Currency::fromToEur(1, ISO4217CharEnum::_USD));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A currency can be converted from one currency to another currency')]
    public function testCurrency() : void
    {
        Currency::resetCurrencies();
        self::assertGreaterThan(0, Currency::convertCurrency(1, ISO4217CharEnum::_USD, ISO4217CharEnum::_GBP));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A currency conversion from eur to a invalid currency throws a InvalidArgumentException')]
    public function testInvalidFromEur() : void
    {
        self::assertLessThan(0,  Currency::fromEurTo(1, 'ERROR'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A currency conversion from a invalid currency to eur throws a InvalidArgumentException')]
    public function testInvalidToEur() : void
    {
        self::assertLessThan(0, Currency::fromToEur(1, 'ERROR'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A currency conversion from a invalid currency to a invalid currency throws a InvalidArgumentException')]
    public function testInvalidConvert() : void
    {
        self::assertLessThan(0, Currency::convertCurrency(1, 'ERROR', 'TEST'));
    }
}
