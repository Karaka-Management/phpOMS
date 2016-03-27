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
namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Directory extends FileAbstract implements \Iterator, \ArrayAccess
{
    private $filter = '*';
    private $nodes = [];

    public static function create(string $path, int $permission = 0644, bool $recursive = false) : bool
    {
        if($recursive && !file_exists($parent = self::getParent($path))) {
            self::create($parent, $permission, $recursive);
        }

        if (!file_exists($path)) {
            if(is_writable(self::getParent($path))) {
                mkdir($path, $permission, true);

                return true;
            } else {

                throw new PathException($path);
            }
        }

        return false;
    }

    public static function getParent(string $path) : string
    {
        $path = explode('/', str_replace('\\', '/', $path));
        array_pop($path);

        return implode('/', $path);
    }

    public function __construct(string $path, string $filter = '*') 
    {
        $this->filter = $filter;
        parent::__constrct($path);

        if(file_exists($this->path)) {
            parent::index();
        }
    }

    public function get(string $name) : FileAbstract 
    {
        return $this->node[$name] ?? new NullFile();
    }

    public function add(FileAbstract $file) 
    {
        $this->count += $file->getCount();
        $this->size += $file->getSize();
        $this->nodes[$this->getName()] = $file;
    }

    public function remove(string $name) : bool
    {
        if(isset($this->nodes[$name])) {
            $this->count -= $this->nodes[$name]->getCount();
            $this->size -= $this->nodes[$name]->getSize();

            unset($this->nodes[$name]);
            // todo: unlink???

            return true;
        }

        return false;
    }

    public function index() 
    {
        parent::index();

        foreach (glob($this->path . DIRECTORY_SEPARATOR . $this->filter) as $filename) {
            // todo: handle . and ..???!!!
            if(is_dir($filename)) {
                $file = new Directory($filename);
                $file->index();
            } else {
                $file = new File($filename);
            }

            $this->add($file);
        }
    }

    /* Iterator */
    public function rewind()
    {
        reset($this->nodes);
    }

    public function current()
    {
        return current($this->nodes);
    }

    public function key() 
    {
        return key($this->nodes);
    }

    public function next() 
    {
        return next($this->nodes);
    }

    public function valid()
    {
        $key = key($this->nodes);

        return ($key !== null && $key !== false);
    }

    /* ArrayAccess */
    public function offsetSet($offset, $value) 
    {
        if (is_null($offset)) {
            $this->add($value);
        } else {
            $this->nodes[$offset] = $value;
        }
    }

    public function offsetExists($offset) 
    {
        return isset($this->nodes[$offset]);
    }

    public function offsetUnset($offset) 
    {
        if(isset($this->nodes[$offset])) {
            unset($this->nodes[$offset]);
        }
    }

    public function offsetGet($offset) 
    {
        return $this->nodes[$offset] ?? null;
    }
}