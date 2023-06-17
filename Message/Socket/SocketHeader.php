<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Socket;

use phpOMS\Contract\SerializableInterface;
use phpOMS\Message\HeaderAbstract;

/**
 * Server class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @package phpOMS\Message\Socket
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class SocketHeader extends HeaderAbstract implements SerializableInterface
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

    /**
     * Header.
     *
     * @var string[][]
     * @since 1.0.0
     */
    private array $header = [];

    /**
     * Get the sender
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getSendFrom()
    {
        return $this->sendFrom;
    }

    /**
     * Set sender
     *
     * @param mixed $sendFrom Sender
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSendFrom($sendFrom) : void
    {
        $this->sendFrom = $sendFrom;
    }

    /**
     * Get receiver
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getSendTo()
    {
        return $this->sendTo;
    }

    /**
     * Set receiver
     *
     * @param mixed $sendTo Receiver
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSendTo($sendTo) : void
    {
        $this->sendTo = $sendTo;
    }

    /**
     * @return int
     *
     * @since 1.0.0
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set header length
     *
     * @param int $length Header length
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLength($length) : void
    {
        $this->length = $length;
    }

    /**
     * Get package type
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
     * Set package type
     *
     * @param int $type Type
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
     * Get subtype
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSubtype() : int
    {
        return $this->subtype;
    }

    /**
     * Set subtype
     *
     * @param int $subtype Subtype
     *
     * @return void
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function serialize() : string
    {
        return '';
    }

    /**
     * Jsonfy object.
     *
     * @return string Json serialization
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function unserialize(mixed $string) : void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion() : string
    {
        return 'Socket/1.1';
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, string $header, bool $overwrite = false) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key = null) : array
    {
        return $key === null ? $this->header : ($this->header[\strtolower($key)] ?? []);
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key) : bool
    {
        return isset($this->header[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(int $code) : void
    {
    }
}
