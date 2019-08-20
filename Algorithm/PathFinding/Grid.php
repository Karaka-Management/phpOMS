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
    private ?Node $nullNode = null;
    
    public function __construct(Node $nullNode)
    {
        $this->nullNode = $nullNode;
    }
    
    public function getNullNode() : Node
    {
        return $this->nullNode;
    }
    
    public function getNode(int $x, int $y) : Node
    {
        if (!isset($this->nodes[$x]) || $this->nodes[$x][$y]) {
            return $this->nullNode;
        }
        
        return $this->nodes[$x][$y];
    }
    
    public function getNeighbors(Node $node, int $movement) : array
    {
        $x = $node->getX();
        $y = $node->getY();
        
        $neighbours = [];
        $s0 = false;
        $s1 = false;
        $s2 = false;
        $s3 = false;
        $d0 = false;
        $d1 = false;
        $d2 = false;
        $d3 = false;
        
        $nodes = $this->nodes;
        
        if ($this->getNode($x, $y - 1)->isWalkable()) {
            $neighbours[$x][$y - 1];
            $s0 = true;
        }
        
        if ($this->getNode($x + 1, $y)->isWalkable()) {
            $neighbours[$x + 1][$y];
            $s1 = true;
        }
        
        if ($this->getNode($x, $y + 1)->isWalkable()) {
            $neighbours[$x][$y + 1];
            $s2 = true;
        }
        
        if ($this->getNode($x - 1, $y)->isWalkable()) {
            $neighbours[$x - 1][$y];
            $s3 = true;
        }
        
        if ($movement === MovementType::STRAIGHT) {
            return $neighbours;
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
        } else if ($movement === MovementType::DIAGONAL) {
            $d0 = true;
            $d1 = true;
            $d2 = true;
            $d3 = true;
        }
    }
}
