<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Account
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

use phpOMS\Stdlib\Base\Enum;

/**
 * Heuristic type enum.
 *
 * @package    phpOMS\Account
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class HeuristicType extends Enum
{
    public const MANHATTAN = 1;
    public const EUCLIDEAN = 2;
    public const OCTILE    = 4;
    public const CHEBYSHEV = 8;
}
