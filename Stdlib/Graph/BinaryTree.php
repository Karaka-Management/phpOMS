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

	public function getLeft() {
		return $this->nodes[0] ?? null;
	}

	public function getRight() {
		return $this->nodes[1] ?? null;
	}

	public function setLeft(BinaryTree $left) {
		$this->nodes[0] = $left;
	}

	public function setRight(BinaryTree $right) {
		$this->nodes[1] = $right;
	}

	public function preOrder(\Closure $callback) {
		if(count($this->nodes) === 0) {
			return;
		}

		$callback($this);
		$this->nodes[0]->inOrder($callback);
		$this->nodes[1]->inOrder($callback);
	}

	public function inOrder(\Closure $callback) {
		if(count($this->nodes) === 0) {
			return;
		}

		$this->nodes[0]->inOrder($callback);
		$callback($this);
		$this->nodes[1]->inOrder($callback);
	}

	public function postOrder(\Closure $callback) {
		if(count($this->nodes) === 0) {
			return;
		}

		$this->nodes[0]->inOrder($callback);
		$this->nodes[1]->inOrder($callback);
		$callback($this);
	}

	private function getVerticalOrder(int $horizontalDistance = 0, array &$order) 
	{
		if(!isset($order[$horizontalDistance])) {
			$order[$horizontalDistance] = [];
		}

		$order[$horizontalDistance][] = $this;

		if(isset($this->nodes[0])) {
			$this->nodes[0]->getVerticalOrder($horizontalDistance-1, $order);
		}

		if(isset($this->nodes[1])) {
			$this->nodes[1]->getVerticalOrder($horizontalDistance+1, $order);
		}
	}

	public function verticalOrder(\Closure $callback)
	{
		$order = [];
		$this->getVerticalOrder(0, $order);

		foreach($order as $level) {
			foreach($level as $node) {
				$callback($node);
			}
		}
	}

	public function isSymmetric() : bool {
		// todo: compare values? true symmetry requires the values to be the same
		if(isset($this->nodes[0]) && isset($this->nodes[1])) {
			return isSymmetric($this->nodes[0], $this->nodes[1]);
		}

		return false;
	}

	public function symmetric(BinaryTree $tree1, BinaryTree $tree2) : bool {
		// todo: compare values? true symmetry requires the values to be the same
		if(($tree1 !== null && $tree2 !== null) || $tree1 === $tree2) {
			return isSymmetric($tree1->getLeft(), $tree1->getRight()) && isSymmetric($tree2->getRight(), $tree2->getLeft());
		}

		return false;
	}
}