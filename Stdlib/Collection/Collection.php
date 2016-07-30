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
namespace phpOMS\Stdlib\Map;

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
    }

    public function toArray()
    {

    }

    public function jsonSerialize()
    {

    }

    public function avg()
    {

    }

    public function chunk()
    {

    }

    public function collapse()
    {

    }

    public function combine()
    {
    }

    public function contains()
    {
    }

    public function count()
    {
    }

    public function diff()
    {
    }

    public function diffKeys()
    {
    }

    public function every()
    {

    }

    public function except()
    {
    }

    public function filter()
    {
    }

    public function first()
    {
    }

    public function last()
    {
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

    public function get()
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

    public function sum()
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