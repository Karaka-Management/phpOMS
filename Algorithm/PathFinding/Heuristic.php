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

use phpOMS\Math\Topology\Metrics2D;

/**
 * Node on grid.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Heuristic
{
    public static function heuristic(array $node1, array $node2, int $heuristic) : float
    {
        if ($heuristic === HeuristicType::MANHATTAN) {
            return Metrics2D::manhattan($node1, $node2);
        } elseif ($heuristic === HeuristicType::EUCLIDEAN) {
            return Metrics2D::euclidean($node1, $node2);
        } elseif ($heuristic === HeuristicType::OCTILE) {
            return Metrics2D::octile($node1, $node2);
        } elseif ($heuristic === HeuristicType::MINKOWSKI) {
            return Metrics2D::minkowski($node1, $node2, 1);
        } elseif ($heuristic === HeuristicType::CANBERRA) {
            return Metrics2D::canberra($node1, $node2);
        } elseif ($heuristic === HeuristicType::BRAY_CURTIS) {
            return Metrics2D::brayCurtis($node1, $node2);
        }

        return Metrics2D::chebyshev($node1, $node2);
    }
}
