<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Localization
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\Utils\Converter\AngleType;
use phpOMS\Utils\Converter\TemperatureType;

/**
 * Localization class.
 *
 * @package    phpOMS\Localization
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Localization
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
     * @var array
     * @since 1.0.0
     */
    private $weight = [];

    /**
     * Speed.
     *
     * @var array
     * @since 1.0.0
     */
    private $speed = [];

    /**
     * Length.
     *
     * @var array
     * @since 1.0.0
     */
    private $length = [];

    /**
     * Area.
     *
     * @var array
     * @since 1.0.0
     */
    private $area = [];

    /**
     * Volume.
     *
     * @var array
     * @since 1.0.0
     */
    private $volume = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Get country
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * Set country name
     *
     * @param string $country Contry name
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setCountry(string $country) : void
    {
        if (!ISO3166TwoEnum::isValidValue($country)) {
            throw new InvalidEnumValue($country);
        }

        $this->country = $country;
    }

    /**
     * Get timezone
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getTimezone() : string
    {
        return $this->timezone;
    }

    /**
     * Set timezone
     *
     * @param string $timezone Timezone
     *
     * @return void
     *
     * @todo   : maybe make parameter int
     *
     * @since  1.0.0
     */
    public function setTimezone(string $timezone) : void
    {
        if (!TimeZoneEnumArray::isValidValue($timezone)) {
            throw new InvalidEnumValue($timezone);
        }

        $this->timezone = $timezone;
    }

    /**
     * Get language
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * Set language code
     *
     * @param string $language Language code
     *
     * @return void
     *
     * @throws InvalidEnumValue
     *
     * @since  1.0.0
     */
    public function setLanguage(string $language) : void
    {
        $language = strtolower($language);

        if (!ISO639x1Enum::isValidValue($language)) {
            throw new InvalidEnumValue($language);
        }

        $this->language = $language;
    }

    /**
     * Get currency
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getCurrency() : string
    {
        return $this->currency;
    }

    /**
     * Set currency code
     *
     * @param string $currency Currency code
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setCurrency(string $currency) : void
    {
        if (!ISO4217Enum::isValidValue($currency)) {
            throw new InvalidEnumValue($currency);
        }

        $this->currency = $currency;
    }

    /**
     * get datetime format
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getDatetime() : string
    {
        return $this->datetime;
    }

    /**
     * Set datetime format
     *
     * @param string $datetime Datetime format
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setDatetime(string $datetime) : void
    {
        $this->datetime = $datetime;
    }

    /**
     * Set decimal char
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getDecimal() : string
    {
        return $this->decimal;
    }

    /**
     * Get decimal char
     *
     * @param string $decimal Decimal char
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setDecimal(string $decimal) : void
    {
        $this->decimal = $decimal;
    }

    /**
     * Get thousands char
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getThousands() : string
    {
        return $this->thousands;
    }

    /**
     * Set thousands char
     *
     * @param string $thousands Thousands char
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setThousands(string $thousands) : void
    {
        $this->thousands = $thousands;
    }

    /**
     * Get angle type
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getAngle() : string
    {
        return $this->angle;
    }

    /**
     * Set angle type
     *
     * @param string $angle Angle
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setAngle(string $angle) : void
    {
        $this->angle = $angle;
    }

    /**
     * Get temperature type
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getTemperature() : string
    {
        return $this->temperature;
    }

    /**
     * Set temperature string
     *
     * @param string $temperature Temperature
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setTemperature(string $temperature) : void
    {
        $this->temperature = $temperature;
    }

    /**
     * Get speed type
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getSpeed() : array
    {
        return $this->speed;
    }

    /**
     * Set speed type
     *
     * @param array $speed Speed
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setSpeed(array $speed) : void
    {
        $this->speed = $speed;
    }

    /**
     * Get weight type
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getWeight() : array
    {
        return $this->weight;
    }

    /**
     * Set weight type
     *
     * @param array $weight Weight type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setWeight(array $weight) : void
    {
        $this->weight = $weight;
    }

    /**
     * Get length type
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getLength() : array
    {
        return $this->length;
    }

    /**
     * Set length type
     *
     * @param array $length Length type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setLength(array $length) : void
    {
        $this->length = $length;
    }

    /**
     * Get area type
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getArea() : array
    {
        return $this->area;
    }

    /**
     * Set area type
     *
     * @param array $area Area type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setArea(array $area) : void
    {
        $this->area = $area;
    }

    /**
     * Get volume type
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getVolume() : array
    {
        return $this->volume;
    }

    /**
     * Set volume type
     *
     * @param array $volume Volume type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setVolume(array $volume) : void
    {
        $this->volume = $volume;
    }
}
