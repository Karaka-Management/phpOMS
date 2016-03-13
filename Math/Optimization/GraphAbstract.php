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
namespace phpOMS\Math\Optimization;

class GraphAbstract {
    private $vertices = [];

    private $edges = null;

    public function __construct() 
    {
        $this->edges = new MultiMap(KeyType::STRICT, OrderType::LOOSE);
    }

    public function addVertice(VerticeInterface $Vertice) : bool
    {
        if(!isset($this->vertices[$Vertice->getId()])) {
            $this->vertices[$Vertice->getId()] = $Vertice;

            return true;
        }

        return false;
    }

    public function removeVertice($id) : bool
    {
        if(isset($this->vertices[$id])) {
            unset($this->vertices[$id]);

            return true;
        }

        return false;
    }

    public function getVertice($id) : VerticeInterface
    {
        return $this->vertices[$id] ?? new NullVertice();
    }

    public function getEdge($a, $b) : EdgeInterface
    {
        return $this->edges->get($a, $b) ?? new NullEdge();
    }

    public function countVertices() : int 
    {
        return count($this->vertices);
    }

    public function countEdges() : int
    {
        return count($this->edges);
    }
}