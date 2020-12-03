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
 * Node class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Node
{
    /**
     * Node id.
     *
     * @var string
     * @since 1.0.0
     */
    private string $id;

    /**
     * Node data.
     *
     * @var mixed
     * @since 1.0.0
     */
    private $data = null;

    /**
     * Edges.
     *
     * @var Edge[]
     * @since 1.0.0
     */
    protected array $edges = [];

    /**
     * Constructor.
     *
     * @param string $id   Node id
     * @param mixed  $data Node data
     *
     * @since 1.0.0
     */
    public function __construct(string $id, mixed $data = null)
    {
        $this->id   = $id;
        $this->data = $data;
    }

    /**
     * Get node id.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Get data.
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getData() : mixed
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param mixed $data Node data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setData(mixed $data) : void
    {
        $this->data = $data;
    }

    /**
     * Compare with other node.
     *
     * @param Node $node Node
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isEqual(self $node) : bool
    {
        return $this->id === $node->getId() && $this->data === $node->getData();
    }

    /**
     * Set a relative undirected node.
     *
     * @param Node $node       Graph node
     * @param int  $key        Index for absolute position
     * @param bool $isDirected Is directed
     *
     * @return Edge
     *
     * @since 1.0.0
     */
    public function setNodeRelative(self $node, int $key = null, bool $isDirected = false) : Edge
    {
        $edge = new Edge($this, $node, 0.0, $isDirected);
        $this->setEdge($edge, $key);

        if (!$edge->isDirected()) {
            $node->setEdge($edge);
        }

        return $edge;
    }

    /**
     * Add edge to node.
     *
     * @param Edge $edge Graph edge
     * @param int  $key  Index for absolute position
     *
     * @return Node
     *
     * @since 1.0.0
     */
    public function setEdge(Edge $edge, int $key = null) : self
    {
        if ($key === null) {
            $this->edges[] = $edge;
        } else {
            $this->edges[$key] = $edge;
        }

        return $this;
    }

    /**
     * Get graph edge.
     *
     * @param int $key Edge key
     *
     * @return null|Edge
     *
     * @since 1.0.0
     */
    public function getEdge(int $key) : ?Edge
    {
        return $this->edges[$key] ?? null;
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
        return $this->edges;
    }

    /**
     * Removes all edges / neighbours from the node
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function removeEdges() : void
    {
        $this->edges = [];
    }

    /**
     * Get all node neighbors.
     *
     * @return Node[]
     *
     * @since 1.0.0
     */
    public function getNeighbors() : array
    {
        $neighbors = [];

        foreach ($this->edges as $edge) {
            $nodes = $edge->getNodes();

            if ($nodes[0] !== null && !$this->isEqual($nodes[0])) {
                $neighbors[] = $nodes[0];
            } else {
                $neighbors[] = $nodes[1];
            }
        }

        return $neighbors;
    }
}
