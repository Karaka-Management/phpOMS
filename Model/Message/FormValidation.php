<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Contract\SerializableInterface;

/**
 * FormValidation class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class FormValidation implements \JsonSerializable, SerializableInterface
{
    /**
     * Message type.
     *
     * @var string
     * @since 1.0.0
     */
    public const TYPE = 'validation';

    /**
     * Form validation result.
     *
     * @var array
     * @since 1.0.0
     */
    private array $validation = [];

    /**
     * Constructor.
     *
     * @param array $validation Invalid data
     *
     * @since 1.0.0
     */
    public function __construct(array $validation = [])
    {
        $this->validation = $validation;
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

        $this->validation = $unserialized['validation'] ?? [];
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
            'type'       => self::TYPE,
            'validation' => $this->validation,
        ];
    }
}
