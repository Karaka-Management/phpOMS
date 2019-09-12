<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\PathFinding
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

/**
 * Path finder interface
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface PathFinderInterface {
    public static function findPath(
        int $startX, int $startY,
        int $endX, int $endY,
        Grid $grid,
        int $heuristic, int $movement
    ) : Path;
}
