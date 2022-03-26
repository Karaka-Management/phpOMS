<?php
/**
 * Karaka
 *
 * PHP Version 8.0
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
    public function serialize() : string;
    public function unserialize($data) : void;
}
