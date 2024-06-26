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
 * Reload class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Reload implements \JsonSerializable, SerializableInterface
{
    /**
     * Message type.
     *
     * @var string
     * @since 1.0.0
     */
    public const TYPE = 'reload';

    /**
     * Delay in ms.
     *
     * @var int
     * @since 1.0.0
     */
    private int $delay = 0;

    /**
     * Constructor.
     *
     * @param int $delay Delay in ms
     *
     * @since 1.0.0
     */
    public function __construct(int $delay = 0)
    {
        $this->delay = $delay;
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
