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
 * Grid of nodes.
 *
 * @package    phpOMS\Algorithm\PathFinding
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Grid
{
    private array $nodes = [[]];

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

    public function setNode(int $x, int $y, Node $node) : void
    {
        $this->nodes[$y][$x] = $node;
    }

    public function getNode(int $x, int $y) : ?Node
    {
        if (!isset($this->nodes[$y]) || $this->nodes[$y][$x]) {
            // todo: add null node to grid because we need to modify some properties later on and remember them!
            return null;
        }

        return $this->nodes[$y][$x];
    }

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

        // todo: check $x and $y because original implementation is flipped!!!
        if ($this->getNode($x, $y - 1)->isWalkable()) {
            $neighbors[] = $this->getNode($x, $y - 1);
            $s0 = true;
        }

        if ($this->getNode($x + 1, $y)->isWalkable()) {
            $neighbors[] = $this->getNode($x + 1, $y);
            $s1 = true;
        }

        if ($this->getNode($x, $y + 1)->isWalkable()) {
            $neighbors[] = $this->getNode($x, $y + 1);
            $s2 = true;
        }

        if ($this->getNode($x - 1, $y)->isWalkable()) {
            $neighbors[] = $this->getNode($x - 1, $y);
            $s3 = true;
        }

        if ($movement === MovementType::STRAIGHT) {
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

        if ($d0 && $this->getNode($x - 1, $y - 1)->isWalkable()) {
            $neighbors[] = $this->getNode($x - 1, $y - 1);
        }

        if ($d1 && $this->getNode($x + 1, $y - 1)->isWalkable()) {
            $neighbors[] = $this->getNode($x + 1, $y - 1);
        }

        if ($d2 && $this->getNode($x + 1, $y + 1)->isWalkable()) {
            $neighbors[] = $this->getNode($x + 1, $y + 1);
        }

        if ($d3 && $this->getNode($x - 1, $y + 1)->isWalkable()) {
            $neighbors[] = $this->getNode($x - 1, $y + 1);
        }

        return $neighbors;
    }
}
