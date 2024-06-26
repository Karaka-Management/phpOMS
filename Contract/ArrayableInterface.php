<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Contract
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Contract;

/**
 * This interface forces classes to implement an array representation of themselves.
 *
 * This can be helpful for \JsonSerializable classes or classes which need to be represented as array.
 *
 * @package phpOMS\Contract
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface ArrayableInterface
{
    /**
     * Get the instance as an array.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray() : array;
}
