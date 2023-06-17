<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Numerics\Interpolation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Numerics\Interpolation;

use phpOMS\Stdlib\Base\Enum;

/**
 * Derivative type enum.
 *
 * @package phpOMS\Math\Numerics\Interpolation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DerivativeType extends Enum
{
    public const FIRST = 1;

    public const SECOND = 2;
}
