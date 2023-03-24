<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Map
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Map;

use phpOMS\Stdlib\Base\Enum;

/**
 * Muli map order type enum.
 *
 * Specifies if keys in the map can be ordered in any way or need to match the exact order.
 *
 * @package phpOMS\Stdlib\Map
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class OrderType extends Enum
{
    public const LOOSE = 0; // Doesn't matter in which order the keys are ordered

    public const STRICT = 1; // The exact key order matters for setting/getting values
}
