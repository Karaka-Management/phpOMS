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

	public function add(Tree $node) {
		$this->nodes[] = $node;

		return $this;
	}

	public function set($key, Tree $node) {
		$this->nodes[$key] = $node;

		return $this;
	}

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

	public function levelOrder(\Closure $callback)
	{
		$depth = $this->getMaxDepth();

		for($i = 1; $i < $depth; $i++) {
			$nodes = $this->getLevel($i);
			callback($nodes);
		}
	}

	public function isLeaf() : bool 
	{
		return count($this->nodes) === 0;
	}

	public function getDimension() : int 
	{
		$size = 1;

		foreach($this->nodes as $node) {
			$size += $node->getDimension() + 1;
		}

		return $size;
	}

	public function getLevelNodes(int $level, array &$nodes)
	{
		--$level;

		foreach($this->nodes as $node) {
			if($level === 0) {
				$nodes[] = $this;

				return $nodes;
			} else {
				$this->getLevelNodes($level, $nodes);
			}
		}
	}
}