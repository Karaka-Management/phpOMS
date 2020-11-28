<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\Utils\Converter\AngleType;
use phpOMS\Utils\Converter\TemperatureType;

/**
 * Localization class.
 *
 * @package phpOMS\Localization
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Localization implements \JsonSerializable
{
    /**
     * Definition path.
     *
     * @var string
     * @since 1.0.0
     */
    private const DEFINITIONS_PATH = __DIR__ . '/../Localization/Defaults/Definitions/';

    /**
     * Country ID.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $country = ISO3166TwoEnum::_USA;

    /**
     * Timezone.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $timezone = 'America/New_York';

    /**
     * Language ISO code.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $language = ISO639x1Enum::_EN;

    /**
     * Currency.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $currency = ISO4217CharEnum::_USD;

    /**
     * Currency format.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $currencyFormat = '0';

    /**
     * Number format.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $decimal = '.';

    /**
     * Number format.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $thousands = ',';

    /**
     * Angle type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $angle = AngleType::DEGREE;

    /**
     * Temperature type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $temperature = TemperatureType::CELSIUS;

    /**
     * Precision.
     *
     * @var array<string, int>
     * @since 1.0.0
     */
    protected array $precision = [];

    /**
     * Time format.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $datetime = [];

    /**
     * Weight.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $weight = [];

    /**
     * Speed.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $speed = [];

    /**
     * Length.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $length = [];

    /**
     * Area.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $area = [];

    /**
     * Volume.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    protected array $volume = [];

    /**
     * Country id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Get id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Create localization from language code
     *
     * @param string $langCode    Language code
     * @param string $countryCode Country code
     *
     * @return Localization
     *
     * @since 1.0.0
     */
    public static function fromLanguage(string $langCode, string $countryCode = '*') : self
    {
        $l11n = new self();
        $l11n->loadFromLanguage($langCode, $countryCode);

        return $l11n;
    }

    /**
     * Create localization from json
     *
     * @param array $json Json serialization
     *
     * @return Localization
     *
     * @since 1.0.0
     */
    public static function fromJson(array $json) : self
    {
        $l11n = new self();
        $l11n->setCountry($json['country']);
        $l11n->setTimezone($json['timezone'] ?? 'America/New_York');
        $l11n->setLanguage($json['language']);
        $l11n->setCurrency(\is_string($json['currency']) ? $json['currency'] : ($json['currency']['code'] ?? ISO4217Enum::_USD));
        $l11n->setCurrencyFormat(isset($json['currencyformat']) && \is_string($json['currencyformat']) ? $json['currencyformat'] : ($json['currency']['format'] ?? '1'));
        $l11n->setDecimal($json['decimal']);
        $l11n->setThousands($json['thousand']);
        $l11n->setAngle($json['angle']);
        $l11n->setTemperature($json['temperature']);
        $l11n->setDatetime($json['datetime']);
        $l11n->setWeight($json['weight']);
        $l11n->setSpeed($json['speed']);
        $l11n->setLength($json['length']);
        $l11n->setArea($json['area']);
        $l11n->setVolume($json['volume']);
        $l11n->setPrecision($json['precision']);

        return $l11n;
    }

    /**
     * Load localization from language code
     *
     * @param string $langCode    Language code
     * @param string $countryCode Country code
     *
     * @return void
     *
     * @throws InvalidEnumValue This exception is thrown if the language is invalid
     *
     * @since 1.0.0
     */
    public function loadFromLanguage(string $langCode, string $countryCode = '*') : void
    {
        $langCode    = \strtolower($langCode);
        $countryCode = \strtoupper($countryCode);

        if ($countryCode !== '*'
            && !\is_file(self::DEFINITIONS_PATH . $langCode . '_' . $countryCode . '.json')
        ) {
            $countryCode = '';
        }

        $files = \glob(self::DEFINITIONS_PATH . $langCode . '_' . $countryCode . '*');
        if ($files === false) {
            $files = []; // @codeCoverageIgnore
        }

        foreach ($files as $file) {
            $fileContent = \file_get_contents($file);
            if ($fileContent === false) {
                break; // @codeCoverageIgnore
            }

            $this->importLocale(\json_decode($fileContent, true));

            return;
        }

        $fileContent = \file_get_contents(self::DEFINITIONS_PATH . 'en_US.json');
        if ($fileContent === false) {
            return; // @codeCoverageIgnore
        }

        $this->importLocale(\json_decode($fileContent, true));
    }

    /**
     * Load localization from locale
     *
     * @param array<string, mixed> $locale Locale data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function importLocale(array $locale) : void
    {
        $this->setLanguage($locale['language'] ?? 'en');
        $this->setCountry($locale['country'] ?? 'US');
        $this->setCurrency($locale['currency']['code'] ?? ISO4217Enum::_USD);
        $this->setThousands($locale['thousand'] ?? ',');
        $this->setDecimal($locale['decimal'] ?? '.');
        $this->setAngle($locale['angle'] ?? AngleType::DEGREE);
        $this->setTemperature($locale['temperature'] ?? TemperatureType::CELSIUS);
        $this->setWeight($locale['weight'] ?? []);
        $this->setSpeed($locale['speed'] ?? []);
        $this->setLength($locale['length'] ?? []);
        $this->setArea($locale['area'] ?? []);
        $this->setVolume($locale['volume'] ?? []);
        $this->setPrecision($locale['precision'] ?? []);
        $this->setTimezone($locale['timezone'] ?? 'America/New_York');
        $this->setDatetime($locale['datetime'] ?? []);
    }

    /**
     * Get country
     *
     * @return string
     *
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if the country is invalid
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if the timezone is invalid
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if the language is invalid
     *
     * @since 1.0.0
     */
    public function setLanguage(string $language) : void
    {
        $language = \strtolower($language);

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
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if the currency is invalid
     *
     * @since 1.0.0
     */
    public function setCurrency(string $currency) : void
    {
        if (!ISO4217CharEnum::isValidValue($currency)) {
            throw new InvalidEnumValue($currency);
        }

        $this->currency = $currency;
    }

    /**
     * Get currency format
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCurrencyFormat() : string
    {
        return $this->currencyFormat;
    }

    /**
     * Set currency format
     *
     * @param string $format Currency format
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCurrencyFormat(string $format) : void
    {
        $this->currencyFormat = $format;
    }

    /**
     * get datetime format
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getDatetime() : array
    {
        return $this->datetime;
    }

    /**
     * Set datetime format
     *
     * @param array<string, string> $datetime Datetime format
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDatetime(array $datetime) : void
    {
        $this->datetime = $datetime;
    }

    /**
     * Set decimal char
     *
     * @return string
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if the angle is invalid
     *
     * @since 1.0.0
     */
    public function setAngle(string $angle) : void
    {
        if (!AngleType::isValidValue($angle)) {
            throw new InvalidEnumValue($angle);
        }

        $this->angle = $angle;
    }

    /**
     * Get temperature type
     *
     * @return string
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function setTemperature(string $temperature) : void
    {
        if (!TemperatureType::isValidValue($temperature)) {
            throw new InvalidEnumValue($temperature);
        }

        $this->temperature = $temperature;
    }

    /**
     * Get speed type
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getSpeed() : array
    {
        return $this->speed;
    }

    /**
     * Set speed type
     *
     * @param array<string, string> $speed Speed
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSpeed(array $speed) : void
    {
        $this->speed = $speed;
    }

    /**
     * Get weight type
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getWeight() : array
    {
        return $this->weight;
    }

    /**
     * Set weight type
     *
     * @param array<string, string> $weight Weight type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setWeight(array $weight) : void
    {
        $this->weight = $weight;
    }

    /**
     * Get length type
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getLength() : array
    {
        return $this->length;
    }

    /**
     * Set length type
     *
     * @param array<string, string> $length Length type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLength(array $length) : void
    {
        $this->length = $length;
    }

    /**
     * Get area type
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getArea() : array
    {
        return $this->area;
    }

    /**
     * Set area type
     *
     * @param array<string, string> $area Area type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setArea(array $area) : void
    {
        $this->area = $area;
    }

    /**
     * Get volume type
     *
     * @return array<string, string>
     *
     * @since 1.0.0
     */
    public function getVolume() : array
    {
        return $this->volume;
    }

    /**
     * Get precision type
     *
     * @return array<string, int>
     *
     * @since 1.0.0
     */
    public function getPrecision() : array
    {
        return $this->precision;
    }

    /**
     * Set precision type
     *
     * @param array<string, int> $precision Precision type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPrecision(array $precision) : void
    {
        $this->precision = $precision;
    }

    /**
     * Set volume type
     *
     * @param array<string, string> $volume Volume type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setVolume(array $volume) : void
    {
        $this->volume = $volume;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'             => $this->id,
            'country'        => $this->country,
            'timezone'       => $this->timezone,
            'language'       => $this->language,
            'currency'       => $this->currency,
            'currencyformat' => $this->currencyFormat,
            'decimal'        => $this->decimal,
            'thousand'       => $this->thousands,
            'angle'          => $this->angle,
            'temperature'    => $this->temperature,
            'datetime'       => $this->datetime,
            'weight'         => $this->weight,
            'speed'          => $this->speed,
            'length'         => $this->length,
            'area'           => $this->area,
            'volume'         => $this->volume,
            'precision'      => $this->precision,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
