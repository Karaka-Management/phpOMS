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
final class AStar implements PathFinderInterface
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
        /** @var null|AStarNode $startNode */
        $startNode = $grid->getNode($startX, $startY);
        /** @var null|AStarNode $endNode */
        $endNode = $grid->getNode($endX, $endY);

        if ($startNode === null || $endNode === null) {
            return new Path($grid);
        }

        $startNode->setG(0.0);
        $startNode->setF(0.0);
        $startNode->setOpened(true);

        $openList = new Heap(function(AStarNode $node1, AStarNode $node2) {
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

            /** @var AStarNode[] $neighbors */
            $neighbors       = $grid->getNeighbors($node, $movement);
            $neighborsLength = \count($neighbors);
            for ($i = 0; $i < $neighborsLength; ++$i) {
                $neighbor = $neighbors[$i];

                if ($neighbor->isClosed()) {
                    continue;
                }

                $ng = $node->getG() + (($neighbor->getX() - $node->getX() === 0 || $neighbor->getY() - $node->getY() === 0) ? 1 : \sqrt(2));

                if (!$neighbor->isOpened() || $ng < $neighbor->getG()) {
                    $neighbor->setG($ng);
                    $neighbor->setH($neighbor->getG() ?? $neighbor->getWeight() * Heuristic::metric($neighbor->getCoordinates(), $endNode->getCoordinates(), $heuristic));
                    $neighbor->setF($neighbor->getG() + $neighbor->getH());
                    $neighbor->parent = $node;

                    if (!$neighbor->isOpened()) {
                        $openList->push($neighbor);
                        $neighbor->setOpened(true);
                    } else {
                        $openList->update($neighbor);
                    }
                }
            }
        }

        $path = new Path($grid);

        while ($node !== null) {
            $path->addNode($node);
            $node = $node->parent;
        }

        return $path;
    }
}
