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
 *
 * @todo Orange-Management/phpOMS#10
 *      * Count all paths between 2 nodes
 *      * Return all paths between 2 nodes
 *      * Find cycles using graph coloring
 *      * Find a negative cycle
 *      * Find cycles with n length
 *      * Find cycles with odd length
 *      * Find shortest path between 2 nodes
 *      * Find longest path between 2 nodes
 *      * Find islands
 *      * Find all unreachable nodes
 *      * Check if strongly connected
 *      * Find longest path between 2 nodes
 *      * Find longest path
 *      * Get the girth
 *      * Get the circuit rank
 *      * Get the node connectivity
 *      * Get the edge connectivity
 *      * Is the graph connected
 *      * Get the unconnected nodes as their own graph
 *      * Check if bipartite
 *      * Check if triangle free
 */
class Graph
{
    /**
     * Nodes.
     *
     * @var Node[]
     * @since 1.0.0
     */
    protected $nodes = [];

    /**
     * Directed
     *
     * @var bool
     * @since 1.0.0
     */
    protected $isDirected = false;

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
     * Graph has node
     *
     * @param mixed $key Node key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasNode($key) : bool
    {
        return isset($this->nodes[$key]);
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
     * Is directed graph
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
     * Set graph directed
     *
     * @param bool $directed Is directed?
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDirected(bool $directed) : void
    {
        $this->isDirected = $directed;
    }

    /**
     * Get graph/edge costs
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function getCost()
    {
        $edges = $this->getEdges();
        $costs = 0;

        foreach ($edges as $edge) {
            $costs += $edge->getWeight();
        }

        return $costs;
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
     * @return Graph
     *
     * @since 1.0.0
     */
    public function getKruskalMinimalSpanningTree() : self
    {
        $graph = new self();
        $edges = $this->getEdges();

        \usort($edges, Edge::class . '::compare');

        foreach ($edges as $edge) {
            if ($graph->hasNode($edge->getNode1()->getId())
                && $graph->hasNode($edge->getNode2()->getId())
            ) {
                continue;
            }

            /** @var Node $node1 */
            $node1 = $graph->hasNode($edge->getNode1()->getId()) ? $graph->getNode($edge->getNode1()->getId()) : clone $edge->getNode1();
            /** @var Node $node2 */
            $node2 = $graph->hasNode($edge->getNode2()->getId()) ? $graph->getNode($edge->getNode2()->getId()) : clone $edge->getNode2();

            $node1->setNodeRelative($node2);

            if (!$graph->hasNode($edge->getNode1()->getId())) {
                $graph->setNode($node1);
            }

            if (!$graph->hasNode($edge->getNode2()->getId())) {
                $graph->setNode($node2);
            }
        }

        return $graph;
    }

    /**
     * Has cycle in graph.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasCycle() : bool
    {
        return $this->isDirected ? $this->hasCycleDirected() : $this->hasCycleUndirected();
    }

    /**
     * Has cycle in directed graph.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function hasCycleDirected() : bool
    {
        $visited  = [];
        $recStack = [];

        foreach ($this->nodes as $node) {
            if ($this->hasDirectedCyclicUtil($node->getId(), $visited, $recStack)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Has cycle in directed graph.
     *
     * @param string $node    Node name
     * @param array  $visited Visited nodes
     * @param array  $stack   Recursion stack
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function hasDirectedCyclicUtil(string $node, array &$visited, array &$stack) : bool
    {
        if (isset($visited[$node]) && $visited[$node]) {
            $stack[$node] = false;

            return false;
        }

        $visited[$node] = true;
        $stack[$node]   = true;

        $neighbors = $this->nodes[$node]->getNeighbors();
        foreach ($neighbors as $neighbor) {
            if ((!isset($visited[$neighbor->getId()]) || !$visited[$neighbor->getId()])
                && $this->hasDirectedCyclicUtil($neighbor->getId(), $visited, $stack)
            ) {
                return true;
            } elseif (isset($stack[$neighbor->getId()]) && $stack[$neighbor->getId()]) {
                return true;
            }
        }

        $stack[$node] = false;

        return false;
    }

    /**
     * Has cycle in undirected graph.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function hasCycleUndirected() : bool
    {
        $visited = [];

        foreach ($this->nodes as $node) {
            if (!isset($visited[$node->getId()]) && $this->hasUndirectedCyclicUtil($node->getId(), $visited, null)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Has cycle in undirected graph.
     *
     * @param string      $node    Node name
     * @param array       $visited Visited nodes
     * @param null|string $parent  Parent node
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function hasUndirectedCyclicUtil(string $node, array &$visited, ?string $parent) : bool
    {
        $visited[$node] = true;

        $neighbors = $this->nodes[$node]->getNeighbors();
        foreach ($neighbors as $neighbor) {
            if (!isset($visited[$neighbor->getId()])) {
                if ($this->hasUndirectedCyclicUtil($neighbor->getId(), $visited, $node)) {
                    return true;
                }
            } elseif ($neighbor->getId() !== $parent) {
                return true;
            }
        }

        return false;
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
        return true;
    }
}
