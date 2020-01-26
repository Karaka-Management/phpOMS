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
 * Node on grid.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Node
{
    /**
     * X-Coordinate.
     *
     * @var int
     * @since 1.0.0
     */
    private int $x = 0;

    /**
     * Y-Coordinate.
     *
     * @var int
     * @since 1.0.0
     */
    private int $y = 0;

    /**
     * Cost of the node.
     *
     * @var float
     * @since 1.0.0
     */
    private float $weight = 1.0;

    /**
     * Can be walked?
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isWalkable = true;

    /**
     * Parent node.
     *
     * @var null|Node
     * @since 1.0.0
     */
    private ?Node $parent = null;

    /**
     * Constructor.
     *
     * @param int   $x          X-Coordinate
     * @param int   $y          Y-Coordinate
     * @param float $weight     Cost of reaching this node
     * @param bool  $isWalkable Can be walked on?
     *
     * @since 1.0.0
     */
    public function __construct(int $x, int $y, float $weight = 1.0, bool $isWalkable = true)
    {
        $this->x          = $x;
        $this->y          = $y;
        $this->weight     = $weight;
        $this->isWalkable = $isWalkable;
    }

    /**
     * Can this node be walked on?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isWalkable() : bool
    {
        return $this->isWalkable;
    }

    /**
     * Get the cost to walk on this node
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function getWeight() : float
    {
        return $this->weight;
    }

    /**
     * Get x-coordinate
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getX() : int
    {
        return $this->x;
    }

    /**
     * Get y-coordinate
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getY() : int
    {
        return $this->y;
    }

    /**
     * Set parent node
     *
     * @param null|Node $node Parent node
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setParent(?self $node) : void
    {
        $this->parent = $node;
    }

    /**
     * Get parent node
     *
     * @return null|Node
     *
     * @since 1.0.0
     */
    public function getParent() : ?self
    {
        return $this->parent;
    }

    /**
     * Is node equal to another node?
     *
     * @param Node $node Node to compare to
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isEqual(self $node) : bool
    {
        return $this->x === $node->getX() && $this->y === $node->getY();
    }

    /**
     * Get the coordinates of this node.
     *
     * @return array<string, int> ['x' => ?, 'y' => ?]
     *
     * @since 1.0.0
     */
    public function getCoordinates() : array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
}
