<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Socket\Packets
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Socket\Packets;

/**
 * Server class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @package    phpOMS\Socket\Packets
 * @license    OMS License 1.0
 * @link       https://orange-management.org
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
     * @var int
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

    public function setSendFrom($sendFrom) : void
    {
        $this->sendFrom = $sendFrom;
    }

    public function getSendTo()
    {
        return $this->sendTo;
    }

    public function setSendTo($sendTo) : void
    {
        $this->sendTo = $sendTo;
    }

    /**
     * @return int
     *
     * @since  1.0.0
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
     */
    public function setLength($length) : void
    {
        $this->length = $length;
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

    /**
     * @param int $type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getSubtype() : int
    {
        return $this->subtype;
    }

    /**
     * @param int $subtype
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setSubtype($subtype) : void
    {
        $this->subtype = $subtype;
    }

    /**
     * Serializing header.
     *
     * @return string Json serialization
     *
     * @since  1.0.0
     */
    public function serialize() : string
    {
        return $this->__toString();
    }

    /**
     * Jsonfy object.
     *
     * @return string Json serialization
     *
     * @since  1.0.0
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
     */
    public function unserialize($string) : void
    {
    }
}
