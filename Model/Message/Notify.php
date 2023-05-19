<?php
/**
 * Karaka
 *
 * PHP Version 8.1
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
use phpOMS\Message\NotificationLevel;

/**
 * Notify class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Notify implements \JsonSerializable, SerializableInterface
{
    /**
     * Message type.
     *
     * @var string
     * @since 1.0.0
     */
    public const TYPE = 'notify';

    /**
     * Notification title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Message.
     *
     * @var string
     * @since 1.0.0
     */
    public string $message = '';

    /**
     * Delay in ms.
     *
     * @var int
     * @since 1.0.0
     */
    public int $delay = 0;

    /**
     * Stay in ms.
     *
     * @var int
     * @since 1.0.0
     */
    public int $stay = 0;

    /**
     * Level or type.
     *
     * @var string
     * @since 1.0.0
     */
    public string $level = NotificationLevel::INFO;

    /**
     * Constructor.
     *
     * @param string $msg   Message
     * @param string $level Message level
     *
     * @since 1.0.0
     */
    public function __construct(string $msg = '', string $level = NotificationLevel::INFO)
    {
        $this->message = $msg;
        $this->level   = $level;
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

        $this->delay   = $unserialized['time'] ?? 0;
        $this->stay    = $unserialized['stay'] ?? 0;
        $this->message = $unserialized['msg'] ?? '';
        $this->title   = $unserialized['title'] ?? '';
        $this->level   = $unserialized['level'] ?? 0;
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
            'type'  => self::TYPE,
            'time'  => $this->delay,
            'stay'  => $this->stay,
            'msg'   => $this->message,
            'title' => $this->title,
            'level' => $this->level,
        ];
    }
}
