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
namespace phpOMS\System;

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
class Directory extends FileAbstract implements Iterator, ArrayAccess
{
    private $filter = '*';
    private $nodes = [];

    public static create(string $path, $permission = 0644) : bool
    {
        if (!file_exists($path)) {
            if(is_writable($path)) {
                mkdir($path, $permission, true);

                return true;
            } else {
                throw new PathException($path);
            }
        }

        return false;
    }

    public function __construct(string $path, string $filter = '*') 
    {
        $this->filter = $filter;
        parent::__constrct($path);

        if(file_exists($this->path)) {
            parent::index();
        }
    }

    public function create() 
    {
        // todo: implement?
    }

    public function get(string $name) : FileInterface 
    {
        return $this->node[$name] ?? new NullFile();
    }

    public function add(FileInterface $file) 
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
        reset($this->node);
    }

    public function current()
    {
        return current($this->node);
    }

    public function key() 
    {
        return key($this->node);
    }

    public function next() 
    {
        return next($this->node);
    }

    public function valid()
    {
        $key = key($this->node);

        return ($key !== null && $key !== false);
    }

    /* ArrayAccess */
    public function offsetSet(string $offset, FileInterface $value) 
    {
        if (is_null($offset)) {
            $this->add($value)
        } else {
            $this->node[$offset] = $value;
        }
    }

    public function offsetExists(string $offset) 
    {
        return isset($this->node[$offset]);
    }

    public function offsetUnset(string $offset) 
    {
        if(isset($this->node[$offset])) {
            unset($this->node[$offset]);
        }
    }

    public function offsetGet(string $offset) 
    {
        return $this->node[$offset] ?? null;
    }
}