<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

use phpOMS\Localization\ISO4217CharEnum;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Currency converter.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Currency
{
    /**
     * ECB currency rates.
     *
     * @var array<string, float>
     * @since 1.0.0
     */
    private static array $ecbCurrencies = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Reset currency rates.
     *
     * Can be used in order to refresh them. Be careful currency rates only get updated once a day from the ECB website.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function resetCurrencies() : void
    {
        self::$ecbCurrencies = [];
    }

    /**
     * Convert from EUR
     *
     * @param float  $value Value to convert
     * @param string $to    Output currency
     *
     * @return float
     *
     * @throws \InvalidArgumentException This exception is thrown if the currency to convert to doesn't exist
     *
     * @since 1.0.0
     */
    public static function fromEurTo(float $value, string $to) : float
    {
        $currencies = self::getEcbEuroRates();
        $to         = \strtoupper($to);

        if (!isset($currencies[$to])) {
            throw new \InvalidArgumentException('Currency doesn\'t exists');
        }

        return $value * $currencies[$to];
    }

    /**
     * Get ECB currency rates.
     *
     * @return array<string, float>
     *
     * @throws \Exception This exception is thrown if the XML is malformed
     *
     * @since 1.0.0
     */
    public static function getEcbEuroRates() : array
    {
        if (!empty(self::$ecbCurrencies)) {
            return self::$ecbCurrencies;
        }

        $request = new HttpRequest(new HttpUri('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml'));
        $request->setMethod(RequestMethod::GET);

        try {
            $xml = new \SimpleXMLElement(Rest::request($request)->getBody());

            if (!isset($xml->Cube)) {
                throw new \Exception('Invalid xml path'); // @codeCoverageIgnore
            }

            $node                = $xml->Cube->Cube->Cube;
            self::$ecbCurrencies = [];

            foreach ($node as $key => $value) {
                /** @var null|array<string, string|int|float> $attributes */
                if (($attributes = $value->attributes()) === null) {
                    continue;
                }

                self::$ecbCurrencies[\strtoupper((string) ($attributes['currency']))] = (float) ($attributes['rate']);
            }
        } catch (\Throwable $t) {
            self::$ecbCurrencies = []; // @codeCoverageIgnore
        }

        return self::$ecbCurrencies;
    }

    /**
     * Convert to EUR
     *
     * @param float  $value Value to convert
     * @param string $from  Input currency
     *
     * @return float
     *
     * @throws \InvalidArgumentException This exception is thrown if the currency to convert from doesn't exist
     *
     * @since 1.0.0
     */
    public static function fromToEur(float $value, string $from) : float
    {
        $currencies = self::getEcbEuroRates();
        $from       = \strtoupper($from);

        if (!isset($currencies[$from])) {
            throw new \InvalidArgumentException('Currency doesn\'t exists');
        }

        return $value / $currencies[$from];
    }

    /**
     * Convert currency based on ECB reates
     *
     * @param float  $value Value to convert
     * @param string $from  Input currency
     * @param string $to    Output currency
     *
     * @return float
     *
     * @throws \InvalidArgumentException This exception is thrown if either the from or to currency doesn't exist
     *
     * @since 1.0.0
     */
    public static function convertCurrency(float $value, string $from, string $to) : float
    {
        $currencies = self::getEcbEuroRates();
        $from       = \strtoupper($from);
        $to         = \strtoupper($to);

        if ((!isset($currencies[$from]) && $from !== ISO4217CharEnum::_EUR) || (!isset($currencies[$to]) && $to !== ISO4217CharEnum::_EUR)) {
            throw new \InvalidArgumentException('Currency doesn\'t exists');
        }

        if ($from !== ISO4217CharEnum::_EUR) {
            $value /= $currencies[$from];
        }

        return $to === ISO4217CharEnum::_EUR ? $value : $value * $currencies[$to];
    }
}
