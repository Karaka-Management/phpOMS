<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Location class.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Location implements \JsonSerializable, \Serializable
{
    /**
     * Location id
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Zip or postal.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $postal = '';

    /**
     * Name of city.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $city = '';

    /**
     * Name of the country.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $country = '';

    /**
     * Street & district.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $address = '';

    /**
     * Address type
     *
     * @var int
     * @since 1.0.0
     */
    protected int $type = AddressType::HOME;

    /**
     * State.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $state = '';

    /**
     * Geo coordinates.
     *
     * @var float[]
     * @since 1.0.0
     */
    protected array $geo = ['lat' => 0, 'long' => 0];

    /**
     * Get location id
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
     * Get location type
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Set location type
     *
     * @param int $type Location type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * Get postal or zip code
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPostal() : string
    {
        return $this->postal;
    }

    /**
     * Set postal or zip code
     *
     * @param string $postal Postal code
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPostal(string $postal) : void
    {
        $this->postal = $postal;
    }

    /**
     * Get city name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCity() : string
    {
        return $this->city;
    }

    /**
     * Set city name
     *
     * @param string $city City name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCity(string $city) : void
    {
        $this->city = $city;
    }

    /**
     * Get country code
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
     * Set country code
     *
     * @param string $country Country name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCountry(string $country) : void
    {
        $this->country = $country;
    }

    /**
     * Get address
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param string $address Address
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setAddress(string $address) : void
    {
        $this->address = $address;
    }

    /**
     * Get state name
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
     * Set state name
     *
     * @param string $state State name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setState(string $state) : void
    {
        $this->state = $state;
    }

    /**
     * Get geo location
     *
     * @return float[]
     *
     * @since 1.0.0
     */
    public function getGeo() : array
    {
        return $this->geo;
    }

    /**
     * Set geo location
     *
     * @param float[] $geo Geo location lat/long
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setGeo(array $geo) : void
    {
        $this->geo = $geo;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return (string) \json_encode($this->jsonSerialize());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'postal'  => $this->postal,
            'city'    => $this->city,
            'country' => $this->country,
            'address' => $this->address,
            'state'   => $this->state,
            'geo'     => $this->geo,
        ];
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     * @param  string $serialized <p>
     *                            The string representation of the object.
     *                            </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized) : void
    {
    }
}
