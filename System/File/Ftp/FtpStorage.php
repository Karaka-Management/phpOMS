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
namespace phpOMS\System\File\Ftp;
use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\StorageAbstract;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @category   Framework
 * @package    phpOMS\System\File
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class FtpStorage extends StorageAbstract
{

    public static function created(string $path) : \DateTime
    {
        // TODO: Implement created() method.
    }

    public static function changed(string $path) : \DateTime
    {
        // TODO: Implement changed() method.
    }

    public static function owner(string $path) : int
    {
        // TODO: Implement owner() method.
    }

    public static function permission(string $path) : int
    {
        // TODO: Implement permission() method.
    }

    public static function parent(string $path) : string
    {
        // TODO: Implement parent() method.
    }

    public static function create(string $path) : bool
    {
        // TODO: Implement create() method.
    }

    public static function delete(string $path) : bool
    {
        // TODO: Implement delete() method.
    }

    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement copy() method.
    }

    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement move() method.
    }

    public static function size(string $path) : int
    {
        // TODO: Implement size() method.
    }

    public static function exists(string $path) : bool
    {
        // TODO: Implement exists() method.
    }

    public function getCount() : int
    {
        // TODO: Implement getCount() method.
    }

    public function getSize() : int
    {
        // TODO: Implement getSize() method.
    }

    public function getName() : string
    {
        // TODO: Implement getName() method.
    }

    public function getPath() : string
    {
        // TODO: Implement getPath() method.
    }

    public function getParent() : ContainerInterface
    {
        // TODO: Implement getParent() method.
    }

    public function createNode() : bool
    {
        // TODO: Implement createNode() method.
    }

    public function copyNode() : bool
    {
        // TODO: Implement copyNode() method.
    }

    public function moveNode() : bool
    {
        // TODO: Implement moveNode() method.
    }

    public function deleteNode() : bool
    {
        // TODO: Implement deleteNode() method.
    }

    public function getCreatedAt() : \DateTime
    {
        // TODO: Implement getCreatedAt() method.
    }

    public function getChangedAt() : \DateTime
    {
        // TODO: Implement getChangedAt() method.
    }

    public function getOwner() : int
    {
        // TODO: Implement getOwner() method.
    }

    public function getPermission() : string
    {
        // TODO: Implement getPermission() method.
    }

    public function index()
    {
        // TODO: Implement index() method.
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

    public static function put(string $path, string $content, bool $overwrite = true) : bool
    {
        // TODO: Implement put() method.
    }

    public static function get(string $path) : string
    {
        // TODO: Implement get() method.
    }

    public function putContent() : bool
    {
        // TODO: Implement putContent() method.
    }

    public function getContent() : string
    {
        // TODO: Implement getContent() method.
    }

    protected function getType() : ContainerInterface
    {
        // TODO: Implement getType() method.
    }
}