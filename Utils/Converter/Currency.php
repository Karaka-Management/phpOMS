<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Converter;

/**
 * Currency converter.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Currency
{

    /**
     * ECB currency rates.
     *
     * @var array|null
     * @since 1.0.0
     */
    private static $ecbCurrencies = null;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * Reset currency rates.
     *
     * Can be used in order to refresh them. Be careful currency rates only get updated once a day from the ECB website.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function resetCurrencies() /* : void */
    {
        self::$ecbCurrencies = null;
    }

    /**
     * Convert from EUR
     *
     * @param float  $value Value to convert
     * @param string $to    Output currency
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function fromEurTo(float $value, string $to) : float
    {
        $currencies = self::getEcbEuroRates();
        $to         = strtoupper($to);

        if (!isset($currencies[$to])) {
            throw new \InvalidArgumentException('Currency doesn\'t exists');
        }

        return $value * $currencies[$to];
    }

    /**
     * Get ECB currency rates.
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getEcbEuroRates() : array
    {
        if (!isset(self::$ecbCurrencies)) {
            $xml = file_get_contents('http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
            $xml = new \SimpleXMLElement($xml);

            if (isset($xml->Cube)) {
                $node = $xml->Cube->Cube->Cube;
            } else {
                throw new \Exception('Invalid xml path');
            }

            self::$ecbCurrencies = [];
            foreach ($node as $key => $value) {
                self::$ecbCurrencies[strtoupper((string) $value->attributes()['currency'])] = (float) $value->attributes()['rate'];
            }
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function fromToEur(float $value, string $from) : float
    {
        $currencies = self::getEcbEuroRates();
        $from       = strtoupper($from);

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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertCurrency(float $value, string $from, string $to) : float
    {
        $currencies = self::getEcbEuroRates();
        $from       = strtoupper($from);
        $to         = strtoupper($to);

        if ((!isset($currencies[$from]) && $from !== 'EUR') || (!isset($currencies[$to]) && $to !== 'EUR')) {
            throw new \InvalidArgumentException('Currency doesn\'t exists');
        }

        if ($from !== 'EUR') {
            $value /= $currencies[$from];
        }

        return $to === 'EUR' ? $value : $value * $currencies[$to];
    }
}
