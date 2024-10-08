<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

use phpOMS\Contract\SerializableInterface;
use phpOMS\Localization\ISO3166TwoEnum;

/**
 * Location class.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Location implements \JsonSerializable, SerializableInterface
{
    /**
     * Location id
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Zip or postal.
     *
     * @var string
     * @since 1.0.0
     */
    public string $postal = '';

    /**
     * Name of city.
     *
     * @var string
     * @since 1.0.0
     */
    public string $city = '';

    /**
     * Name of the country.
     *
     * @var string
     * @since 1.0.0
     */
    public string $country = ISO3166TwoEnum::_XXX;

    /**
     * Street & district.
     *
     * @var string
     * @since 1.0.0
     */
    public string $address = '';

    /**
     * Address addition.
     *
     * e.g. 2nd floor
     *
     * @var string
     * @since 1.0.0
     */
    public string $addressAddition = '';

    /**
     * Address type
     *
     * @var int
     * @since 1.0.0
     */
    public int $type = AddressType::HOME;

    /**
     * State.
     *
     * @var string
     * @since 1.0.0
     */
    public string $state = '';

    public float $lat = 0.0;

    public float $lon = 0.0;

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
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return (string) \json_encode($this->jsonSerialize());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
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
            'lat'     => $this->lat,
            'lon'     => $this->lon,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize(mixed $serialized) : void
    {
        if (!\is_string($serialized)) {
            return;
        }

        $data = \json_decode($serialized, true);

        if (!\is_array($data)) {
            return;
        }

        $this->postal  = $data['postal'];
        $this->city    = $data['city'];
        $this->country = $data['country'];
        $this->address = $data['address'];
        $this->state   = $data['state'];
        $this->lat     = $data['lat'];
        $this->lon     = $data['lon'];
    }
}
