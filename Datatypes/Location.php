<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Datatypes;

/**
 * Location class.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Location implements \JsonSerializable, \Serializable
{

    /**
     * Location id
     *
     * @var int
     * @since 1.0.0
     */
    private $id = 0;

    /**
     * Zip or postal.
     *
     * @var string
     * @since 1.0.0
     */
    private $postal = '';

    /**
     * Name of city.
     *
     * @var string
     * @since 1.0.0
     */
    private $city = '';

    /**
     * Name of the country.
     *
     * @var string
     * @since 1.0.0
     */
    private $country = '';

    /**
     * Street & district.
     *
     * @var string
     * @since 1.0.0
     */
    private $address = '';

    /**
     * Address type
     *
     * @var int
     * @since 1.0.0
     */
    private $type = AddressType::HOME;

    /**
     * State.
     *
     * @var string
     * @since 1.0.0
     */
    private $state = '';

    /**
     * Geo coordinates.
     *
     * @var float[]
     * @since 1.0.0
     */
    private $geo = ['lat' => 0, 'long' => 0];

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    public function setType(int $type) /* : void */
    {
        $this->type = $type;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     */
    public function getPostal() : string
    {
        return $this->postal;
    }

    /**
     * @param string $postal
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setPostal(string $postal) /* : void */
    {
        $this->postal = $postal;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     */
    public function getCity() : string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setCity(string $city) /* : void */
    {
        $this->city = $city;
    }

    /**
     * @return string
     *
     * @since  1.0.0
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
     */
    public function setCountry(string $country) /* : void */
    {
        $this->country = $country;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setAddress(string $address) /* : void */
    {
        $this->address = $address;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     */
    public function getState() : string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setState(string $state) /* : void */
    {
        $this->state = $state;
    }

    /**
     * @return float[]
     *
     * @since  1.0.0
     */
    public function getGeo() : array
    {
        return $this->geo;
    }

    /**
     * @param float[] $geo
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setGeo(array $geo) /* : void */
    {
        $this->geo = $geo;
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return json_encode($this->jsonSerialize());
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
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
    }
}
