<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Math\Optimization\Graph;

use phpOMS\Stdlib\Map\KeyType;
use phpOMS\Stdlib\Map\MultiMap;
use phpOMS\Stdlib\Map\OrderType;

/**
 * Graph class
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Graph
{
    /**
     * Vertices.
     *
     * @var VerticeInterface[]
     * @since 1.0.0
     */
    private $vertices = [];

    /**
     * Edge.
     *
     * @var EdgeInterface[]
     * @since 1.0.0
     */
    private $edges = null;

    /**
     * Constructor
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->edges = new MultiMap(KeyType::MULTIPLE, OrderType::LOOSE);
    }

    /**
     * Add vertice to graph.
     *
     * @param VerticeInterface $vertice Vertice
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addVertice(VerticeInterface $vertice) : bool
    {
        if (!isset($this->vertices[$vertice->getId()])) {
            $this->vertices[$vertice->getId()] = $vertice;

            return true;
        }

        return false;
    }

    /**
     * Add edge to graph.
     *
     * @param EdgeInterface $edge Edge
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addEdge(EdgeInterface $edge) : bool
    {
        if (!isset($this->edges[$edge->getId()])) {
            $this->edges[$edge->getId()] = $edge;

            return true;
        }

        return false;
    }

    /**
     * Remove vertice from graph.
     *
     * @param mixed $id Id of vertice to remove
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeVertice($id) : bool
    {
        if (isset($this->vertices[$id])) {
            unset($this->vertices[$id]);

            return true;
        }

        return false;
    }

    /**
     * Remove edge by nodes from graph.
     *
     * @param mixed $a First node of edge
     * @param mixed $b Second node of edge
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeEdge($a, $b) : bool
    {
        return $this->edges->remove([$a, $b]);
    }

    /**
     * Remove edge from graph.
     *
     * @param mixed $id Id of edge to remove
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeEdgeById($id) : bool
    {
        if (isset($this->edges[$id])) {
            unset($this->edges[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get vertice.
     *
     * @param mixed $id Id of vertice
     *
     * @return VerticeInterface
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getVertice($id) : VerticeInterface
    {
        return $this->vertices[$id] ?? new NullVertice();
    }

    /**
     * Get edge by nodes.
     *
     * Order of nodes is irrelevant
     *
     * @param mixed $a First node of edge
     * @param mixed $b Second node of edge
     *
     * @return EdgeInterface
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getEdge($a, $b) : EdgeInterface
    {
        return $this->edges->get([$a, $b]) ?? new NullEdge();
    }

    /**
     * Get edge by id.
     *
     * @param int $id Edge id
     *
     * @return EdgeInterface
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getEdgeById(int $id) : EdgeInterface
    {
        return $this->edges->get($id) ?? new NullEdge();
    }

    /**
     * Count vertices.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function countVertices() : int
    {
        return count($this->vertices);
    }

    /**
     * Count edges.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function countEdges() : int
    {
        return count($this->edges);
    }
}