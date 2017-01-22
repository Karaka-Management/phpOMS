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
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Datatypes;

/**
 * Location class.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Location implements \JsonSerializable, \Serializable
{

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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setPostal(string $postal) /* : void */
    {
        $this->postal = $postal;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCity(string $city) /* : void */
    {
        $this->city = $city;
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
    public function setCountry(string $country) /* : void */
    {
        $this->country = $country;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setAddress(string $address) /* : void */
    {
        $this->address = $address;
    }

    /**
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setState(string $state) /* : void */
    {
        $this->state = $state;
    }

    /**
     * @return float[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
        return $this->jsonSerialize();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : string
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
