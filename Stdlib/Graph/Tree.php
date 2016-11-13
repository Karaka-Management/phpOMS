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
namespace phpOMS\Datatypes;

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
class Tree extends Graph
{
	private $root = null;

	public function __construct()
	{
		$root = new Node();
		$this->addNode($root);
	}

	public function addNode(Node $base, Node $node) 
	{
		parent::addNode($node);
		parent::addEdge(new Edge($base, $node));
	}

	public function getMaxDepth(Node $node = null) : int 
	{
		$currentNode = $node ?? $this->root;

		if(!isset($currentNode)) {
			return 0;
		}

		$depth = 1;
		$neighbors = $this->getNeighbors($currentNode);

		foreach($neighbors as $neighbor) {
			$depth = max($depth, $depth + $this->getMaxDepth($neighbor));
		}

		return $depth;
	}

	public function getMinDepth(Node $node = null) : int
	{
		$currentNode = $node ?? $this->root;

		if(!isset($currentNode)) {
			return 0;
		}

		$depth = [];
		$neighbors = $this->getNeighbors($currentNode);

		foreach($neighbors as $neighbor) {
			$depth[] = $this->getMaxDepth($neighbor);
		}

		$depth = empty($depth) ? 0 : $depth;

		return min($depth) + 1;
	}

	public function levelOrder(\Closure $callback)
	{
		$depth = $this->getMaxDepth();

		for($i = 1; $i < $depth; $i++) {
			$nodes = $this->getLevel($i);
			callback($nodes);
		}
	}

	public function isLeaf(Node $node) : bool 
	{
		return count($this->getEdgesOfNode($node)) === 1;
	}

	public function getLevelNodes(int $level, Node $node) : array
	{
		--$level;
		$neighbors = $this->getNeighbors($node);
		$nodes = [];

		if($level === 1) {
			return $neighbors;
		}

		foreach($neighbors as $neighbor) {
			array_merge($nodes, $this->getLevelNodes($level, $neighbor));
		}

		return $nodes;
	}

	public function isFull(int $type) : bool {
		if(count($this->edges) % $type !== 0) {
			return false;
		}

		foreach($this->nodes as $node) {
			$neighbors = count($this->getNeighbors($node));

			if($neighbors !== $type && $neighbors !== 0) {
				return false;
			}
		}

		return true;
	}

	public function preOrder(Node $node, \Closure $callback) {
		if(count($this->nodes) === 0) {
			return;
		}

		$callback($node);
		$neighbors = $this->getNeighbors();

		foreach($neighbors as $neighbor) {
			// todo: get neighbors needs to return in ordered way
			$this->preOrder($neighbor, $callback);
		}
	}
	
	public function postOrder(Node $node, \Closure $callback) {
		if(count($this->nodes) === 0) {
			return;
		}
		
		$neighbors = $this->getNeighbors();

		foreach($neighbors as $neighbor) {
			// todo: get neighbors needs to return in ordered way
			$this->postOrder($neighbor, $callback);
		}

		$callback($node);
	}
}