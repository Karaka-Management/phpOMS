<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Stdlib\Graph
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Graph;

/**
 * Edge class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Edge
{
    /**
     * Node1.
     *
     * In case of directed edges this is the from node/starting node.
     *
     * @var Node
     * @since 1.0.0
     */
    private Node $node1;

    /**
     * Node2.
     *
     * In case of directed edges this is the to node/end node.
     *
     * @var Node
     * @since 1.0.0
     */
    private Node $node2;

    /**
     * Is graph/edge directed
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isDirected = false;

    /**
     * Edge weight
     *
     * @var float
     * @since 1.0.0
     */
    private float $weight = 0.0;

    /**
     * Constructor.
     *
     * @param Node  $node1      Graph node (start node in case of directed edge)
     * @param Node  $node2      Graph node (end node in case of directed edge)
     * @param float $weight     weight/cost of the edge
     * @param bool  $isDirected Is directed edge
     *
     * @since 1.0.0
     */
    public function __construct(Node $node1, Node $node2, float $weight = 0.0, bool $isDirected = false)
    {
        $this->node1      = $node1;
        $this->node2      = $node2;
        $this->weight     = $weight;
        $this->isDirected = $isDirected;
    }

    /**
     * Get nodes of the edge.
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function getNodes() : array
    {
        return [$this->node1, $this->node2];
    }

    /**
     * Set weight
     *
     * @param float $weight Weight
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setWeight(float $weight) : void
    {
        $this->weight = $weight;
    }

    /**
     * Get node of the edge.
     *
     * @return Node
     *
     * @since 1.0.0
     */
    public function getNode1() : Node
    {
        return $this->node1;
    }

    /**
     * Get node of the edge.
     *
     * @return Node
     *
     * @since 1.0.0
     */
    public function getNode2() : Node
    {
        return $this->node2;
    }

    /**
     * Get weight
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
     * Is directed edge
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isDirected() : bool
    {
        return $this->isDirected;
    }

    /**
     * Compare edge weights
     *
     * @param Edge $e1 Edge 1
     * @param Edge $e2 Edge 2
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function compare(self $e1, self $e2) : int
    {
        return $e1->getWeight() <=> $e2->getWeight();
    }
}
