<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * Multi map key type enum.
 *
 * These keys specify how the multi map works.
 *
 * @package phpOMS\Stdlib\Map
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class KeyType extends Enum
{
    public const SINGLE = 0; // There can only be one key(-combination) per value

    public const MULTIPLE = 1; // There can be multiple keys per value
}
