<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\PathFinding
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

use phpOMS\Stdlib\Base\HeapItemInterface;

/**
 * Node on grid.
 *
 * @package phpOMS\Algorithm\PathFinding
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Node implements HeapItemInterface
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
    public bool $isWalkable = true;

    /**
     * Parent node.
     *
     * @var null|Node
     * @since 1.0.0
     */
    public ?Node $parent = null;

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
     * Is node equal to another node?
     *
     * @param Node $node Node to compare to
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isEqual(HeapItemInterface $node) : bool
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
