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
		return $this->nodes[0];
	}

	public function getRight() {
		return $this->nodes[1];
	}

	public function setLeft(BinaryTree $left) {
		$this->nodes[0] = $left;
	}

	public function setRight(BinaryTree $right) {
		$this->nodes[1] = $right;
	}

	public function inOrder() {
		
	}
}