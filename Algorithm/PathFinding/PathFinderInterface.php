<?php
/**
 * Karaka
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

/**
 * Path finder interface
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface PathFinderInterface
{
    /**
     * Find path from one point to another
     *
     * @param int  $startX    Start point X-Coordinate
     * @param int  $startY    Start point Y-Coordinate
     * @param int  $endX      End point X-Coordinate
     * @param int  $endY      End point Y-Coordinate
     * @param Grid $grid      Grid with the walkable points
     * @param int  $heuristic Heuristic algorithm to use in order to calculate the distance for a good path
     * @param int  $movement  Allowed movement (e.g. straight, diagonal, ...)
     *
     * @return Path
     *
     * @since 1.0.0
     */
    public static function findPath(
        int $startX, int $startY,
        int $endX, int $endY,
        Grid $grid,
        int $heuristic, int $movement
    ) : Path;
}
