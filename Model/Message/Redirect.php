<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Contract\SerializableInterface;

/**
 * Redirect class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Redirect implements \JsonSerializable, SerializableInterface
{
    /**
     * Message type.
     *
     * @var string
     * @since 1.0.0
     */
    public const TYPE = 'redirect';

    /**
     * Redirect uri.
     *
     * @var string
     * @since 1.0.0
     */
    private string $uri = '';

    /**
     * Delay.
     *
     * @var int
     * @since 1.0.0
     */
    private int $delay = 0;

    /**
     * Window.
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $new = false;

    /**
     * Constructor.
     *
     * @param string $url   Url
     * @param bool   $blank New window
     *
     * @since 1.0.0
     */
    public function __construct(string $url = '', bool $blank = false)
    {
        $this->uri = $url;
        $this->new = $blank;
    }

    /**
     * Set delay.
     *
     * @param int $delay Delay in ms
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDelay(int $delay) : void
    {
        $this->delay = $delay;
    }

    /**
     * Set uri.
     *
     * @param string $uri Uri
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setUri(string $uri) : void
    {
        $this->uri = $uri;
    }

    /**
     * Render message.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function serialize() : string
    {
        return $this->__toString();
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
    public function unserialize(mixed $raw) : void
    {
        if (!\is_string($raw)) {
            return;
        }

        $unserialized = \json_decode($raw, true);
        if (!\is_array($unserialized)) {
            return;
        }

        $this->delay = $unserialized['time'] ?? 0;
        $this->uri   = $unserialized['uri'] ?? '';
        $this->new   = $unserialized['new'] ?? false;
    }

    /**
     * Stringify.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return (string) \json_encode($this->toArray());
    }

    /**
     * Generate message array.
     *
     * @return array<string, mixed>
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        return [
            'type' => self::TYPE,
            'time' => $this->delay,
            'uri'  => $this->uri,
            'new'  => $this->new,
        ];
    }
}
