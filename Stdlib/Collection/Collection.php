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
namespace phpOMS\Stdlib\Collection;

/**
 * Multimap utils.
 *
 * @category   Framework
 * @package    phpOMS\Stdlib
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Collection implements \Countable, \ArrayAccess, \Iterator, \JsonSerializable
{
    private $count = 0;
    private $collection = [];

    public function __construct(array $data)
    {
        $this->collection = $data;
    }

    public function toArray() : array
    {
        return $this->collection;
    }

    public function jsonSerialize()
    {
        return json_encode($this->collection);
    }

    public function avg($filter = null)
    {
        return $this->sum($filter) / $this->count();
    }

    public function sum($filter = null)
    {
        $sum = 0;

        if(!isset($filter)) {
            foreach($this->collection as $key => $value) {
                if(is_numeric($value)) {
                    $sum += $value;
                } elseif($value instanceof Collection) {
                    $sum += $value->sum();
                }
            }
        } elseif(is_string($filter)) {
            foreach($this->collection as $key => $value) {
                if(isset($value[$filter]) && is_numeric($value[$filter])) {
                    $sum += $value[$filter];
                }
            }
        } elseif ($filter instanceof \Closure) {
            $sum = $filter($this->collection);
        }

        return $sum;
    }

    public function count()
    {
        return count($this->collection);
    }

    public function chunk(int size)
    {
        return new self(array_chunk($this->collection, size));
    }

    public function collapse()
    {
        $return = [];
        return new self(array_walk_recursive($this->collection, function($a) use (&$return) { $return[] = $a; });)
    }

    public function combine(array $values) : Collection
    {
        foreach($this->collection as $key => $value) {
            if(is_int($key) && is_string($value)) {
                $this->collection[$value] = current($values);
                unset($this->collection[$key]);
            } elseif(is_string($key) && is_string($value)) {
                $this->collection[$key] = [$value, current($values)];
            } elseif(is_array($value)) {
                $this->collection[$key][] = current($values);
            } else {
                continue;
            }

            next($values);
        }

        return $this;
    }

    public function contains($find) : bool
    {
        foreach($this->collection as $key => $value) {
            if(is_string($find) && ((is_string($value) && $find === $value) || (is_array($value) && in_array($find, $value)))) {
                return true;
            } elseif($find instanceof \Collection) {
                $result = $find($value, $key);

                if($result) {
                    return true;
                }
            }
        }

        return false;
    }

    public function diff(array $compare) : array
    {
        $diff = [];

        foreach($this->collection as $key => $value) {
            if($value !== current($compare)) {
                $diff = $value;
            }

            next($compare);
        }

        return $diff;
    }

    public function diffKeys(array $compare)
    {
        $diff = [];

        foreach($this->collection as $key => $value) {
            if($key !== current($compare)) {
                $diff = $key;
            }

            next($compare);
        }

        return $diff;
    }

    public function every(int $n)
    {
        $new = [];
        for ($i = 0; $i < $this->count(); $i += $n) {
            $new[] = $this->get($i);
        }

        return new self($new);
    }

    public function get($key)
    {
        if (!isset($this->collection[$key])) {
            if (is_int($key) && $key < $this->count()) {
                return $this->collection[array_keys($this->collection)[$key]];
            }
        } else {
            return $this->collection[$key];
        }

        return null;
    }

    public function except($filter)
    {
        if (!is_array($filter)) {
            $filter = [$filter];
        }

        $new = [];
        for ($i = 0; $i < $this->count(); $i++) {

            if (!in_array($this->get($i), $filter)) {
                $new[] = $this->get($i);
            }
        }

        return new self($new);
    }

    public function filter()
    {
    }

    public function first()
    {
        return reset($this->collection);
    }

    public function last()
    {
        $end = end($this->collection);
        reset($this->collection);

        return $end;
    }

    public function sort()
    {
    }

    public function reverse()
    {
    }

    public function map()
    {
    }

    public function flatten()
    {
    }

    public function flip()
    {
    }

    public function remove()
    {
    }

    public function range()
    {
    }

    public function groupBy()
    {
    }

    public function has()
    {
    }

    public function implode()
    {
    }

    public function intersect()
    {
    }

    public function isEmpty()
    {
    }

    public function keyBy()
    {
    }

    public function keys()
    {
    }

    public function max()
    {
    }

    public function min()
    {
    }

    public function merge()
    {
    }

    public function pop()
    {
    }

    public function pull()
    {
    }

    public function put()
    {
    }

    public function random()
    {
    }

    public function find()
    {
    }

    public function shift()
    {
    }

    public function shiffle()
    {
    }

    public function slice()
    {
    }

    public function splice()
    {
    }

    public function sortBy()
    {
    }

    public function push()
    {
    }

    public function prepend()
    {
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * Move forward to next element
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * Return the key of the current element
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * Checks if current position is valid
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * Rewind the Iterator to the first element
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}