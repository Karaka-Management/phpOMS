<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Contract
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
 * @license OMS License 1.0
 * @link    https://karaka.app
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
