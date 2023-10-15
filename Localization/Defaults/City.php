<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * City class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class City
{
    /**
     * City id.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Country code.
     *
     * @var string
     * @since 1.0.0
     */
    public string $countryCode = '';

    /**
     * State code.
     *
     * @var string
     * @since 1.0.0
     */
    public string $state = '';

    /**
     * City name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Postal code.
     *
     * @var int
     * @since 1.0.0
     */
    public int $postal = 0;

    /**
     * Latitude.
     *
     * @var float
     * @since 1.0.0
     */
    public float $lat = 0.0;

    /**
     * Longitude.
     *
     * @var float
     * @since 1.0.0
     */
    public float $long = 0.0;

    /**
     * Get city name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get country code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCountryCode() : string
    {
        return $this->countryCode;
    }

    /**
     * Get city state
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getState() : string
    {
        return $this->state;
    }

    /**
     * Get city postal
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getPostal() : int
    {
        return $this->postal;
    }

    /**
     * Get city latitude
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getLat() : float
    {
        return $this->lat;
    }

    /**
     * Get city longitude
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getLong() : float
    {
        return $this->long;
    }
}
