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
 * Movement type enum.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class MovementType extends Enum
{
    public const DIAGONAL = 1;

    public const STRAIGHT = 2;

    public const DIAGONAL_ONE_OBSTACLE = 4;

    public const DIAGONAL_NO_OBSTACLE = 8;
}
