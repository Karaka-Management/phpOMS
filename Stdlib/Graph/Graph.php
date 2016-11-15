<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Stdlib\Graph;

/**
 * Tree class.
 *
 * @category   Framework
 * @package    phpOMS\Datatypes
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Graph
{
    protected $nodes = [];

    protected $edges = [];

	public function addNode(Node $node) 
    {
		$this->nodes[] = $node;

		return $this;
	}

	public function setNode($key, Node $node) 
    {
		$this->nodes[$key] = $node;

		return $this;
	}

    public function addEdge(Edge $edge) 
    {
		$this->edges[] = $edge;

		return $this;
	}

	public function setEdge($key, Edge $edge) 
    {
		$this->edges[$key] = $edge;

		return $this;
	}

    public function getNode($key) : Node 
	{
		return $this->nodes[$key];
	}

    public function getEdge($key) : Edge 
	{
		return $this->edges[$key];
	}

    public function getEdgesOfNode($node) : array 
    {
        if(!($node instanceof Node)) {
            $node = $this->getNode($node);
        }

        $edges = [];
        foreach($this->edges as $edge) {
            $nodes = $edge->getNodes();

            if($nodes[0] === $node || $nodes[1] === $node) {
                $edges[] = $edge;
            }
        }

        return $edges;
    }

    public function getNeighbors($node) : array 
    {
        if(!($node instanceof Node)) {
            $node = $this->getNode($node);
        }

        $edges = $this->getEdgesOfNode($node);
        $neighbors = [];

        foreach($edges as $edge) {
            $nodes = $edge->getNodes();

            if($nodes[0] !== $node && $nodes[0] !== null) {
                $neighbors[] = $nodes[0];
            } elseif($nodes[1] !== $node && $nodes[0] !== null) {
                $neighbors[] = $nodes[1];
            }
        }

        return $neighbors;
    }

    public function getDimension() : int 
    {
        return 0;
    }

    public function getBridges() : array
    {
        return [];
    }

    public function getKruskalMinimalSpanningTree() : Tree 
    {
        return new Tree();
    }

    public function getPrimMinimalSpanningTree() : Tree 
    {
        return new Tree();
    }

    public function getCircle() : array 
    {

    }

    public function getFloydWarshallShortestPath() : array 
    {

    }

    public function getDijkstraShortestPath() : array
    {

    }

    public function depthFirstTraversal() : array 
    {

    }

    public function breadthFirstTraversal() : array 
    {

    }

    public function longestPath() : array
    {

    }

    public function longestPathBetweenNodes() : array
    {

    }

    public function getOrder() : int
    {
        return count($this->nodes);
    }

    public function getSize() : int
    {
        return count($this->edges);
    }

    public function getDiameter() : int
    {
        $diameter = 0;

        foreach($this->nodes as $node1) {
            foreach($this->nodes as $node2) {
                if($node1 === $node2) {
                    continue;
                }

                $diameter = max($diameter, $this->getFloydWarshallShortestPath($node1, $node2));
            }
        }

        return $diameter;
    }

    public function getGirth() : int
    {

    }

    public function getCircuitRank() : int
    {

    }

    public function getNodeConnectivity() : int
    {

    }

    public function getEdgeConnectivity() : int
    {

    }

    public function isConnected() : bool
    {
        return true;
    }

    public function getUnconnected() : array
    {
        // get all unconnected sub graphs
    }

    public function isBipartite() : bool
    {
        return true;
    }

    public function isTriangleFree() : bool
    {
        return true;
    }

    public function isCircleFree() : bool
    {
        return true;
    }
}