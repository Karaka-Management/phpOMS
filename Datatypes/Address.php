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
 * Address class.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Address implements \JsonSerializable
{

    /**
     * Name of the receiver.
     *
     * @var string
     * @since 1.0.0
     */
    private $recipient = '';

    /**
     * Sub of the address.
     *
     * @var string
     * @since 1.0.0
     */
    private $fao = '';

    /**
     * Location.
     *
     * @var Location
     * @since 1.0.0
     */
    private $location = null;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->location = new Location();
    }

    /**
     * Get recipient.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getRecipient() : string
    {
        return $this->recipient;
    }

    /**
     * Set recipient.
     *
     * @param string $recipient Recipient
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setRecipient(string $recipient) /* : void */
    {
        $this->recipient = $recipient;
    }

    /**
     * Get FAO.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getFAO() : string
    {
        return $this->fao;
    }

    /**
     * Set FAO.
     *
     * @param string $fao FAO
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setFAO(string $fao) /* : void */
    {
        $this->fao = $fao;
    }

    /**
     * Get location.
     *
     * @return Location
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLocation() : Location
    {
        return $this->location;
    }

    /**
     * Set location.
     *
     * @param Location $location Location
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLocation(Location $location) /* : void */
    {
        $this->location = $location;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(int $option = 0)
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return ['recipient' => $this->recipient, 'fao' => $this->fao, 'location' => $this->location->toArray()];
    }
}
