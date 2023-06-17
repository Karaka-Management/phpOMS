<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\PathFinding
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

use phpOMS\Stdlib\Base\Enum;

/**
 * Heuristic type enum.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class HeuristicType extends Enum
{
    public const MANHATTAN = 1;

    public const EUCLIDEAN = 2;

    public const OCTILE = 4;

    public const CHEBYSHEV = 8;

    public const MINKOWSKI = 16;

    public const CANBERRA = 32;

    public const BRAY_CURTIS = 64;
}
