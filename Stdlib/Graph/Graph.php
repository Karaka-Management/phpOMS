<?php
/**
 * Karaka
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
 * Graph class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Graph
{
    /**
     * Nodes.
     *
     * @var Node[]
     * @since 1.0.0
     */
    protected array $nodes = [];

    /**
     * Directed
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $isDirected = false;

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
     * @param int|string $key Node key
     *
     * @return null|Node
     *
     * @since 1.0.0
     */
    public function getNode(int | string $key) : ?Node
    {
        return $this->nodes[$key] ?? null;
    }

    /**
     * Define a relationship between two nodes
     *
     * @param Node $node1    First node
     * @param Node $node2    Second node
     * @param bool $directed Is directed
     *
     * @return Edge
     *
     * @since 1.0.0
     */
    public function setNodeRelative(Node $node1, Node $node2, bool $directed = false) : Edge
    {
        return $node1->setNodeRelative($node2, null, $directed);
    }

    /**
     * Graph has node
     *
     * @param int|string $key Node key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasNode(int | string $key) : bool
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
        foreach ($this->nodes as $node) {
            $edges = $node->getEdges();
            foreach ($edges as $edge) {
                if ($edge->isDirected) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get graph/edge costs
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function getCost() : int | float
    {
        $edges = $this->getEdges();
        $costs = 0;

        foreach ($edges as $edge) {
            $costs += $edge->weight;
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
                if (!isset($edges[$edge->node1->getId() . ':' . $edge->node2->getId()])
                    && !isset($edges[$edge->node2->getId() . ':' . $edge->node1->getId()])
                ) {
                    $edges[$edge->node1->getId() . ':' . $edge->node2->getId()] = $edge;
                }
            }
        }

        return $edges;
    }

    /**
     * Get the edge between two nodes
     *
     * @param string $id1 Node id
     * @param string $id2 Node id
     *
     * @return null|Edge
     *
     * @since 1.0.0
     */
    public function getEdge(string $id1, string $id2) : ?Edge
    {
        foreach ($this->nodes as $node) {
            if ($node->getId() !== $id1 && $node->getId() !== $id2) {
                continue;
            }

            $nodeEdges = $node->getEdges();

            foreach ($nodeEdges as $edge) {
                if (($edge->node1->getId() === $id1 || $edge->node1->getId() === $id2)
                    && ($edge->node2->getId() === $id1 || $edge->node2->getId() === $id2)
                ) {
                    return $edge;
                }
            }
        }

        return null;
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
    ) : void
    {
        $id           = $node->getId();
        $visited[$id] = true;

        ++$index;

        $discovery[$id] = $index;
        $low[$id]       = $index;

        $edges = $this->nodes[$id]->getEdges();
        foreach ($edges as $edge) {
            $neighbor = !$edge->node1->isEqual($node) ? $edge->node1 : $edge->node2;

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
            if ($graph->hasNode($edge->node1->getId())
                && $graph->hasNode($edge->node2->getId())
            ) {
                continue;
            }

            /** @var Node $node1 */
            $node1 = $graph->hasNode($edge->node1->getId())
                ? $graph->getNode($edge->node1->getId())
                : clone $edge->node1;

            /** @var Node $node2 */
            $node2 = $graph->hasNode($edge->node2->getId())
                ? $graph->getNode($edge->node2->getId())
                : clone $edge->node2;

            if (!$graph->hasNode($node1->getId())) {
                $node1->removeEdges();
                $graph->setNode($node1);
            }

            if (!$graph->hasNode($node2->getId())) {
                $node2->removeEdges();
                $graph->setNode($node2);
            }

            $node1->setNodeRelative($node2)
                ->weight = $this->getEdge(
                        $node1->getId(),
                        $node2->getId()
                    )?->weight ?? 0.0;
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
        $distances = [];
        foreach ($this->nodes as $k) {
            foreach ($this->nodes as $i) {
                foreach ($this->nodes as $j) {
                    if (!isset($distances[$i->getId()])) {
                        $distances[$i->getId()] = [];

                        foreach ($this->nodes as $node) {
                            if ($i->isEqual($node)) {
                                $distances[$i->getId()][$node->getId()] = 0;
                            } else {
                                $edge = $i->getEdgeByNeighbor($node);

                                $distances[$i->getId()][$node->getId()] = $edge === null
                                    ? null
                                    : $edge->weight;
                            }
                        }
                    }

                    if (!isset($distances[$k->getId()])) {
                        $distances[$k->getId()] = [];

                        foreach ($this->nodes as $node) {
                            if ($k->isEqual($node)) {
                                $distances[$k->getId()][$node->getId()] = 0;
                            } else {
                                $edge = $k->getEdgeByNeighbor($node);

                                $distances[$k->getId()][$node->getId()] = $edge === null
                                    ? null
                                    : $edge->weight;
                            }
                        }
                    }

                    if ($distances[$i->getId()][$k->getId()] !== null
                        && $distances[$k->getId()][$j->getId()] !== null
                        && $distances[$i->getId()][$j->getId()] > $distances[$i->getId()][$k->getId()] + $distances[$k->getId()][$j->getId()]
                    ) {
                        $distances[$i->getId()][$j->getId()] = $distances[$i->getId()][$k->getId()] + $distances[$k->getId()][$j->getId()];
                    }
                }
            }
        }

        return $distances;
    }

    /**
     * Perform depth first traversal
     *
     * @param Node  $node1   Graph node
     * @param Node  $node2   Graph node
     * @param array $visited Is the node already visited
     * @param array $path    Array of nodes (a path through the graph = connected nodes)
     * @param array $paths   Array of paths (all identified paths)
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function pathBetweenNodesDfs(
        Node $node1,
        Node $node2 = null,
        array &$visited,
        array &$path,
        array &$paths
    ) : void
    {
        $visited[$node1->getId()] = true;

        if ($node2 !== null && $node1->isEqual($node2)) {
            $paths[] = $path;
        }

        $neighbors = $node1->getNeighbors();
        foreach ($neighbors as $neighbor) {
            if (!isset($visited[$neighbor->getId()]) || !$visited[$neighbor->getId()]) {
                $path[] = $neighbor;
                $this->pathBetweenNodesDfs($neighbor, $node2, $visited, $path, $paths);
                \array_pop($path);
            }
        }

        $visited[$node1->getId()] = false;
    }

    /**
     * Find all reachable nodes with depth first traversal (iterative)
     *
     * Includes the node itself
     *
     * @param int|string|Node $node1 Graph node
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function findAllReachableNodesDfs(int | string | Node $node1) : array
    {
        $nodes = [];

        if (!($node1 instanceof Node)) {
            $node1 = $this->getNode($node1);
        }

        if ($node1 === null) {
            return $nodes;
        }

        $visited = [];
        $stack   = [$node1];

        while (!empty($stack)) {
            $cNode = \array_pop($stack);
            if (isset($visited[$cNode->getId()]) && $visited[$cNode->getId()] === true) {
                continue;
            }

            $nodes[]                  = $cNode;
            $visited[$cNode->getId()] = true;

            $neighbors = $cNode->getNeighbors();
            foreach ($neighbors as $neighbor) {
                if (!isset($visited[$neighbor->getId()]) || $visited[$neighbor->getId()] === false) {
                    $stack[] = $neighbor;
                }
            }
        }

        return $nodes;
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
        $longestPath = [];
        $visited     = [];
        $path        = [];

        foreach ($this->nodes as $node) {
            $visited[$node->getId()] = false;
        }

        foreach ($this->nodes as $node) {
            $this->longestPathDfs($node, $visited, $path, $longestPath);
        }

        return $longestPath;
    }

    /**
     * Perform depth first traversal
     *
     * @param Node  $node        Graph node
     * @param array $visited     Is the node already visited
     * @param array $path        Array of nodes (a path through the graph = connected nodes)
     * @param array $longestPath Array of nodes (longest path through the graph = connected nodes)
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function longestPathDfs(Node $node, &$visited, &$path, &$longestPath)
    {
        $visited[$node->getId()] = true;
        $path[]                  = $node;

        $edges = $node->getEdges();
        foreach ($edges as $edge) {
            $adj = $edge->node1->isEqual($node)
                    ? $edge->node2
                    : $edge->node1;

            if (!$visited[$adj->getId()]) {
                $this->longestPathDfs($adj, $visited, $path, $longestPath);
            }
        }

        if (\count($path) > \count($longestPath)) {
            $longestPath = $path;
        }

        \array_pop($path);
        $visited[$node->getId()] = false;
    }

    /**
     * Get all paths between two nodes
     * Inclides end node, but not start node in the paths
     *
     * @param int|string|Node $node1 Graph node
     * @param int|string|Node $node2 Graph node
     *
     * @return array<int, Node[]>
     *
     * @since 1.0.0
     */
    public function getAllPathsBetweenNodes(int | string | Node $node1, int | string | Node $node2) : array
    {
        if (!($node1 instanceof Node)) {
            $node1 = $this->getNode($node1);
        }

        if (!($node2 instanceof Node)) {
            $node2 = $this->getNode($node2);
        }

        if ($node1 === null || $node2 === null) {
            return [];
        }

        $path    = [];
        $paths   = [];
        $visited = [];

        $this->pathBetweenNodesDfs($node1, $node2, $visited, $path, $paths);

        return $paths;
    }

    /**
     * Count possible paths between two nodes.
     *
     * @param int|string|Node $node1 Graph node
     * @param int|string|Node $node2 Graph node
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function countAllPathsBetweenNodes(int | string | Node $node1, int | string | Node $node2) : int
    {
        return \count($this->getAllPathsBetweenNodes($node1, $node2));
    }

    /**
     * Get longest path between two nodes.
     *
     * @param int|string|Node $node1 Graph node
     * @param int|string|Node $node2 Graph node
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function longestPathBetweenNodes(int | string | Node $node1, int | string | Node $node2) : array
    {
        if (!($node1 instanceof Node)) {
            $node1 = $this->getNode($node1);
        }

        if (!($node2 instanceof Node)) {
            $node2 = $this->getNode($node2);
        }

        if ($node1 === null || $node2 === null) {
            return [];
        }

        $paths = $this->getAllPathsBetweenNodes($node1, $node2);

        if (empty($paths)) {
            return [];
        }

        $mostNodes      = 0;
        $mostNodesCount = 0;
        foreach ($paths as $key => $path) {
            $edges[$key] = 0;
            $nodeCount   = 0;
            foreach ($path as $node) {
                if ($node1->getEdgeByNeighbor($node) === null) {
                    continue;
                }

                $edges[$key] += $node1->getEdgeByNeighbor($node)->weight;
                ++$nodeCount;
            }

            if ($nodeCount > $mostNodesCount) {
                $mostNodesCount = $nodeCount;
                $mostNodes      = $key;
            }
        }

        if (\array_sum($edges) > 0.0) {
            \arsort($edges);

            return $paths[\array_key_first($edges)];
        }

        return $paths[$mostNodes];
    }

    /**
     * Get shortest path between two nodes.
     *
     * @param int|string|Node $node1 Graph node
     * @param int|string|Node $node2 Graph node
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function shortestPathBetweenNodes(int | string | Node $node1, int | string | Node $node2) : array
    {
        if (!($node1 instanceof Node)) {
            $node1 = $this->getNode($node1);
        }

        if (!($node2 instanceof Node)) {
            $node2 = $this->getNode($node2);
        }

        if ($node1 === null || $node2 === null) {
            return [];
        }

        $paths = $this->getAllPathsBetweenNodes($node1, $node2);

        $edges           = [];
        $leastNodes      = 0;
        $leastNodesCount = \PHP_INT_MAX;
        foreach ($paths as $key => $path) {
            $edges[$key] = 0;
            $nodeCount   = 0;
            foreach ($path as $node) {
                if ($node1->getEdgeByNeighbor($node) === null) {
                    continue;
                }

                $edges[$key] += $node1->getEdgeByNeighbor($node)->weight;
                ++$nodeCount;
            }

            if ($nodeCount < $leastNodesCount && $nodeCount > 0) {
                $leastNodesCount = $nodeCount;
                $leastNodes      = $key;
            }
        }

        if (\array_sum($edges) > 0.0) {
            \asort($edges);

            return $paths[\array_key_first($edges)];
        }

        return $paths[$leastNodes];
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
        $paths = $this->getFloydWarshallShortestPath();
        $count = [];

        if (empty($paths)) {
            return 0;
        }

        foreach ($paths as $path) {
            $count[] = \count($path);
        }

        return \max($count);
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
        $girth = \PHP_INT_MAX;

        foreach ($this->nodes as $i) {
            $stack = [$i];

            foreach ($this->nodes as $node) {
                $distances[$node->getId()] = \PHP_INT_MAX;
            }

            $distances[$i->getId()] = 0;

            while (!empty($stack)) {
                $current = \array_shift($stack);

                foreach ($this->nodes as $j) {
                    // Has neighbour
                    if ($this->nodes[$current->getId()]->hasNeighbor($j)) {
                        if ($distances[$j->getId()] === \PHP_INT_MAX) {
                            $distances[$j->getId()] = $distances[$current->getId()] + 1;
                            $stack[]                = $j;
                        } elseif (!$j->isEqual($i) && !$j->isEqual($current)
                            && $distances[$j->getId()] >= $distances[$current->getId()]
                        ) {
                            $girth = \min(
                                $girth,
                                $distances[$current->getId()] + $distances[$j->getId()] + 1
                            );
                        }
                    }
                }
            }
        }

        return $girth;
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
        $adjMatrix = [];
        foreach ($this->nodes as $node) {
            $adjMatrix[$node->getId()] = $node->getEdges();
        }

        $rank = 0;
        foreach ($this->nodes as $i) {
            $visited = [];
            foreach ($this->nodes as $node) {
                $visited[$node->getId()] = false;
            }

            if ($this->cycleDfs($adjMatrix, $i, null, $visited)) {
                ++$rank;
            }
        }

        return $rank;
    }

    /**
     * Get the graph circuit rank
     *
     * @param array $adjMatrix Adjacency matrix
     * @param Node  $current   Current node
     * @param Node  $previous  Previous node
     * @param array $visited   Visited nodes
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private function cycleDfs(array $adjMatrix, Node $current, ?Node $previous, &$visited) : bool
    {
        $visited[$current->getId()] = true;
        foreach ($adjMatrix[$current->getId()] as $edge) {
            if ($edge->isDirected) {
                if ($edge->node2->isEqual(($current))) {
                    continue;
                }

                $next = $edge->node2;
            } else {
                $next = $edge->node1->isEqual($current)
                    ? $edge->node2
                    : $edge->node1;
            }

            if ($previous !== null && $next->isEqual($previous)) {
                continue;
            }

            if ($visited[$next->getId()]) {
                return true;
            }

            if ($this->cycleDfs($adjMatrix, $next, $current, $visited)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is the graph connected?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isConnected(int | string | Node $node1 = null, int | string | Node $node2 = null) : bool
    {
        if (empty($this->nodes)) {
            return true;
        }

        if ($node1 === null || $node2 === null) {
            $visited = [];
            foreach ($this->nodes as $node) {
                $visited[] = false;
            }

            $node                    = \reset($this->nodes);
            $visited[$node->getId()] = true;
            $stack                   = [$node];

            while (!empty($stack)) {
                $node = \array_pop($stack);

                $edges = $node->getEdges();
                foreach ($edges as $edge) {
                    if ($edge->isDirected) {
                        if ($edge->node2->isEqual(($node))) {
                            continue;
                        }

                        $adj = $edge->node2;
                    } else {
                        $adj = $edge->node1->isEqual($node)
                            ? $edge->node2
                            : $edge->node1;
                    }

                    if (!$visited[$adj->getId()]) {
                        $visited[$adj->getId()] = true;

                        $stack[] = $adj;
                    }
                }
            }

            return !\in_array(false, $visited);
        }

        $visited = [];
        foreach ($this->nodes as $node) {
            $visited[$node->getId()] = false;
        }

        $stack = [$node1];
        while (!empty($stack)) {
            $node = \array_shift($stack);

            if ($node->isEqual($node2)) {
                return true;
            }

            if (!$visited[$node->getId()]) {
                $visited[$node->getId()] = true;

                $edges = $node->getEdges();
                foreach ($edges as $edge) {
                    if ($edge->isDirected) {
                        if ($edge->node2->isEqual(($node))) {
                            continue;
                        }

                        $stack[] = $edge->node2;
                    } else {
                        $stack = $edge->node1->isEqual($node)
                            ? $edge->node2
                            : $edge->node1;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Is the graph strongly connected?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isStronglyConnected()
    {
        if (empty($this->nodes)) {
            return true;
        }

        $visited = [];
        foreach ($this->nodes as $node) {
            $visited[] = false;
        }

        $node                    = \reset($this->nodes);
        $visited[$node->getId()] = true;
        $stack                   = [$node];

        while (!empty($stack)) {
            $node = \array_pop($stack);

            $edges = $node->getEdges();
            foreach ($edges as $edge) {
                if ($edge->isDirected) {
                    if ($edge->node2->isEqual(($node))) {
                        continue;
                    }

                    $adj = $edge->node2;
                } else {
                    $adj = $edge->node1->isEqual($node)
                        ? $edge->node2
                        : $edge->node1;
                }

                if (!$visited[$adj->getId()]) {
                    $visited[$adj->getId()] = true;

                    $stack[] = $adj;
                }
            }
        }

        if (\in_array(false, $visited)) {
            return false;
        }

        if (!$this->isDirected) {
            return true;
        }

        // Test connectivity in reverse
        $visited = [];
        foreach ($this->nodes as $node) {
            $visited[] = false;
        }

        $node                    = \reset($this->nodes);
        $visited[$node->getId()] = true;
        $stack                   = [$node];

        while (!empty($stack)) {
            $node = \array_pop($stack);

            $edges = $node->getEdges();
            foreach ($edges as $edge) {
                if ($edge->isDirected) {
                    if (!$edge->node2->isEqual(($node))) {
                        continue;
                    }

                    $adj = $edge->node1;
                } else {
                    $adj = $edge->node1->isEqual($node)
                        ? $edge->node2
                        : $edge->node1;
                }

                if (!$visited[$adj->getId()]) {
                    $visited[$adj->getId()] = true;

                    $stack[] = $adj;
                }
            }
        }

        return !\in_array(false, $visited);
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
        foreach ($this->nodes as $node) {
            $colors[$node->getId()] = 0;
        }

        $node1 = \reset($this->nodes);
        $colors[$node1->getId()] = 1;

        $stack = [$node1];

        while (!empty($stack)) {
            $node = \array_pop($stack);

            $edges = $node->getEdges();
            foreach ($edges as $edge) {
                $adj = $edge->node1->isEqual($node)
                    ? $edge->node2
                    : $edge->node1;

                if ($colors[$adj->getId()] === -1) {
                    $colors[$adj->getId()] = 1 - $colors[$node->getId()];
                    $stack[]               = $adj;
                } elseif ($colors[$adj->getId()] === $colors[$node->getId()]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the graph triangle?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasTriangles() : bool
    {
        foreach ($this->nodes as $i) {
            foreach ($this->nodes as $j) {
                if ($this->getEdge($i->getId(), $j->getId()) !== null) {
                    foreach ($this->nodes as $k) {
                        if ($this->getEdge($j->getId(), $k->getId()) && $this->getEdge($k->getId(), $i->getId())) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
