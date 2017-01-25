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
namespace phpOMS\System\File\Local;

use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\DirectoryInterface;
use phpOMS\System\File\PathException;
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
     * Directory list filter.
     *
     * @var string
     * @since 1.0.0
     */
    private $filter = '*';

    /**
     * Directory nodes (files and directories).
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
     * {@inheritdoc}
     */
    public function index() /* : void */
    {
        parent::index();

        foreach (glob($this->path . DIRECTORY_SEPARATOR . $this->filter) as $filename) {
            if (!StringUtils::endsWith(trim($filename), '.')) {
                $file = is_dir($filename) ? new self($filename) : new File($filename);

                $this->addNode($file);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
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
    public static function size(string $dir, bool $recursive = true) : int
    {
        $countSize = 0;
        $count     = 0;

        if (is_readable($dir)) {
            $dir_array = scandir($dir);

            foreach ($dir_array as $key => $filename) {
                if ($filename != ".." && $filename != ".") {
                    if (is_dir($dir . "/" . $filename) && $recursive) {
                        $countSize += self::size($dir . "/" . $filename, $recursive);
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
     * {@inheritdoc}
     */
    public static function count(string $path, bool $recursive = true, array $ignore = ['.', '..', 'cgi-bin',
                                                                                               '.DS_Store']) : int
    {
        $size  = 0;
        $files = scandir($path);

        foreach ($files as $t) {
            if (in_array($t, $ignore)) {
                continue;
            }
            if (is_dir(rtrim($path, '/') . '/' . $t)) {
                if ($recursive) {
                    $size += self::count(rtrim($path, '/') . '/' . $t, true, $ignore);
                }
            } else {
                $size++;
            }
        }

        return $size;
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        if (!file_exists($path) || !is_dir($path)) {
            throw new PathException($path);
        }

        $files = scandir($path);

        /* Removing . and .. */
        unset($files[1]);
        unset($files[0]);

        foreach ($files as $file) {
            if (is_dir($file)) {
                self::delete($file);
            } else {
                unlink($file);
            }
        }

        rmdir($path);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
        $path = explode('/', str_replace('\\', '/', $path));
        array_pop($path);

        return implode('/', $path);
    }

    /**
     * {@inheritdoc}
     * todo: move to fileAbastract since it should be the same for file and directory?
     */
    public static function created(string $path) : \DateTime
    {
        if(!file_exists($path)) {
            $created = new \DateTime();
            $created->setTimestamp(0);

            return $created;
        }

        $created = new \DateTime('now');
        $created->setTimestamp(filemtime($path));

        return $created;
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
        return file_exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '') : string
    {
        return preg_replace('[^\w\s\d\.\-_~,;:\[\]\(\]\/]', $replace, $path);
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

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return basename($path);
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