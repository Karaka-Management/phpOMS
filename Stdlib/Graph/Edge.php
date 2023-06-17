<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Graph
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Graph;

/**
 * Edge class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 2.0
 * @link    https://jingga.app
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
    public Node $node1;

    /**
     * Node2.
     *
     * In case of directed edges this is the to node/end node.
     *
     * @var Node
     * @since 1.0.0
     */
    public Node $node2;

    /**
     * Is graph/edge directed
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $isDirected = false;

    /**
     * Edge weight
     *
     * @var float
     * @since 1.0.0
     */
    public float $weight = 1.0;

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
    public function __construct(Node $node1, Node $node2, float $weight = 1.0, bool $isDirected = false)
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
        return $e1->weight <=> $e2->weight;
    }
}
