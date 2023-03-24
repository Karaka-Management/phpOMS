<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Address class.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Address implements \JsonSerializable
{
    /**
     * Name of the receiver.
     *
     * @var string
     * @since 1.0.0
     */
    public string $recipient = '';

    /**
     * Sub of the address.
     *
     * @var string
     * @since 1.0.0
     */
    public string $fao = '';

    /**
     * Location.
     *
     * @var Location
     * @since 1.0.0
     */
    public Location $location;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->location = new Location();
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
    public function toArray() : array
    {
        return [
            'recipient' => $this->recipient,
            'fao'       => $this->fao,
            'location'  => $this->location->toArray(),
        ];
    }
}
