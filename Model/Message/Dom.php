<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Contract\ArrayableInterface;

/**
 * Dom class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Dom implements ArrayableInterface
{
    /**
     * Message type.
     *
     * @var string
     * @since 1.0.0
     */
    public const TYPE = 'dom';

    /**
     * Selector string.
     *
     * @var string
     * @since 1.0.0
     */
    private string $selector = '';

    /**
     * Dom content.
     *
     * @var string
     * @since 1.0.0
     */
    private string $content = '';

    /**
     * Dom action.
     *
     * @var int
     * @since 1.0.0
     */
    private int $action = DomAction::MODIFY;

    /**
     * Delay in ms.
     *
     * @var int
     * @since 1.0.0
     */
    private int $delay = 0;

    /**
     * Set DOM content
     *
     * @param string $content DOM Content
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setContent(string $content) : void
    {
        $this->content = $content;
    }

    /**
     * Set selector.
     *
     * @param string $selector Selector
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSelector(string $selector) : void
    {
        $this->selector = $selector;
    }

    /**
     * Set action.
     *
     * @param int $action action
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setAction(int $action) : void
    {
        $this->action = $action;
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
     * @return array
     *
     * @since 1.0.0
     */
    public function __serialize() : array
    {
        return $this->toArray();
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
    public function __unserialize($raw) : void
    {
        $unserialized = \json_decode($raw, true);

        $this->delay    = $unserialized['time'] ?? 0;
        $this->selector = $unserialized['selector'] ?? '';
        $this->action   = $unserialized['action'] ?? '';
        $this->content  = $unserialized['content'] ?? DomAction::MODIFY;
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
            'type'     => self::TYPE,
            'time'     => $this->delay,
            'selector' => $this->selector,
            'action'   => $this->action,
            'content'  => $this->content,
        ];
    }
}
