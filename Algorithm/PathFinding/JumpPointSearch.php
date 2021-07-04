<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Algorithm\PathFinding
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 *
 * Extended based on:
 * MIT License
 * (c) 2011-2012 Xueqiao Xu <xueqiaoxu@gmail.com>
 * (c) PathFinding.js
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

use phpOMS\Stdlib\Base\Heap;

/**
 * Perform path finding.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class JumpPointSearch implements PathFinderInterface
{
    /**
     * {@inheritdoc}
     */
    public static function findPath(
        int $startX, int $startY,
        int $endX, int $endY,
        Grid $grid,
        int $heuristic, int $movement
    ) : Path
    {
        /** @var null|JumpPointNode $startNode */
        $startNode = $grid->getNode($startX, $startY);
        /** @var null|JumpPointNode $endNode */
        $endNode = $grid->getNode($endX, $endY);

        if ($startNode === null || $endNode === null) {
            return new Path($grid);
        }

        $startNode->setG(0.0);
        $startNode->setF(0.0);
        $startNode->setOpened(true);

        $openList = new Heap(function($node1, $node2) {
            return $node1->getF() - $node2->getF();
        });

        $openList->push($startNode);
        $node = null;

        while (!$openList->isEmpty()) {
            $node = $openList->pop();
            $node->setClosed(true);

            if ($node->isEqual($endNode)) {
                break;
            }

            $openList = self::identifySuccessors($node, $grid, $heuristic, $movement, $endNode, $openList);
        }

        $path = new Path($grid);

        while ($node !== null) {
            $path->addNode($node);
            $node = $node->parent;
        }

        return $path;
    }

    /**
     * Find possible successor jump points
     *
     * @param JumpPointNode $node      Node to find successor for
     * @param Grid          $grid      Grid of the nodes
     * @param int           $heuristic Heuristic/metrics type for the distance calculation
     * @param int           $movement  Movement type
     * @param JumpPointNode $endNode   End node to find path to
     * @param Heap          $openList  Heap of open nodes
     *
     * @return Heap
     *
     * @since 1.0.0
     */
    public static function identifySuccessors(JumpPointNode $node, Grid $grid, int $heuristic, int $movement, JumpPointNode $endNode, Heap $openList) : Heap
    {
        /** @var JumpPointNode[] $neighbors */
        $neighbors       = self::findNeighbors($node, $movement, $grid);
        $neighborsLength = \count($neighbors);

        for ($i = 0; $i < $neighborsLength; ++$i) {
            $neighbor = $neighbors[$i];

            if ($neighbor === null) {
                continue;
            }

            $jumpPoint = self::jump($neighbor, $node, $endNode, $movement, $grid);

            if ($jumpPoint === null || $jumpPoint->isClosed()) {
                continue;
            }

            $d  = Heuristic::metric($node->getCoordinates(), $jumpPoint->getCoordinates(), HeuristicType::OCTILE);
            $ng = $node->getG() + $d;

            if (!$jumpPoint->isOpened() || $ng < $jumpPoint->getG()) {
                $jumpPoint->setG($ng);
                $jumpPoint->setH($jumpPoint->getH() ?? Heuristic::metric($jumpPoint->getCoordinates(), $endNode->getCoordinates(), $heuristic));
                $jumpPoint->setF($jumpPoint->getG() + $jumpPoint->getH());
                $jumpPoint->parent = $node;

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

    /**
     * Find neighbor of node
     *
     * @param JumpPointNode $node     Node to find successor for
     * @param int           $movement Movement type
     * @param Grid          $grid     Grid of the nodes
     *
     * @return Node[] Neighbors of node
     *
     * @since 1.0.0
     */
    private static function findNeighbors(JumpPointNode $node, int $movement, Grid $grid) : array
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

    /**
     * Find neighbor of node
     *
     * @param JumpPointNode $node Node to find successor for
     * @param Grid          $grid Grid of the nodes
     *
     * @return Node[] Neighbors of node
     *
     * @since 1.0.0
     */
    private static function findNeighborsStraight(JumpPointNode $node, Grid $grid) : array
    {
        if ($node->parent === null) {
            return $grid->getNeighbors($node, MovementType::STRAIGHT);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->parent->getX();
        $py = $node->parent->getY();

        /** @var int $dx */
        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        /** @var int $dy */
        $dy = ($y - $py) / \max(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0) {
            if ($grid->isWalkable($x, $y - 1)) {
                $neighbors[] = $grid->getNode($x, $y - 1);
            }

            if ($grid->isWalkable($x, $y + 1)) {
                $neighbors[] = $grid->getNode($x, $y + 1);
            }

            if ($grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }
        } elseif ($dy !== 0) {
            if ($grid->isWalkable($x - 1, $y)) {
                $neighbors[] = $grid->getNode($x - 1, $y);
            }

            if ($grid->isWalkable($x + 1, $y)) {
                $neighbors[] = $grid->getNode($x + 1, $y);
            }

            if ($grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }
        }

        /** @var JumpPointNode[] $neighbors */
        return $neighbors;
    }

    /**
     * Find neighbor of node
     *
     * @param JumpPointNode $node Node to find successor for
     * @param Grid          $grid Grid of the nodes
     *
     * @return Node[] Neighbors of node
     *
     * @since 1.0.0
     */
    private static function findNeighborsDiagonal(JumpPointNode $node, Grid $grid) : array
    {
        if ($node->parent === null) {
            return $grid->getNeighbors($node, MovementType::DIAGONAL);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->parent->getX();
        $py = $node->parent->getY();

        /** @var int $dx */
        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        /** @var int $dy */
        $dy = ($y - $py) / \max(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0 && $dy !== 0) {
            if ($grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if ($grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if ($grid->isWalkable($x + $dx, $y + $dy)) {
                $neighbors[] = $grid->getNode($x + $dx, $y + $dy);
            }

            if (!$grid->isWalkable($x - $dx, $y)) {
                $neighbors[] = $grid->getNode($x - $dx, $y + $dy);
            }

            if (!$grid->isWalkable($x, $y - $dy)) {
                $neighbors[] = $grid->getNode($x + $dx, $y - $dy);
            }
        } elseif ($dx === 0) {
            if ($grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if (!$grid->isWalkable($x + 1, $y)) {
                $neighbors[] = $grid->getNode($x + 1, $y + $dy);
            }

            if (!$grid->isWalkable($x - 1, $y)) {
                $neighbors[] = $grid->getNode($x - 1, $y + $dy);
            }
        } else {
            if ($grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if (!$grid->isWalkable($x, $y + 1)) {
                $neighbors[] = $grid->getNode($x + $dx, $y + 1);
            }

            if (!$grid->isWalkable($x, $y - 1)) {
                $neighbors[] = $grid->getNode($x + $dx, $y - 1);
            }
        }

        /** @var JumpPointNode[] $neighbors */
        return $neighbors;
    }

    /**
     * Find neighbor of node
     *
     * @param JumpPointNode $node Node to find successor for
     * @param Grid          $grid Grid of the nodes
     *
     * @return Node[] Neighbors of node
     *
     * @since 1.0.0
     */
    private static function findNeighborsDiagonalOneObstacle(JumpPointNode $node, Grid $grid) : array
    {
        if ($node->parent === null) {
            return $grid->getNeighbors($node, MovementType::DIAGONAL_ONE_OBSTACLE);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->parent->getX();
        $py = $node->parent->getY();

        /** @var int $dx */
        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        /** @var int $dy */
        $dy = ($y - $py) / \max(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0 && $dy !== 0) {
            if ($grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if ($grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if ($grid->isWalkable($x, $y + $dy) || $grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y + $dy);
            }

            if (!$grid->isWalkable($x - $dx, $y) && $grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x - $dx, $y + $dy);
            }

            if (!$grid->isWalkable($x, $y - $dy) && $grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y - $dy);
            }
        } elseif ($dx === 0) {
            if ($grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
                if (!$grid->isWalkable($x + 1, $y)) {
                    $neighbors[] = $grid->getNode($x + 1, $y + $dy);
                }
                if (!$grid->isWalkable($x - 1, $y)) {
                    $neighbors[] = $grid->getNode($x - 1, $y + $dy);
                }
            }
        } else {
            if ($grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
                if (!$grid->isWalkable($x, $y + 1)) {
                    $neighbors[] = $grid->getNode($x + $dx, $y + 1);
                }
                if (!$grid->isWalkable($x, $y - 1)) {
                    $neighbors[] = $grid->getNode($x + $dx, $y - 1);
                }
            }
        }

        /** @var JumpPointNode[] $neighbors */
        return $neighbors;
    }

    /**
     * Find neighbor of node
     *
     * @param JumpPointNode $node Node to find successor for
     * @param Grid          $grid Grid of the nodes
     *
     * @return Node[] Neighbors of node
     *
     * @since 1.0.0
     */
    private static function findNeighborsDiagonalNoObstacle(JumpPointNode $node, Grid $grid) : array
    {
        if ($node->parent === null) {
            return $grid->getNeighbors($node, MovementType::DIAGONAL_NO_OBSTACLE);
        }

        $x = $node->getX();
        $y = $node->getY();

        $px = $node->parent->getX();
        $py = $node->parent->getY();

        /** @var int $dx */
        $dx = ($x - $px) / \max(\abs($x - $px), 1);
        /** @var int $dy */
        $dy = ($y - $py) / \max(\abs($y - $py), 1);

        $neighbors = [];
        if ($dx !== 0 && $dy !== 0) {
            if ($grid->isWalkable($x, $y + $dy)) {
                $neighbors[] = $grid->getNode($x, $y + $dy);
            }

            if ($grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y);
            }

            if ($grid->isWalkable($x, $y + $dy) || $grid->isWalkable($x + $dx, $y)) {
                $neighbors[] = $grid->getNode($x + $dx, $y + $dy);
            }
        } elseif ($dx !== 0 && $dy === 0) {
            $isNextWalkable   = $grid->isWalkable($x + $dx, $y);
            $isTopWalkable    = $grid->isWalkable($x, $y + 1);
            $isBottomWalkable = $grid->isWalkable($x, $y - 1);

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
            $isNextWalkable  = $grid->isWalkable($x, $y + $dy);
            $isRightWalkable = $grid->isWalkable($x + 1, $y);
            $isLeftWalkable  = $grid->isWalkable($x - 1, $y);

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

        /** @var JumpPointNode[] $neighbors */
        return $neighbors;
    }

    /**
     * Find next jump point
     *
     * @param null|JumpPointNode $node     Node to find jump point from
     * @param null|JumpPointNode $pNode    Parent node
     * @param JumpPointNode      $endNode  End node to find path to
     * @param int                $movement Movement type
     * @param Grid               $grid     Grid of the nodes
     *
     * @return null|JumpPointNode
     *
     * @since 1.0.0
     */
    private static function jump(?JumpPointNode $node, ?JumpPointNode $pNode, JumpPointNode $endNode, int $movement, Grid $grid) : ?JumpPointNode
    {
        if ($movement === MovementType::STRAIGHT) {
            return self::jumpStraight($node, $pNode, $endNode, $grid);
        } elseif ($movement === MovementType::DIAGONAL) {
            return self::jumpDiagonal($node, $pNode, $endNode, $grid);
        } elseif ($movement === MovementType::DIAGONAL_ONE_OBSTACLE) {
            return self::jumpDiagonalOneObstacle($node, $pNode, $endNode, $grid);
        }

        return self::jumpDiagonalNoObstacle($node, $pNode, $endNode, $grid);
    }

    /**
     * Find next jump point
     *
     * @param null|JumpPointNode $node    Node to find jump point from
     * @param null|JumpPointNode $pNode   Parent node
     * @param JumpPointNode      $endNode End node to find path to
     * @param Grid               $grid    Grid of the nodes
     *
     * @return null|JumpPointNode
     *
     * @since 1.0.0
     */
    private static function jumpStraight(?JumpPointNode $node, ?JumpPointNode $pNode, JumpPointNode $endNode, Grid $grid) : ?JumpPointNode
    {
        if ($node === null || $pNode === null || !$node->isWalkable) {
            return null;
        }

        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $pNode->getX();
        $dy = $y - $pNode->getY();

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0) {
            if (($grid->isWalkable($x, $y - 1) && !$grid->isWalkable($x - $dx, $y - 1))
                || ($grid->isWalkable($x, $y + 1) && !$grid->isWalkable($x - $dx, $y + 1))
            ) {
                return $node;
            }
        } elseif ($dy !== 0) {
            if (($grid->isWalkable($x - 1, $y) && !$grid->isWalkable($x - 1, $y - $dy))
                || ($grid->isWalkable($x + 1, $y) && !$grid->isWalkable($x + 1, $y - $dy))
            ) {
                return $node;
            }

            if (self::jumpStraight($grid->getNode($x + 1, $y), $node, $endNode, $grid) !== null
                || self::jumpStraight($grid->getNode($x - 1, $y), $node, $endNode, $grid) !== null
            ) {
                return $node;
            }
        } else {
            throw new \Exception('invalid movement'); // @codeCoverageIgnore
        }

        return self::jumpStraight($grid->getNode($x + $dx, $y + $dy), $node, $endNode, $grid);
    }

    /**
     * Find next jump point
     *
     * @param null|JumpPointNode $node    Node to find jump point from
     * @param null|JumpPointNode $pNode   Parent node
     * @param JumpPointNode      $endNode End node to find path to
     * @param Grid               $grid    Grid of the nodes
     *
     * @return null|JumpPointNode
     *
     * @since 1.0.0
     */
    private static function jumpDiagonal(?JumpPointNode $node, ?JumpPointNode $pNode, JumpPointNode $endNode, Grid $grid) : ?JumpPointNode
    {
        if ($node === null || $pNode === null || !$node->isWalkable) {
            return null;
        }

        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $pNode->getX();
        $dy = $y - $pNode->getY();

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0 && $dy !== 0) {
            if (($grid->isWalkable($x - $dx, $y + $dy) && !$grid->isWalkable($x - $dx, $y))
                || ($grid->isWalkable($x + $dx, $y - $dy) && !$grid->isWalkable($x, $y - $dy))
            ) {
                return $node;
            }

            if (self::jumpDiagonal($grid->getNode($x + $dx, $y), $node, $endNode, $grid) !== null
                || self::jumpDiagonal($grid->getNode($x, $y + $dy), $node, $endNode, $grid) !== null
            ) {
                return $node;
            }
        } elseif ($dx !== 0) {
            if (($grid->isWalkable($x + $dx, $y + 1) && !$grid->isWalkable($x, $y + 1))
                || ($grid->isWalkable($x + $dx, $y - 1) && !$grid->isWalkable($x, $y - 1))
            ) {
                return $node;
            }
        } else {
            if (($grid->isWalkable($x + 1, $y + $dy) && !$grid->isWalkable($x + 1, $y))
                || ($grid->isWalkable($x - 1, $y + $dy) && !$grid->isWalkable($x - 1, $y))
            ) {
                return $node;
            }
        }

        return self::jumpDiagonal($grid->getNode($x + $dx, $y + $dy), $node, $endNode, $grid);
    }

    /**
     * Find next jump point
     *
     * @param null|JumpPointNode $node    Node to find jump point from
     * @param null|JumpPointNode $pNode   Parent node
     * @param JumpPointNode      $endNode End node to find path to
     * @param Grid               $grid    Grid of the nodes
     *
     * @return null|JumpPointNode
     *
     * @since 1.0.0
     */
    private static function jumpDiagonalOneObstacle(?JumpPointNode $node, ?JumpPointNode $pNode, JumpPointNode $endNode, Grid $grid) : ?JumpPointNode
    {
        if ($node === null || $pNode === null || !$node->isWalkable) {
            return null;
        }

        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $pNode->getX();
        $dy = $y - $pNode->getY();

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0 && $dy !== 0) {
            if (($grid->isWalkable($x - $dx, $y + $dy) && !$grid->isWalkable($x - $dx, $y))
                || ($grid->isWalkable($x + $dx, $y - $dy) && !$grid->isWalkable($x, $y - $dy))
            ) {
                return $node;
            }

            if (self::jumpDiagonalOneObstacle($grid->getNode($x + $dx, $y), $node, $endNode, $grid) !== null
                || self::jumpDiagonalOneObstacle($grid->getNode($x, $y + $dy), $node, $endNode, $grid) !== null
            ) {
                return $node;
            }
        } elseif ($dx !== 0) {
            if (($grid->isWalkable($x + $dx, $y + 1) && !$grid->isWalkable($x, $y + 1))
                || ($grid->isWalkable($x + $dx, $y - 1) && !$grid->isWalkable($x, $y - 1))
            ) {
                return $node;
            }
        } else {
            if (($grid->isWalkable($x + 1, $y + $dy) && !$grid->isWalkable($x + 1, $y))
                || ($grid->isWalkable($x - 1, $y + $dy) && !$grid->isWalkable($x - 1, $y))
            ) {
                return $node;
            }
        }

        if ($grid->isWalkable($x + $dx, $y) || $grid->isWalkable($x, $y + $dy)) {
            return self::jumpDiagonalOneObstacle($grid->getNode($x + $dx, $y + $dy), $node, $endNode, $grid);
        }

        return null;
    }

    /**
     * Find next jump point
     *
     * @param null|JumpPointNode $node    Node to find jump point from
     * @param null|JumpPointNode $pNode   Parent node
     * @param JumpPointNode      $endNode End node to find path to
     * @param Grid               $grid    Grid of the nodes
     *
     * @return null|JumpPointNode
     *
     * @since 1.0.0
     */
    private static function jumpDiagonalNoObstacle(?JumpPointNode $node, ?JumpPointNode $pNode, JumpPointNode $endNode, Grid $grid) : ?JumpPointNode
    {
        if ($node === null || $pNode === null || !$node->isWalkable) {
            return null;
        }

        $x = $node->getX();
        $y = $node->getY();

        $dx = $x - $pNode->getX();
        $dy = $y - $pNode->getY();

        // not always necessary but might be important for the future
        $node->setTested(true);

        if ($node->isEqual($endNode)) {
            return $node;
        }

        if ($dx !== 0 && $dy !== 0) {
            if (self::jumpDiagonalNoObstacle($grid->getNode($x + $dx, $y), $node, $endNode, $grid) !== null
                || self::jumpDiagonalNoObstacle($grid->getNode($x, $y + $dy), $node, $endNode, $grid) !== null
            ) {
                return $node;
            }
        } elseif ($dx !== 0) {
            if (($grid->isWalkable($x, $y - 1) && !$grid->isWalkable($x - $dx, $y - 1))
                || ($grid->isWalkable($x, $y + 1) && !$grid->isWalkable($x - $dx, $y + 1))
            ) {
                return $node;
            }
        } elseif ($dy !== 0) {
            if (($grid->isWalkable($x - 1, $y) && !$grid->isWalkable($x - 1, $y - $dy))
                || ($grid->isWalkable($x + 1, $y) && !$grid->isWalkable($x + 1, $y - $dy))
            ) {
                return $node;
            }
        }

        if ($grid->isWalkable($x + $dx, $y) || $grid->isWalkable($x, $y + $dy)) {
            return self::jumpDiagonalNoObstacle($grid->getNode($x + $dx, $y + $dy), $node, $endNode, $grid);
        }

        return null;
    }
}
