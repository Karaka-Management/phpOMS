<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Contract
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Contract;

/**
 * Make a class Serializable.
 *
 * This is primarily used for classes that provide formatted output or output,
 * that get rendered.
 *
 * @package phpOMS\Contract
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface SerializableInterface
{
    /**
     * Serialize object
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function serialize() : string;

    /**
     * Fill object with data
     *
     * @param null|int|float|string|bool $data Date to unserialize
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function unserialize(mixed $data) : void;
}
