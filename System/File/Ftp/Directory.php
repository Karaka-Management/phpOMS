<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\System\File\Ftp;

use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\DirectoryInterface;
use phpOMS\System\File\PathException;
use phpOMS\Utils\StringUtils;
use phpOMS\System\File\Local\FileAbstract;
use phpOMS\System\File\Local\Directory as DirectoryLocal;

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
class Directory extends FileAbstract implements DirectoryInterface
{
    /**
     * {@inheritdoc}
     */
    public static function size(string $dir, bool $recursive = true) : int
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function count(string $path, bool $recursive = true, array $ignore = ['.', '..', 'cgi-bin',
                                                                                               '.DS_Store']) : int
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {

    }

    /**
     * {@inheritdoc}
     * todo: move to fileAbastract since it should be the same for file and directory?
     */
    public static function created(string $path) : \DateTime
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function changed(string $path) : \DateTime
    {
        // TODO: Implement changed() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {
        // TODO: Implement owner() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : string
    {
        // TODO: Implement permission() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement copy() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement move() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(string $path) : bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '') : string
    {
        return DirectoryLocal::sanitize($path, $replace);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path, string $permission = '0644', bool $recursive = false) : bool
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return DirectoryLocal::name($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return DirectoryLocal::basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(string $name) : FileAbstract
    {
        return $this->nodes[$name] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        return self::create($this->path, $this->permission, true);

        // todo: add node
    }

    public function addNode($file) : bool
    {
        $this->count += $file->getCount();
        $this->size += $file->getSize();
        $this->nodes[$file->getName()] = $file;

        return $file->createNode();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() : ContainerInterface
    {
        // TODO: Implement getParent() method.
    }

    /**
     * {@inheritdoc}
     */
    public function copyNode(string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement copyNode() method.
    }

    /**
     * {@inheritdoc}
     */
    public function moveNode(string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement moveNode() method.
    }

    /**
     * {@inheritdoc}
     */
    public function deleteNode() : bool
    {
        // TODO: Implement deleteNode() method.
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $key = key($this->nodes);

        return ($key !== null && $key !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->addNode($value);
        } else {
            $this->nodes[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->nodes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if (isset($this->nodes[$offset])) {
            unset($this->nodes[$offset]);
        }
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
}