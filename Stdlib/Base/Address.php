<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
class Address extends Location
{
    /**
     * Model id.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Name of the receiver.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Sub of the address.
     *
     * @var string
     * @since 1.0.0
     */
    public string $fao = '';

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
        return \array_merge(
            parent::toArray(),
            [
                'id'   => $this->id,
                'name' => $this->name,
                'fao'  => $this->fao,
            ]
        );
    }

    /**
     * Create object from array
     *
     * @param array{id:int, name:string, fao:string, type:int, postal:string, city:string, country:string, address:string, state:string, lat:float, lon:float} $address Address data
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function fromJson(array $address) : self
    {
        $new = new self();
        $new->from($address);

        $new->id   = $address['id'];
        $new->name = $address['name'];
        $new->fao  = $address['fao'];

        return $new;
    }
}
