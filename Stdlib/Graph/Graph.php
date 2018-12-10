<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Stdlib\Graph
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Graph;

/**
 * Tree class.
 *
 * @package    phpOMS\Stdlib\Graph
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Graph
{
    /**
     * Nodes.
     *
     * @var array
     * @since 1.0.0
     */
    protected $nodes = [];

    /**
     * Edges.
     *
     * @var array
     * @since 1.0.0
     */
    protected $edges = [];

    /**
     * Add node to graph.
     *
     * @param Node $node Graph node
     *
     * @return Graph
     *
     * @since  1.0.0
     */
    public function addNode(Node $node) : self
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Add node to graph.
     *
     * @param Node $relative Relative graph node
     * @param Node $node     Graph node
     *
     * @return Graph
     *
     * @since  1.0.0
     */
    public function addNodeRelative(Node $relative, Node $node) : self
    {
        $this->edges[] = new Edge($relative, $node);

        return $this;
    }

    /**
     * Set node in graph.
     *
     * @param mixed $key  Key of node
     * @param Node  $node Graph node
     *
     * @return Graph
     *
     * @since  1.0.0
     */
    public function setNode($key, Node $node) : self
    {
        $this->nodes[$key] = $node;

        return $this;
    }

    /**
     * Add edge to graph.
     *
     * @param Edge $edge Graph edge
     *
     * @return Graph
     *
     * @since  1.0.0
     */
    public function addEdge(Edge $edge) : self
    {
        $this->edges[] = $edge;

        return $this;
    }

    /**
     * Set edge in graph.
     *
     * @param mixed $key  Edge key
     * @param Edge  $edge Edge to set
     *
     * @return Graph
     *
     * @since  1.0.0
     */
    public function setEdge($key, Edge $edge)  /* : void */
    {
        $this->edges[$key] = $edge;

        return $this;
    }

    /**
     * Get graph node
     *
     * @param mixed $key Node key
     *
     * @return null|Node
     *
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getNodes() : array
    {
        return $this->nodes;
    }

    /**
     * Get graph edge.
     *
     * @param mixed $key Edge key
     *
     * @return null|Edge
     *
     * @since  1.0.0
     */
    public function getEdge($key) : ?Edge
    {
        return $this->edges[$key] ?? null;
    }

    /**
     * Get graph edges
     *
     * @return Node[]
     *
     * @since  1.0.0
     */
    public function getEdges() : array
    {
        return $this->edges;
    }

    /**
     * Get all edges of a node
     *
     * @param mixed $node Node
     *
     * @return Edge[]
     *
     * @since  1.0.0
     */
    public function getEdgesOfNode($node) : array
    {
        if (!($node instanceof Node)) {
            $node = $this->getNode($node);
        }

        $edges = [];
        foreach ($this->edges as $edge) {
            $nodes = $edge->getNodes();

            if ($nodes[0] === $node || $nodes[1] === $node) {
                $edges[] = $edge;
            }
        }

        return $edges;
    }

    /**
     * Get all node neighbors.
     *
     * @param mixed $node Graph node
     *
     * @return Node[]
     *
     * @since  1.0.0
     */
    public function getNeighbors($node) : array
    {
        if (!($node instanceof Node)) {
            $node = $this->getNode($node);
        }

        $edges     = $this->getEdgesOfNode($node);
        $neighbors = [];

        foreach ($edges as $edge) {
            $nodes = $edge->getNodes();

            if ($nodes[0] !== $node && $nodes[0] !== null) {
                $neighbors[] = $nodes[0];
            } elseif ($nodes[1] !== $node && $nodes[0] !== null) {
                $neighbors[] = $nodes[1];
            }
        }

        return $neighbors;
    }

    /**
     * Get graph dimension.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getDimension() : int
    {
        // todo: implement
        return 0;
    }

    /**
     * Get all bridges.
     *
     * @return Edge[]
     *
     * @since  1.0.0
     */
    public function getBridges() : array
    {
        // todo: implement
        return [];
    }

    /**
     * Get minimal spanning tree using Kruskal's algorithm.
     *
     * @return Tree
     *
     * @since  1.0.0
     */
    public function getKruskalMinimalSpanningTree() : Tree
    {
        // todo: implement
        return new Tree();
    }

    /**
     * Get minimal spanning tree using Prim's algorithm
     *
     * @return Tree
     *
     * @since  1.0.0
     */
    public function getPrimMinimalSpanningTree() : Tree
    {
        // todo: implement
        return new Tree();
    }

    /**
     * Get circles in graph.
     *
     * @return array
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getSize() : int
    {
        return \count($this->edges);
    }

    /**
     * Get diameter of graph.
     *
     * The diameter of a graph is the longest shortest path between two nodes.
     *
     * @return int
     *
     * @since  1.0.0
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

    public function getGirth() : int
    {
        return 0;
    }

    public function getCircuitRank() : int
    {
        return 0;
    }

    public function getNodeConnectivity() : int
    {
        return 0;
    }

    public function getEdgeConnectivity() : int
    {
        return 0;
    }

    public function isConnected() : bool
    {
        // todo: implement
        return true;
    }

    public function getUnconnected() : array
    {
        // todo: implement
        // get all unconnected sub graphs

        return [];
    }

    public function isBipartite() : bool
    {
        // todo: implement
        return true;
    }

    public function isTriangleFree() : bool
    {
        // todo: implement
        return true;
    }

    public function isCircleFree() : bool
    {
        // todo: implement
        return true;
    }
}
