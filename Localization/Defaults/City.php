<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

/**
 * City class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class City
{
    /**
     * City id.
     *
     * @var int
     * @since 1.0.0
     */
    private int $id = 0;

    /**
     * Country code.
     *
     * @var string
     * @since 1.0.0
     */
    private string $countryCode = '';

    /**
     * State code.
     *
     * @var string
     * @since 1.0.0
     */
    private string $state = '';

    /**
     * City name.
     *
     * @var string
     * @since 1.0.0
     */
    private string $name = '';

    /**
     * Postal code.
     *
     * @var int
     * @since 1.0.0
     */
    private int $postal = 0;

    /**
     * Latitude.
     *
     * @var float
     * @since 1.0.0
     */
    private float $lat = 0.0;

    /**
     * Longitude.
     *
     * @var float
     * @since 1.0.0
     */
    private float $long = 0.0;

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
