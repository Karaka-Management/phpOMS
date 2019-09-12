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
    private \Closure $compare;

    private array $nodes = [];

    public function __construct(\Closure $compare = null)
    {
        $this->compare = $compare ?? function($a, $b) { return $a <=> $b; };
    }

    public function insort($x, $lo = 0) : void
    {
        $hi = \count($this->nodes);

        while ($lo < $hi) {
            $mid = (int) \floor(($lo + $hi) / 2);
            if (($this->compare)($x, $this->node[$mid]) < 0) {
                $hi = $mid;
            } else {
                $lo = $mid + 1;
            }
        }

        $this->nodes = \array_splice($this->nodes, $lo, 0, $x);
    }

    public function push($item) : void
    {
        $this->nodes[] = $item;
        $this->siftDown(0, \count($this->nodes) - 1);
    }

    public function pop()
    {
        $last = \array_pop($this->nodes);
        if (empty($this->nodes)) {
            return $last;
        }

        $item = $this->nodes[0];
        $this->nodes[0] = $last;
        $this->siftUp(0);

        return $item;
    }

    public function peek()
    {
        return $this->nodes[0];
    }

    public function contains($item) : bool
    {
        foreach ($this->nodes as $key => $node) {
            if (\is_scalar($item)) {
                if ($node === $item) {
                    return true;
                }
            } else {
                if ($item->isEqual($node)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function replace($new)
    {
        $old = $this->nodes[0];
        $this->nodes[0] = $new;
        $this->siftUp(0);

        return $old;
    }

    public function pushpop($item)
    {
        if (!empty($this->nodes) && ($this->compare)($this->nodes[0], $item) < 0) {
            $temp = $item;
            $item = $this->nodes[0];
            $this->nodes[0] = $temp;
            $this->siftUp(0);
        }

        return $item;
    }

    public function heapify() : void
    {
        for ($i = (int) \floor(\count($this->nodes) / 2); $i > -1; --$i) {
            $this->siftUp($i);
        }
    }

    public function update($item) : bool
    {
        $pos = null;
        foreach ($this->nodes as $key => $node) {
            if (\is_scalar($item)) {
                if ($node === $item) {
                    $pos = $key;
                    break;
                }
            } else {
                if ($item->isEqual($node)) {
                    $pos = $key;
                    break;
                }
            }
        }

        if ($pos === null) {
            return false;
        }

        $this->siftDown(0, $pos);
        $this->siftUp($pos);

        return true;
    }

    public function getNLargest(int $n) : array
    {
        $nodes = $this->nodes;
        \uasort($nodes, $this->compare);

        return \array_slice(\array_reverse($nodes), 0, $n);
    }

    public function getNSmallest(int $n): array
    {
        $nodes = $this->nodes;
        \uasort($nodes, $this->compare);

        return \array_slice($nodes, 0, $n);
    }

    private function siftDown(int $start, int $pos) : void
    {
        $item = $this->nodes[$pos];
        while ($pos > $start) {
            $pPos = ($pos - 1) >> 1;
            $parent = $this->nodes[$pPos];

            if (($this->compare)($item, $parent) < 0) {
                $this->nodes[$pos] = $parent;
                $pos = $pPos;

                continue;
            }

            break;
        }

        $this->nodes[$pos] = $item;
    }

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
            $pos = $cPos;
            $cPos = 2 * $pos + 1;
        }

        $this->nodes[$pos] = $item;
        $this->siftDown($sPos, $pos);
    }

    public function clear() : void
    {
        $this->nodes = [];
    }

    public function empty() : bool
    {
        return empty($this->nodes);
    }

    public function size() : int
    {
        return \count($this->nodes);
    }

    public function toArray()
    {
        return $this->nodes;
    }
}
