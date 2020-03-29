<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Stdlib\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Heap class.
 *
 * @package phpOMS\Stdlib\Base
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Heap
{
    /**
     * Comparison function
     *
     * @var \Closure
     * @since 1.0.0
     */
    private \Closure $compare;

    /**
     * Heap items
     *
     * @var array<int, mixed>
     * @since 1.0.0
     */
    private array $nodes = [];

    /**
     * Constructor.
     *
     * @param null|\Closure $compare Compare function
     *
     * @since 1.0.0
     */
    public function __construct(\Closure $compare = null)
    {
        $this->compare = $compare ?? function($a, $b) {
            return $a <=> $b;
        };
    }

    /**
     * Insert item into sorted heap at correct position
     *
     * @param mixed $x  Element to insert
     * @param int   $lo Lower bound
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function insort($x, int $lo = 0) : void
    {
        $hi = \count($this->nodes);

        while ($lo < $hi) {
            $mid = (int) (($lo + $hi) / 2);
            if (($this->compare)($x, $this->nodes[$mid]) < 0) {
                $hi = $mid;
            } else {
                $lo = $mid + 1;
            }
        }

        \array_splice($this->nodes, $lo, 0, [$x]);
    }

    /**
     * Push item onto the heap
     *
     * @param mixed $item Item to add to the heap
     *
     * @return void;
     *
     * @since 1.0.0
     */
    public function push($item) : void
    {
        $this->nodes[] = $item;
        $this->siftDown(0, \count($this->nodes) - 1);
    }

    /**
     * Pop the smallest item off the heap
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function pop()
    {
        $last = \array_pop($this->nodes);
        if (empty($this->nodes)) {
            return $last;
        }

        $item           = $this->nodes[0];
        $this->nodes[0] = $last;
        $this->siftUp(0);

        return $item;
    }

    /**
     * Get first item without popping
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function peek()
    {
        return $this->nodes[0];
    }

    /**
     * Contains item?
     *
     * @param mixed $item Item to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function contains($item) : bool
    {
        foreach ($this->nodes as $key => $node) {
            if (\is_scalar($item)) {
                if ($node === $item) {
                    return true;
                }
            } elseif ($item->isEqual($node)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Pop a item and push a new one (replace with a new one)
     *
     * @param mixed $new New item
     *
     * @return mixed popped item
     *
     * @since 1.0.0
     */
    public function replace($new)
    {
        $old            = $this->nodes[0];
        $this->nodes[0] = $new;
        $this->siftUp(0);

        return $old;
    }

    /**
     * Push item and pop one
     *
     * @param mixed $item New item
     *
     * @return mixed popped item
     *
     * @since 1.0.0
     */
    public function pushpop($item)
    {
        if (!empty($this->nodes) && ($this->compare)($this->nodes[0], $item) < 0) {
            $temp           = $item;
            $item           = $this->nodes[0];
            $this->nodes[0] = $temp;
            $this->siftUp(0);
        }

        return $item;
    }

    /**
     * Turn list into heap
     *
     * @param array $list Item list
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function heapify(array $list) : void
    {
        $this->nodes = $list;

        for ($i = (int) (\count($this->nodes) / 2); $i > -1; --$i) {
            $this->siftUp($i);
        }
    }

    /**
     * Update the position of a item
     *
     * This is called after changing an item
     *
     * @param mixed $item Item to update
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function update($item) : bool
    {
        $pos = null;
        foreach ($this->nodes as $key => $node) {
            if ($item->isEqual($node)) {
                $pos = $key;
                break;
            }
        }

        if ($pos === null) {
            return false;
        }

        $this->siftDown(0, $pos);
        $this->siftUp($pos);

        return true;
    }

    /**
     * Get n largest items
     *
     * @param int $n Number of items
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getNLargest(int $n) : array
    {
        $nodes = $this->nodes;
        \uasort($nodes, $this->compare);

        return \array_slice(\array_reverse($nodes), 0, $n);
    }

    /**
     * Get n smalles items
     *
     * @param int $n Number of items
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getNSmallest(int $n) : array
    {
        $nodes = $this->nodes;
        \uasort($nodes, $this->compare);

        return \array_slice($nodes, 0, $n);
    }

    /**
     * Down shift
     *
     * @param int $start Start index
     * @param int $pos   Pos of the pivot item
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function siftDown(int $start, int $pos) : void
    {
        $item = $this->nodes[$pos];
        while ($pos > $start) {
            $pPos   = ($pos - 1) >> 1;
            $parent = $this->nodes[$pPos];

            if (($this->compare)($item, $parent) < 0) {
                $this->nodes[$pos] = $parent;
                $pos               = $pPos;

                continue;
            }

            break;
        }

        $this->nodes[$pos] = $item;
    }

    /**
     * Up shift
     *
     * @param int $pos Pos of the pivot item
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function siftUp(int $pos) : void
    {
        $ePos = \count($this->nodes);
        $sPos = $pos;
        $item = $this->nodes[$pos];
        $cPos = 2 * $pos + 1;

        while ($cPos < $ePos) {
            $rPos = $cPos + 1;

            if ($rPos < $ePos && ($this->compare)($this->nodes[$cPos], $this->nodes[$rPos]) > -1) {
                $cPos = $rPos;
            }

            $this->nodes[$pos] = $this->nodes[$cPos];
            $pos               = $cPos;
            $cPos              = 2 * $pos + 1;
        }

        $this->nodes[$pos] = $item;
        $this->siftDown($sPos, $pos);
    }

    /**
     * Clear heap
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function clear() : void
    {
        $this->nodes = [];
    }

    /**
     * Is heap empty?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isEmpty() : bool
    {
        return empty($this->nodes);
    }

    /**
     * Get heap size
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function size() : int
    {
        return \count($this->nodes);
    }

    /**
     * Get heap array
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        return $this->nodes;
    }
}
