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
 * Grid of nodes.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Grid
{
    /**
     * Grid system containing all nodes
     *
     * @var  array
     * @since 1.0.0
     */
    private array $nodes = [[]];

    /**
     * Create a grid from an array
     *
     * [
     *      [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
     *      [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0,],
     *      [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
     *      [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0,],
     *      [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0,],
     *      [0, 0, 1, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9,],
     *      [0, 0, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0,],
     *      [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
     *      [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0,],
     *      [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0,],
     *      [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 9, 9, 0, 0, 0,],
     *      [0, 0, 0, 0, 0, 9, 9, 9, 0, 0, 9, 2, 0, 0, 0,],
     *      [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0,],
     *      [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
     *      [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
     * ]
     *
     * @param array<int, int[]> $gridArray Grid defined in an array (0 = empty, 1 = start, 2 = end, 9 = not walkable)
     * @param string            $node      Node type name
     *
     * @return Grid
     *
     * @since 1.0.0
     */
    public static function createGridFromArray(array $gridArray, string $node) : self
    {
        $grid = new self();
        foreach ($gridArray as $y => $yRow) {
            foreach ($yRow as $x => $xElement) {
                if ($xElement === 0 || $xElement === 1 || $xElement === 2) {
                    $empty = new $node($x, $y, 1.0, true);
                    $grid->setNode($x, $y, $empty);
                } elseif ($xElement === 9) {
                    $wall = new $node($x, $y, 1.0, false);
                    $grid->setNode($x, $y, $wall);
                }
            }
        }

        return $grid;
    }

    /**
     * Set node at position
     *
     * @param int  $x    X-Coordinate
     * @param int  $y    Y-Coordinate
     * @param Node $node Node to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setNode(int $x, int $y, Node $node) : void
    {
        $this->nodes[$y][$x] = $node;
    }

    /**
     * Get node at position
     *
     * @param int $x X-Coordinate
     * @param int $y Y-Coordinate
     *
     * @return null|Node
     *
     * @since 1.0.0
     */
    public function getNode(int $x, int $y) : ?Node
    {
        if (!isset($this->nodes[$y]) || !isset($this->nodes[$y][$x])) {
            return null;
        }

        return $this->nodes[$y][$x];
    }

    /**
     * Is node walkable"
     *
     * @param int $x X-Coordinate
     * @param int $y Y-Coordinate
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isWalkable(int $x, int $y) : bool
    {
        return isset($this->nodes[$y]) && isset($this->nodes[$y][$x]) && $this->nodes[$y][$x]->isWalkable();
    }

    /**
     * Get neighbors of node
     *
     * @param Node $node     Node to get neighbors from
     * @param int  $movement Allowed movements
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function getNeighbors(Node $node, int $movement) : array
    {
        $x = $node->getX();
        $y = $node->getY();

        $neighbors = [];

        $s0 = false;
        $s1 = false;
        $s2 = false;
        $s3 = false;
        $d0 = false;
        $d1 = false;
        $d2 = false;
        $d3 = false;

        if ($this->isWalkable($x, $y - 1)) {
            $neighbors[] = $this->getNode($x, $y - 1);
            $s0          = true;
        }

        if ($this->isWalkable($x + 1, $y)) {
            $neighbors[] = $this->getNode($x + 1, $y);
            $s1          = true;
        }

        if ($this->isWalkable($x, $y + 1)) {
            $neighbors[] = $this->getNode($x, $y + 1);
            $s2          = true;
        }

        if ($this->isWalkable($x - 1, $y)) {
            $neighbors[] = $this->getNode($x - 1, $y);
            $s3          = true;
        }

        if ($movement === MovementType::STRAIGHT) {
            /** @var Node[] $neighbors */
            return $neighbors;
        }

        if ($movement === MovementType::DIAGONAL_NO_OBSTACLE) {
            $d0 = $s3 && $s0;
            $d1 = $s0 && $s1;
            $d2 = $s1 && $s2;
            $d3 = $s2 && $s3;
        } elseif ($movement === MovementType::DIAGONAL_ONE_OBSTACLE) {
            $d0 = $s3 || $s0;
            $d1 = $s0 || $s1;
            $d2 = $s1 || $s2;
            $d3 = $s2 || $s3;
        } elseif ($movement === MovementType::DIAGONAL) {
            $d0 = true;
            $d1 = true;
            $d2 = true;
            $d3 = true;
        }

        if ($d0 && $this->isWalkable($x - 1, $y - 1)) {
            $neighbors[] = $this->getNode($x - 1, $y - 1);
        }

        if ($d1 && $this->isWalkable($x + 1, $y - 1)) {
            $neighbors[] = $this->getNode($x + 1, $y - 1);
        }

        if ($d2 && $this->isWalkable($x + 1, $y + 1)) {
            $neighbors[] = $this->getNode($x + 1, $y + 1);
        }

        if ($d3 && $this->isWalkable($x - 1, $y + 1)) {
            $neighbors[] = $this->getNode($x - 1, $y + 1);
        }

        /** @var Node[] $neighbors */
        return $neighbors;
    }
}
