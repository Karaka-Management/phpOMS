<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
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
abstract class PacketAbstract implements \Serializable
{

    /**
     * Packet header.
     *
     * @var Header
     * @since 1.0.0
     */
    private $header = null;

    /**
     * Stringify packet.
     *
     * This is using a json format
     *
     * @return string
     *
     * @since 1.0.0
     */
    abstract public function __toString();

    /**
     * Stringify packet.
     *
     * This is using a json format
     *
     * @return string Json string
     *
     * @since 1.0.0
     */
    abstract public function serialize();

    /**
     * Unserialize packet.
     *
     * This is using a json format
     *
     * @param string $string Json string
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function unserialize($string) : void;

    /**
     * Get packet header.
     *
     * @return Header
     *
     * @since 1.0.0
     */
    public function getHeader() : Header
    {
        return $this->header;
    }

    /**
     * Set packet header.
     *
     * @param Header $header Header
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setHeader(Header $header) : void
    {
        $this->header = $header;
    }
}
