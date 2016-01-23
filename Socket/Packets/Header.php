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
namespace phpOMS\Socket\Packets;

/**
 * Server class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Header implements \Serializable
{
    private $sendFrom = null;

    private $sendTo = null;

    /**
     * Packet size.
     *
     * @var int
     * @since 1.0.0
     */
    private $length = 0;

    /**
     * Packet type.
     *
     * @var \phpOMS\Socket\Packets\PacketType
     * @since 1.0.0
     */
    private $type = 0;

    /**
     * Packet subtype.
     *
     * @var int
     * @since 1.0.0
     */
    private $subtype = 0;

    public function getSendFrom()
    {
        return $this->sendFrom;
    }

    public function setSendFrom($sendFrom)
    {
        $this->sendFrom = $sendFrom;
    }

    public function getSendTo()
    {
        return $this->sendTo;
    }

    public function setSendTo($sendTo)
    {
        $this->sendTo = $sendTo;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * @param int $subtype
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    }

    /**
     * Serializing header.
     *
     * @return string Json serialization
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serialize()
    {
        return $this->__toString();
    }

    /**
     * Jsonfy object.
     *
     * @return string Json serialization
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __toString()
    {
        return '';
    }

    /**
     * Unserializing json string.
     *
     * @param string $string String to unserialize
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function unserialize($string)
    {
    }
}
