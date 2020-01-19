<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
 * Graph class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Graph
{
    /**
     * Nodes.
     *
     * @var   Node[]
     * @since 1.0.0
     */
    protected $nodes = [];

    /**
     * Set node to graph.
     *
     * @param Node $node Graph node
     *
     * @return Graph
     *
     * @since 1.0.0
     */
    public function setNode(Node $node) : self
    {
        $this->nodes[$node->getId()] = $node;

        return $this;
    }

    /**
     * Get graph node
     *
     * @param mixed $key Node key
     *
     * @return null|Node
     *
     * @since 1.0.0
     */
    public function getNode($key) : ?Node
    {
        return $this->nodes[$key] ?? null;
    }

    /**
     * Get graph nodes
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function getNodes() : array
    {
        return $this->nodes;
    }

    /**
     * Get graph edges
     *
     * @return Edge[]
     *
     * @since 1.0.0
     */
    public function getEdges() : array
    {
        $edges = [];

        foreach ($this->nodes as $node) {
            $nodeEdges = $node->getEdges();

            foreach ($nodeEdges as $edge) {
                if (!isset($edges[$edge->getNode1()->getId() . ':' . $edge->getNode2()->getId()])
                    && !isset($edges[$edge->getNode2()->getId() . ':' . $edge->getNode1()->getId()])
                ) {
                    $edges[$edge->getNode1()->getId() . ':' . $edge->getNode2()->getId()] = $edge;
                }
            }
        }

        return $edges;
    }

    /**
     * Get all bridges.
     *
     * @return Edge[]
     *
     * @since 1.0.0
     */
    public function getBridges() : array
    {
        $visited   = [];
        $parent    = [];
        $discovery = [];
        $low       = [];
        $index     = 0;
        $bridges   = [];

        foreach ($this->nodes as $i => $node) {
            if (!isset($visited[$i]) || $visited[$i] === false) {
                $this->bridgesDepthFirstSearch($node, $visited, $discovery, $low, $parent, $index, $bridges);
            }
        }

        return $bridges;
    }

    /**
     * Fill bridge array
     *
     * @param Node   $node      Node to check bridge for
     * @param bool[] $visited   Visited nodes
     * @param int[]  $discovery Discovered
     * @param int[]  $low       Lowest preorder of any vertex connected to ?
     * @param Node[] $parent    Parent node
     * @param int    $index     Node index
     * @param Edge[] $bridges   Edges which represent bridges
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function bridgesDepthFirstSearch(
        Node $node,
        array &$visited,
        array &$discovery,
        array &$low,
        array &$parent,
        int &$index,
        array &$bridges
    ) : void {
        $id           = $node->getId();
        $visited[$id] = true;

        ++$index;

        $discovery[$id] = $index;
        $low[$id]       = $index;

        $edges = $this->nodes[$id]->getEdges();
        foreach ($edges as $edge) {
            $neighbor = !$edge->getNode1()->isEqual($node) ? $edge->getNode1() : $edge->getNode2();

            if (!isset($visited[$neighbor->getId()]) || !$visited[$neighbor->getId()]) {
                $parent[$neighbor->getId()] = $node;

                $this->bridgesDepthFirstSearch($neighbor, $visited, $discovery, $low, $parent, $index, $bridges);

                $low[$id] = \min($low[$id], $low[$neighbor->getId()]);

                if ($low[$neighbor->getId()] > $discovery[$id]) {
                    $bridges[] = $edge;
                }
            } elseif (isset($parent[$id]) && !$neighbor->isEqual($parent[$id])) {
                $low[$id] = \min($low[$id], $discovery[$neighbor->getId()]);
            }
        }
    }

    /**
     * Get minimal spanning tree using Kruskal's algorithm.
     *
     * @return Tree
     *
     * @since 1.0.0
     */
    public function getKruskalMinimalSpanningTree() : Tree
    {
        return new Tree();
    }

    /**
     * Get minimal spanning tree using Prim's algorithm
     *
     * @return Tree
     *
     * @since 1.0.0
     */
    public function getPrimMinimalSpanningTree() : Tree
    {
        return new Tree();
    }

    /**
     * Get circles in graph.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getCircle() : array
    {
        return [];
    }

    /**
     * Get shortest path using Floyd Warschall algorithm.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getFloydWarshallShortestPath() : array
    {
        return [];
    }

    /**
     * Get shortest path using Dijkstra algorithm.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getDijkstraShortestPath() : array
    {
        return [];
    }

    /**
     * Perform depth first traversal.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function depthFirstTraversal() : array
    {
        return [];
    }

    /**
     * Perform breadth first traversal.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function breadthFirstTraversal() : array
    {
        return [];
    }

    /**
     * Get longest path in graph.
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function longestPath() : array
    {
        return [];
    }

    /**
     * Get longest path between two nodes.
     *
     * @param mixed $node1 Graph node
     * @param mixed $node2 Graph node
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function longestPathBetweenNodes($node1, $node2) : array
    {
        if (!($node1 instanceof Node)) {
            $node1 = $this->getNode($node1);
        }

        if (!($node2 instanceof Node)) {
            $node2 = $this->getNode($node2);
        }

        return [];
    }

    /**
     * Get order of the graph.
     *
     * The order of a graph is the amount of nodes it contains.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getOrder() : int
    {
        return \count($this->nodes);
    }

    /**
     * Get size of the graph.
     *
     * The size of the graph is the amount of edges it contains.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSize() : int
    {
        $edges = $this->getEdges();

        return \count($edges);
    }

    /**
     * Get diameter of graph.
     *
     * The diameter of a graph is the longest shortest path between two nodes.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getDiameter() : int
    {
        $diameter = 0;

        foreach ($this->nodes as $node1) {
            foreach ($this->nodes as $node2) {
                if ($node1 === $node2) {
                    continue;
                }

                /** @var int $diameter */
                $diameter = \max($diameter, $this->getFloydWarshallShortestPath());
            }
        }

        /** @var int $diameter */
        return $diameter;
    }

    /**
     * Get the graph girth
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getGirth() : int
    {
        return 0;
    }

    /**
     * Get the graph circuit rank
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getCircuitRank() : int
    {
        return 0;
    }

    /**
     * Get the graph node connectivity
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getNodeConnectivity() : int
    {
        return 0;
    }

    /**
     * Get the graph edge connectivity
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getEdgeConnectivity() : int
    {
        return 0;
    }

    /**
     * Is the graph connected?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isConnected() : bool
    {
        // todo: implement
        return true;
    }

    /**
     * Get unconnected sub graphs
     *
     * @return Graph[]
     *
     * @since 1.0.0
     */
    public function getUnconnected() : array
    {
        // todo: implement
        // get all unconnected sub graphs

        return [];
    }

    /**
     * Is the graph bipartite?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isBipartite() : bool
    {
        // todo: implement
        return true;
    }

    /**
     * Is the graph triangle?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isTriangleFree() : bool
    {
        // todo: implement
        return true;
    }

    /**
     * Is the graph circle free?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isCircleFree() : bool
    {
        // todo: implement
        return true;
    }
}
