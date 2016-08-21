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
namespace phpOMS\System\File\Local;

use phpOMS\System\File\DirectoryInterface;
use phpOMS\Utils\StringUtils;

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
     * Direcotry list filter.
     *
     * @var string
     * @since 1.0.0
     */
    private $filter = '*';

    /**
     * Direcotry nodes (files and directories).
     *
     * @var FileAbstract[]
     * @since 1.0.0
     */
    private $nodes = [];

    /**
     * Constructor.
     *
     * @param string $path   Path
     * @param string $filter Filter
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $path, string $filter = '*')
    {
        $this->filter = ltrim($filter, '\\/');
        parent::__construct($path);

        if (file_exists($this->path)) {
            $this->index();
        }
    }

    /**
     * Index directory.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function index()
    {
        parent::index();

        foreach (glob($this->path . DIRECTORY_SEPARATOR . $this->filter) as $filename) {
            if (!StringUtils::endsWith(trim($filename), '.')) {
                $file = is_dir($filename) ? new self($filename) : new File($filename);

                $this->add($file);
            }
        }
    }

    /**
     * Add file or directory.
     *
     * @param FileAbstract $file File to add
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function add(FileAbstract $file) : bool
    {
        $this->count += $file->getCount();
        $this->size += $file->getSize();
        $this->nodes[$file->getName()] = $file;

        return $file->createNode();
    }

    /**
     * Get folder size recursively.
     *
     * This can become rather slow for large structures.
     *
     * @param string $dir       Root dir to inspect
     * @param bool   $recursive Get size recursive
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getFolderSize(string $dir, bool $recursive = true) : int
    {
        $countSize = 0;
        $count     = 0;

        if (is_readable($dir)) {
            $dir_array = scandir($dir);

            foreach ($dir_array as $key => $filename) {
                if ($filename != ".." && $filename != ".") {
                    if (is_dir($dir . "/" . $filename) && $recursive) {
                        $countSize += self::getFolderSize($dir . "/" . $filename, $recursive);
                    } else {
                        if (is_file($dir . "/" . $filename)) {
                            $countSize += filesize($dir . "/" . $filename);
                            $count++;
                        }
                    }
                }
            }
        }

        return (int) $countSize;
    }

    /**
     * Get file count inside path.
     *
     * @param string $path      Path to folder
     * @param bool   $recursive Should sub folders be counted as well?
     * @param array  $ignore    Ignore these sub-paths
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getFileCount(string $path, bool $recursive = true, array $ignore = ['.', '..', 'cgi-bin',
                                                                                               '.DS_Store'])
    {
        $size  = 0;
        $files = scandir($path);

        foreach ($files as $t) {
            if (in_array($t, $ignore)) {
                continue;
            }
            if (is_dir(rtrim($path, '/') . '/' . $t)) {
                if ($recursive) {
                    $size += self::getFileCount(rtrim($path, '/') . '/' . $t, true, $ignore);
                }
            } else {
                $size++;
            }
        }

        return $size;
    }

    /**
     * Delete directory and all its content.
     *
     * @param string $path Path to folder
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function deletePath($path) : bool
    {
        $path = realpath($oldPath = $path);
        if ($path === false || !is_dir($path) || StringUtils::startsWith($path, ROOT_PATH)) {
            throw new PathException($oldPath);
        }

        $files = scandir($path);

        /* Removing . and .. */
        unset($files[1]);
        unset($files[0]);

        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deletePath($file);
            } else {
                unlink($file);
            }
        }

        rmdir($path);

        return true;
    }

    /**
     * Get parent directory path.
     *
     * @param string $path Path
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function parent(string $path) : string
    {
        $path = explode('/', str_replace('\\', '/', $path));
        array_pop($path);

        return implode('/', $path);
    }

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

    /* Iterator */

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

    public static function put(string $path, string $content, bool $overwrite = true) : bool
    {
        // TODO: Implement put() method.
    }

    public static function get(string $path) : string
    {
        // TODO: Implement get() method.
    }

    /* ArrayAccess */

    public static function size(string $path) : int
    {
        // TODO: Implement size() method.
    }

    public static function exists(string $path) : bool
    {
        return file_exists($path);
    }

    /**
     * Get node by name.
     *
     * @param string $name File/direcotry name
     *
     * @return FileAbstract
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getNode(string $name) : FileAbstract
    {
        return $this->nodes[$name] ?? new NullFile('');
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        return self::create($this->path, $this->permission, true);
    }

    /**
     * Create directory.
     *
     * @param string $path       Path
     * @param string $permission Directory permission
     * @param bool   $recursive  Create parent directories if applicable
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function create(string $path, string $permission = '0644', bool $recursive = false) : bool
    {
        if (!file_exists($path)) {
            mkdir($path, $permission, $recursive);

            return true;
        }

        return false;
    }

    /**
     * Remove by name.
     *
     * @param string $name Name to remove
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(string $name) : bool
    {
        if (isset($this->nodes[$name])) {
            $this->count -= $this->nodes[$name]->getCount();
            $this->size -= $this->nodes[$name]->getSize();

            unset($this->nodes[$name]);

            // todo: unlink???

            return true;
        }

        return false;
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
            $this->add($value);
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
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->nodes[$offset] ?? null;
    }

    public function getParent() : FileInterface
    {
        // TODO: Implement getParent() method.
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

    public function putContent() : bool
    {
        // TODO: Implement putContent() method.
    }

    public function getContent() : string
    {
        // TODO: Implement getContent() method.
    }
}