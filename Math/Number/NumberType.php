<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Number
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

use phpOMS\Stdlib\Base\Enum;

/**
 * Number type enum.
 *
 * @package phpOMS\Math\Number
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class NumberType extends Enum
{
    public const N_INTEGER = 0;

    public const N_NATURAL = 1;

    public const N_EVEN = 2;

    public const N_UNEVEN = 4;

    public const N_PRIME = 8;

    public const N_REAL = 16;

    public const N_RATIONAL = 32;

    public const N_IRRATIONAL = 64;

    public const N_COMPLEX = 128;
}
