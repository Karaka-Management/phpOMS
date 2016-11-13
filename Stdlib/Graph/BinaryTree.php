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

use phpOMS\Validation\Base\IbanEnum;

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
 *
 * @todo       : there is a bug with Hungary ibans since they have two k (checksums) in their definition
 */
class BinaryTree extends Tree
{
	public static function invert($list) : BinaryTree
	{
	    if (empty($list->getNodes())) {
	    	return $list;
	    }

		$left = $list->getLeft();
		$list->setLeft($list->invert($list->nodes[1]));
		$list->setRight($list->invert($left));

		return $list;  
	}

	public function getLeft(Node $base)
	{
		$neighbors = $base->getNeighbors($base);

		// todo: index can be wrong, see setLeft/setRight
		return $neighbors[0] ?? null;
	}

	public function getRight(Node $base)
	{
		$neighbors = $base->getNeighbors($base);

		// todo: index can be wrong, see setLeft/setRight
		return $neighbors[1] ?? null;
	}

	public function setLeft(Node $base, Node $left) 
	{
		if($this->getLeft($base) === null) {
			$this->addNode($base, $left);
			// todo: doesn't know that this is left
			// todo: maybe need to add numerics to edges?
		} else {
			// todo: replace node
		}
	}

	public function setRight(Node $base, Node $right) 
	{
		if($this->getRight($base) === null) {
			$this->addNode($base, $right);
			// todo: doesn't know that this is right
			// todo: maybe need to add numerics to edges?
		} else {
			// todo: replace node
		}
	}

	public function inOrder(Node $node, \Closure $callback) 
	{
		if(count($this->nodes) === 0) {
			return;
		}

		$this->inOrder($this->getLeft($node), $callback);
		$callback($node);
		$this->inOrder($this->getRight($node), $callback);
	}

	private function getVerticalOrder(Node $node, int $horizontalDistance = 0, array &$order) 
	{
		if(!isset($order[$horizontalDistance])) {
			$order[$horizontalDistance] = [];
		}

		$order[$horizontalDistance][] = $node;
		$left = $this->getLeft($node);
		$right = $this->getRight($node);

		if(isset($left)) {
			$this->getVerticalOrder($left, $horizontalDistance-1, $order);
		}

		if(isset($right)) {
			$this->getVerticalOrder($right, $horizontalDistance+1, $order);
		}
	}

	public function verticalOrder(Node $node, \Closure $callback)
	{
		$order = [];
		$this->getVerticalOrder($node, 0, $order);

		foreach($order as $level) {
			foreach($level as $node) {
				$callback($node);
			}
		}
	}

	public function isSymmetric(Node $node1 = null, Node $node2 = null) : bool 
	{
		if(!isset($node1) && !isset($node2)) {
			return true;
		}

		$left1 = $this->getLeft($node1);
		$right1 = $this->getRight($node1);

		$left2 = isset($node2) ? $this->getLeft($node1) : $this->getLeft($node2);
		$right2 = isset($node2) ? $this->getRight($node1) : $this->getRight($node2);

		// todo: compare values? true symmetry requires the values to be the same
		if(isset($node1) && isset($node2)) {
			return $this->isSymmetric($left1, $right2) && $this->isSymmetric($right1, $left2);
		}

		return false;
	}
}