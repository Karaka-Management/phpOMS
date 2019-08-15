<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Model\Message
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Contract\ArrayableInterface;

/**
 * Notify class.
 *
 * @package    phpOMS\Model\Message
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Notify implements \Serializable, ArrayableInterface, \JsonSerializable
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
    private string $title = '';

    /**
     * Message.
     *
     * @var string
     * @since 1.0.0
     */
    private string $message = '';

    /**
     * Delay in ms.
     *
     * @var int
     * @since 1.0.0
     */
    private int $delay = 0;

    /**
     * Stay in ms.
     *
     * @var int
     * @since 1.0.0
     */
    private int $stay = 0;

    /**
     * Level or type.
     *
     * @var int
     * @since 1.0.0
     */
    private int $level = NotifyType::INFO;

    /**
     * Constructor.
     *
     * @param string $msg   Message
     * @param int    $level Message level
     *
     * @since  1.0.0
     */
    public function __construct(string $msg = '', int $level = NotifyType::INFO)
    {
        $this->message = $msg;
        $this->level   = $level;
    }

    /**
     * Set delay.
     *
     * @param int $delay Delay in ms
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setDelay(int $delay) : void
    {
        $this->delay = $delay;
    }

    /**
     * Set delay.
     *
     * @param int $stay Stay in ms
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setStay(int $stay) : void
    {
        $this->stay = $stay;
    }

    /**
     * Set title.
     *
     * @param string $title Title
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    /**
     * Set message.
     *
     * @param string $message Message
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setMessage(string $message) : void
    {
        $this->message = $message;
    }

    /**
     * Set level/type.
     *
     * @param int $level Notification type/level
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setLevel(int $level) : void
    {
        $this->level = $level;
    }

    /**
     * Render message.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function serialize() : string
    {
        return $this->__toString();
    }

    /**
     * Render message.
     *
     * @return array<string, mixed>
     *
     * @since  1.0.0
     */
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($raw) : void
    {
        $unserialized = \json_decode($raw, true);

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
     * @since  1.0.0
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
     * @since  1.0.0
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
