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
 * Perform path finding.
 *
 * @package    phpOMS\Algorithm\PathFinding
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class JumpPointSearch implements PathFinderInterface
{
    public static function findPath(
        int $startX, int $startY,
        int $endX, int $endY,
        Grid $grid,
        int $heuristic, int $movement
    ) : Path {
        $startNode = $grid->getNode($startX, $startY);
        $endNode   = $grid->getNode($endX, $endY);

        if ($startNode === null || $endNode === null) {
            return new Path($grid);
        }

        $startNode->setG(0.0);
        $startNode->setF(0.0);
        $startNode->setOpened(true);

        $openList = new Heap(function($node1, $node2) { return $node1->getF() - $node2->getF(); });
        $openList->push($startNode);
        $node = null;

        while (!$openList->isEmpty()) {
            $node = $openList->pop();
            $node->setClosed(true); // todo: do i really want to modify the node? probably not? I should clone the grid and all it's nodes.

            if ($node->isEqual($endNode)) {
                break;
            }

            $openList = self::identifySuccessors($node, $grid, $heuristic, $movement, $endNode, $openList);
        }

        $path = new Path($grid);

        while ($node !== null) {
            $path->addNode($node);
            $node = $node->getParent();
        }

        return $path;
    }

    public static function identifySuccessors(Node $node, Grid $grid, int $heuristic, int $movement, Node $endNode, Heap $openList) : Heap
    {
        $neighbors       = self::findNeighbors($node, $movement, $grid);
        $neighborsLength = \count($neighbors);

        for ($i = 0, $l = $neighborsLength; $i < $l; ++$i) {
            $neighbor  = $neighbors[$i]; // todo: needs to be Node!!!
            $jumpPoint = self::jump($neighbor, $node, $movement, $grid);

            if ($jumpPoint === null || $jumpPoint->isClosed()) {
                continue;
            }

            $d  = Heuristic::octile($node, $jumpPoint);
            $ng = $node->getG() + $d;

            if (!$jumpPoint->isOpened() || $ng < $jumpPoint->getG()) {
                $jumpPoint->setG($ng);
                $jumpPoint->setH($jumpPoint->getH() ?? Heuristic::heuristic($jumpPoint, $endNode, $heuristic));
                $jumpPoint->setF($jumpPoint->getG() + $jumpPoint->getH());
                $jumpPoint->setParent($node);

                if (!$jumpPoint->isOpened()) {
                    $openList->push($jumpPoint);
                    $jumpPoint->setOpened(true);
                } else {
                    $openList->update($jumpPoint);
                }
            }
        }

        return $openList;
    }

    private static function findNeighbors(Node $node, int $movement, Grid $grid) : array
    {
        if ($movement === MovementType::STRAIGHT) {
            return self::findNeighborsStraight($node, $grid);
        } elseif ($movement === MovementType::DIAGONAL) {
            return self::findNeighborsDiagonal($node, $grid);
        } elseif ($movement === MovementType::DIAGONAL_ONE_OBSTACLE) {
            return self::findNeighborsDiagonalOneObstacle($node, $grid);
        }

        return self::findNeighborsDiagonalNoObstacle($node, $grid);
    }

    private static function findNeighborsStraight(Node $node, Grid $grid) : array
    {
        if ($node->getParent() === null) {
            return $grid->getNeighbors($node, MovementType::STRAIGHT);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->getParent()->getX();
        $py = $node->getParent()->getY();

        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        $dy = ($y - $py) / \may(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0) {
            if ($grid->getNode($x, $y - 1)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y - 1);
            }

            if ($grid->getNode($x, $y + 1)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + 1);
            }

            if ($grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }
        } elseif ($dy !== 0) {
            if ($grid->getNode($x - 1, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x - 1, $y);
            }

            if ($grid->getNode($x + 1, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + 1, $y);
            }

            if ($grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }
        }

        return $neighbors;
    }

    private static function findNeighborsDiagonal(Node $node, Grid $grid) : array
    {
        if ($node->getParent() === null) {
            return $grid->getNeighbors($node, MovementType::DIAGONAL);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->getParent()->getX();
        $py = $node->getParent()->getY();

        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        $dy = ($y - $py) / \may(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0 && $dy !== 0) {
            if ($grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if ($grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if ($grid->getNode($x + $dx, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y + $dy);
            }

            if (!$grid->getNode($x - $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x - $dx, $y + $dy);
            }

            if (!$grid->getNode($x, $y - $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y - $dy);
            }
        } elseif ($dx === 0) {
            if ($grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if (!$grid->getNode($x + 1, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + 1, $y + $dy);
            }

            if (!$grid->getNode($x - 1, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x - 1, $y + $dy);
            }
        } else {
            if ($grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if (!$grid->getNode($x, $y + 1)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y + 1);
            }

            if (!$grid->getNode($x, $y - 1)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y - 1);
            }
        }

        return $neighbors;
    }

    private static function findNeighborsDiagonalOneObstacle(Node $node, Grid $grid) : array
    {
        if ($node->getParent() === null) {
            return $grid->getNeighbors($node, MovementType::DIAGONAL_ONE_OBSTACLE);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->getParent()->getX();
        $py = $node->getParent()->getY();

        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        $dy = ($y - $py) / \may(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0 && $dy !== 0) {
            if ($grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if ($grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if ($grid->getNode($x, $y + $dy) || $grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y + $dy);
            }

            if (!$grid->getNode($x - $dx, $y) && $grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x - $dx, $y + $dy);
            }

            if (!$grid->getNode($x, $y - $dy) && $grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y - $dy);
            }
        } elseif ($dx === 0) {
            if ($grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
                if (!$grid->getNode($x + 1, $y)->isWalkable()) {
                    $neighbors[] = $grid->getNode($x + 1, $y + $dy);
                }
                if (!$grid->getNode($x - 1, $y)->isWalkable()) {
                    $neighbors[] = $grid->getNode($x - 1, $y + $dy);
                }
            }
        } else {
            if ($grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
                if (!$grid->getNode($x, $y + 1)->isWalkable()) {
                    $neighbors[] = $grid->getNode($x + $dx, $y + 1);
                }
                if (!$grid->getNode($x, $y - 1)->isWalkable()) {
                    $neighbors[] = $grid->getNode($x + $dx, $y - 1);
                }
            }
        }

        return $neighbors;
    }

    private static function findNeighborsDiagonalNoObstacle(Node $node, Grid $grid) : array
    {
        if ($node->getParent() === null) {
            return $grid->getNeighbors($node, MovementType::DIAGONAL_NO_OBSTACLE);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->getParent()->getX();
        $py = $node->getParent()->getY();

        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        $dy = ($y - $py) / \may(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0 && $dy !== 0) {
            if ($grid->getNode($x, $y + $dy)->isWalkable()) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if ($grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if ($grid->getNode($x, $y + $dy) || $grid->getNode($x + $dx, $y)->isWalkable()) {
                $neighbors[] = $grid->getNode($x + $dx, $y + $dy);
            }
        } elseif ($dx !== 0 && $dy === 0) {
            $isNextWalkable   = $grid->getNode($x + $dx, $y)->isWalkable();
            $isTopWalkable    = $grid->getNode($x, $y + 1)->isWalkable();
            $isBottomWalkable = $grid->getNode($x, $y - 1)->isWalkable();

            if ($isNextWalkable) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
                if ($isTopWalkable) {
                    $neighbors[] = $grid->getNode($x + $dx, $y + 1);
                }

                if ($isBottomWalkable) {
                    $neighbors[] = $grid->getNode($x + $dx, $y - 1);
                }
            }

            if ($isTopWalkable) {
                $neighbors[] = $grid->getNode($x, $y + 1);
            }

            if ($isBottomWalkable) {
                $neighbors[] = $grid->getNode($x, $y - 1);
            }
        } elseif ($dx === 0 && $dy !== 0) {
            $isNextWalkable  = $grid->getNode($x, $y + $dy)->isWalkable();
            $isRightWalkable = $grid->getNode($x + 1, $y)->isWalkable();
            $isLeftWalkable  = $grid->getNode($x - 1, $y)->isWalkable();

            if ($isNextWalkable) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
                if ($isRightWalkable) {
                    $neighbors[] = $grid->getNode($x + 1, $y + $dy);
                }

                if ($isLeftWalkable) {
                    $neighbors[] = $grid->getNode($x - 1, $y + $dy);
                }
            }

            if ($isRightWalkable) {
                $neighbors[] = $grid->getNode($x + 1, $y);
            }

            if ($isLeftWalkable) {
                $neighbors[] = $grid->getNode($x - 1, $y);
            }
        }

        return $neighbors;
    }

    private static function jump(Node $node, Node $endNode, int $movement, Grid $grid) : ?Node
    {
        if ($movement === MovementType::STRAIGHT) {
            return self::jumpStraight($node, $endNode, $grid);
        } elseif ($movement === MovementType::DIAGONAL) {
            return self::jumpDiagonal($node, $endNode, $grid);
        } elseif ($movement === MovementType::DIAGONAL_ONE_OBSTACLE) {
            return self::jumpDiagonalOneObstacle($node, $endNode, $grid);
        }

        return self::jumpDiagonalNoObstacle($node, $endNode, $grid);
    }

    private static function jumpStraight(Node $node, Node $endNode, Grid $grid) : ?Node
    {
        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $endNode->getX();
        $dy = $y - $endNode->getY();

        if (!$node->isWalkable()) {
            return null;
        }

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0) {
            if (($grid->getNode($x, $y - 1)->isWalkable() && !$grid->getNode($x - $dx, $y - 1)->isWalkable())
                || ($grid->getNode($x, $y + 1)->isWalkable() && !$grid->getNode($x - $dx, $y + 1)->isWalkable())
            ) {
                return $node;
            }
        } elseif ($dy !== 0) {
            if (($grid->getNode($x - 1, $y)->isWalkable() && !$grid->getNode($x - 1, $y - $dy)->isWalkable())
                || ($grid->getNode($x + 1, $y)->isWalkable() && !$grid->getNode($x + 1, $y - $dy)->isWalkable())
            ) {
                return $node;
            }

            if (self::jumpStraight($grid->getNode($x + 1, $y), $node, $grid) !== null
                || self::jumpStraight($grid->getNode($x - 1, $y), $node, $grid) !== null
            ) {
                return $node;
            }
        } else {
            throw new \Exception('invalid movement');
        }

        return self::jumpStraight($grid->getNode($x + $dx, $y + $dy), $node, $grid);
    }

    private static function jumpDiagonal(Node $node, Node $endNode, Grid $grid) : ?Node
    {
        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $endNode->getX();
        $dy = $y - $endNode->getY();

        if (!$node->isWalkable()) {
            return null;
        }

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0 && $dy !== 0) {
            if (($grid->getNode($x - $dx, $y + $dy)->isWalkable() && !$grid->getNode($x - $dx, $y)->isWalkable())
                || ($grid->getNode($x + $dx, $y - $dy)->isWalkable() && !$grid->getNode($x, $y - $dy)->isWalkable())
            ) {
                return $node;
            }

            if (self::jumpDiagonal($grid->getNode($x + $dx, $y), $node, $grid) !== null
                || self::jumpDiagonal($grid->getNode($x, $y + $dy), $node, $grid) !== null
            ) {
                return $node;
            }
        } elseif ($dx !== 0 && $dy === 0) {
            if (($grid->getNode($x + $dx, $y + 1)->isWalkable() && !$grid->getNode($x, $y + 1)->isWalkable())
                || ($grid->getNode($x + $dx, $y - 1)->isWalkable() && !$grid->getNode($x, $y - 1)->isWalkable())
            ) {
                return $node;
            }
        } else {
            if (($grid->getNode($x + 1, $y + $dy)->isWalkable() && !$grid->getNode($x + 1, $y)->isWalkable())
                || ($grid->getNode($x - 1, $y + $dy)->isWalkable() && !$grid->getNode($x - 1, $y)->isWalkable())
            ) {
                return $node;
            }
        }

        return self::jumpDiagonal($grid->getNode($x + $dx, $y + $dy), $node, $grid);
    }

    private static function jumpDiagonalOneObstacle(Node $node, Node $endNode, Grid $grid) : ?Node
    {
        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $endNode->getX();
        $dy = $y - $endNode->getY();

        if (!$node->isWalkable()) {
            return null;
        }

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0 && $dy !== 0) {
            if (($grid->getNode($x - $dx, $y + $dy)->isWalkable() && !$grid->getNode($x - $dx, $y)->isWalkable())
                || ($grid->getNode($x + $dx, $y - $dy)->isWalkable() && !$grid->getNode($x, $y - $dy)->isWalkable())
            ) {
                return $node;
            }

            if (self::jumpDiagonalOneObstacle($grid->getNode($x + $dx, $y), $node, $grid) !== null
                || self::jumpDiagonalOneObstacle($grid->getNode($x, $y + $dy), $node, $grid) !== null
            ) {
                return $node;
            }
        } elseif ($dx !== 0 && $dy === 0) {
            if (($grid->getNode($x + $dx, $y + 1)->isWalkable() && !$grid->getNode($x, $y + 1)->isWalkable())
                || ($grid->getNode($x + $dx, $y - 1)->isWalkable() && !$grid->getNode($x, $y - 1)->isWalkable())
            ) {
                return $node;
            }
        } else {
            if (($grid->getNode($x + 1, $y + $dy)->isWalkable() && !$grid->getNode($x + 1, $y)->isWalkable())
                || ($grid->getNode($x - 1, $y + $dy)->isWalkable() && !$grid->getNode($x - 1, $y)->isWalkable())
            ) {
                return $node;
            }
        }

        if ($grid->getNode($x + $dx, $y)->isWalkable() || $grid->getNode($x, $y + $dy)->isWalkable()) {
            return self::jumpDiagonalOneObstacle($grid->getNode($x + $dx, $y + $dy), $node, $grid);
        }

        return null;
    }

    private static function jumpDiagonalNoObstacle(Node $node, Node $endNode, Grid $grid) : ?Node
    {
        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $endNode->getX();
        $dy = $y - $endNode->getY();

        if (!$node->isWalkable()) {
            return null;
        }

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0 && $dy !== 0) {
            if (self::jumpDiagonalNoObstacle($grid->getNode($x + $dx, $y), $node, $grid) !== null
                || self::jumpDiagonalNoObstacle($grid->getNode($x, $y + $dy), $node, $grid) !== null
            ) {
                return $node;
            }
        } elseif ($dx !== 0 && $dy === 0) {
            if (($grid->getNode($x, $y - 1)->isWalkable() && !$grid->getNode($x - $dx, $y - 1)->isWalkable())
                || ($grid->getNode($x, $y + 1)->isWalkable() && !$grid->getNode($x - $dx, $y + 1)->isWalkable())
            ) {
                return $node;
            }
        } elseif ($dx === 0 && $dy !== 0) {
            if (($grid->getNode($x - 1, $y)->isWalkable() && !$grid->getNode($x - 1, $y - $dy)->isWalkable())
                || ($grid->getNode($x + 1, $y)->isWalkable() && !$grid->getNode($x + 1, $y - $dy)->isWalkable())
            ) {
                return $node;
            }
        }

        if ($grid->getNode($x + $dx, $y)->isWalkable() || $grid->getNode($x, $y + $dy)->isWalkable()) {
            return self::jumpDiagonalNoObstacle($grid->getNode($x + $dx, $y + $dy), $node, $grid);
        }

        return null;
    }
}