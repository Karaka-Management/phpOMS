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
 *
 * @todo       : there is a bug with Hungary ibans since they have two k (checksums) in their definition
 */
class Tree extends Graph
{
	protected $nodes = [];

	public function getMaxDepth() : int 
	{
		$depth = [0];

		foreach($this->nodes as $node) {
			$depth[] = $node->getMaxDepth();
		}

		return max($depth) + 1;
	}

	public function getMinDepth() : int
	{
		$depth = [0];

		foreach($this->nodes as $node) {
			$depth[] = $node->getMinDepth();
		}

		return min($depth) + 1;
	}

	public function postOrder() 
	{

	}

	public function preOrder()
	{

	}

	public function levelOrder()
	{

	}

	public function levelOrder2()
	{

	}

	public function verticalOrder()
	{

	}

	public function isLeaf() : bool 
	{

	}

	public function isSymmetric() : bool {

	}

	public function getDimension() : int 
	{
		
	}
}