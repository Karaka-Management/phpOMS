<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Localization;

use phpOMS\Datatypes\Exception\InvalidEnumValue;
use phpOMS\Utils\Converter\AngleType;
use phpOMS\Utils\Converter\TemperatureType;

/**
 * Localization class.
 *
 * @category   Framework
 * @package    phpOMS\Localization
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Localization
{

    /**
     * Country ID.
     *
     * @var string
     * @since 1.0.0
     */
    private $country = ISO3166TwoEnum::_USA;
    /**
     * Timezone.
     *
     * @var string
     * @since 1.0.0
     */
    private $timezone = 'America/New_York';
    /**
     * Language ISO code.
     *
     * @var string
     * @since 1.0.0
     */
    private $language = ISO639x1Enum::_EN;
    /**
     * Currency.
     *
     * @var string
     * @since 1.0.0
     */
    private $currency = ISO4217Enum::_USD;
    /**
     * Number format.
     *
     * @var string
     * @since 1.0.0
     */
    private $decimal = '.';
    /**
     * Number format.
     *
     * @var string
     * @since 1.0.0
     */
    private $thousands = ',';

    /**
     * Angle type.
     *
     * @var string
     * @since 1.0.0
     */
    private $angle = AngleType::DEGREE;

    /**
     * Temperature type.
     *
     * @var string
     * @since 1.0.0
     */
    private $temperature = TemperatureType::CELSIUS;

    /**
     * Time format.
     *
     * @var string
     * @since 1.0.0
     */
    private $datetime = 'Y-m-d H:i:s';

    /**
     * Weight.
     *
     * @var string
     * @since 1.0.0
     */
    private $weight = [];

    /**
     * Speed.
     *
     * @var string
     * @since 1.0.0
     */
    private $speed = [];

    /**
     * Length.
     *
     * @var string
     * @since 1.0.0
     */
    private $length = [];

    /**
     * Area.
     *
     * @var string
     * @since 1.0.0
     */
    private $area = [];

    /**
     * Volume.
     *
     * @var string
     * @since 1.0.0
     */
    private $volume = [];

    /**
     * Constructor.
     *
     * @param L11nManager $l11nManager Localization manager
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCountry(string $country)
    {
        if (!ISO3166TwoEnum::isValidValue($country)) {
            throw new InvalidEnumValue($country);
        }

        $this->country = $country;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTimezone() : string
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     *
     * @todo   : maybe make parameter int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setTimezone(string $timezone)
    {
        if (!TimeZoneEnumArray::isValidValue($timezone)) {
            throw new InvalidEnumValue($timezone);
        }

        $this->timezone = $timezone;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return void
     *
     * @throws InvalidEnumValue
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLanguage(string $language)
    {
        if (!ISO639x1Enum::isValidValue($language)) {
            throw new InvalidEnumValue($language);
        }

        $this->language = $language;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCurrency() : string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCurrency(string $currency)
    {
        if (!ISO4217Enum::isValidValue($currency)) {
            throw new InvalidEnumValue($currency);
        }

        $this->currency = $currency;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDatetime() : string
    {
        return $this->datetime;
    }

    /**
     * @param string $datetime
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDatetime(string $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDecimal() : string
    {
        return $this->decimal;
    }

    /**
     * @param string $decimal
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDecimal(string $decimal)
    {
        $this->decimal = $decimal;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getThousands() : string
    {
        return $this->thousands;
    }

    /**
     * @param string $thousands
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setThousands(string $thousands)
    {
        $this->thousands = $thousands;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAngle() : string
    {
        return $this->angle;
    }

    /**
     * @param string $angle
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setAngle(string $angle)
    {
        $this->temperature = $angle;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTemperature() : string
    {
        return $this->temperature;
    }

    /**
     * @param string $temperature
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setTemperatur(string $temperature)
    {
        $this->temperature = $temperature;
    }
}
