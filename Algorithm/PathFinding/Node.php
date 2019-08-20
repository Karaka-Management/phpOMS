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
class Node
{
    private int $x = 0;
    private int $y = 0;
    private float $weight = 1.0;
    private bool $isWalkable = true;
    
    public function __construct(int $x, int $y, float $weight = 1.0, bool $isWalkable = true)
    {
        $this->x          = $x;
        $this->y          = $y;
        $this->weight     = $weight;
        $this->isWalkable = $isWalkable;
    }
    
    public function isWalkable() : bool
    {
        return $this->isWalkable;
    }
    
    public function getWeight() : float
    {
        return $this->weight;
    }
    
    public function getX() : int
    {
        return $this->x;
    }
    
    public function getY() : int
    {
        return $this->y;
    }
}
