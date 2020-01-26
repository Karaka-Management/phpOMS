<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Contract\ArrayableInterface;

/**
 * Reload class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Reload implements \Serializable, ArrayableInterface, \JsonSerializable
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
    public function unserialize($raw) : void
    {
        $unserialized = \json_decode($raw, true);

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
     * Generate message json.
     *
     * @return array<string, mixed>
     *
     * @since 1.0.0
     */
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
