<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Algorithm\PathFinding
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

/**
 * Node on grid.
 *
 * @package    phpOMS\Algorithm\PathFinding
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Heuristic
{
    public static function heuristic(Node $node1, Node $node2, int $heuristic) : float
    {
        if ($heuristic === HeuristicType::MANHATTAN) {
            return self::manhattan($node1, $node2);
        } elseif ($heuristic === HeuristicType::EUCLIDEAN) {
            return self::euclidean($node1, $node2);
        } elseif ($heuristic === HeuristicType::OCTILE) {
            return self::octile($node1, $node2);
        }

        return self::chebyshev($node1, $node2);
    }

    public static function manhattan(Node $node1, Node $node2) : float
    {
        return \abs($node1->getX() - $node2->getX()) + \abs($node1->getY() - $node2->getY());
    }

    public static function euclidean(Node $node1, Node $node2) : float
    {
        $dx = \abs($node1->getX() - $node2->getX());
        $dy = \abs($node1->getY() - $node2->getY());

        return \sqrt($dx * $dx + $dy * $dy);
    }

    public static function octile(Node $node1, Node $node2) : float
    {
        $dx = \abs($node1->getX() - $node2->getX());
        $dy = \abs($node1->getY() - $node2->getY());

        return $dx < $dy ? (\sqrt(2) - 1) * $dx + $dy : (\sqrt(2) - 1) * $dy + $dx;
    }

    public static function chebyshev(Node $node1, Node $node2) : float
    {
        return \max(
            \abs($node1->getX() - $node2->getX()),
            \abs($node1->getY() - $node2->getY())
        );
    }
}
