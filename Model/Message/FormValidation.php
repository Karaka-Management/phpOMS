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
 * FormValidation class.
 *
 * @package    phpOMS\Model\Message
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class FormValidation implements \Serializable, ArrayableInterface, \JsonSerializable
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
    private $validation = [];

    /**
     * Constructor.
     *
     * @param array $validation Invalid data
     *
     * @since  1.0.0
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

        $this->validation = $unserialized['validation'] ?? [];
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
            'type'       => self::TYPE,
            'validation' => $this->validation,
        ];
    }
}
