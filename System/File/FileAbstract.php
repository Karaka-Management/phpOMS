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
abstract class FileAbstract
{
    protected $path = '';
    protected $name = 'new_directory';
    protected $count = 0;
    protected $size = 0;
    private $createdAt = null;
    private $changedAt = null;
    private $owner = 0;
    private $permission = '0000';

    public function __construct(string $path) 
    {
        $this->path = $path;
        $this->name = basename($path);

        $this->createdAt = new \DateTime('now');
        $this->changedAt = new \DateTime('now');
    }

    public function getCount() : int
    {
        return $this->count;
    }

    public function getSize() : int
    {
        return $this->size;
    }

    public function getName() : string 
    {
        return $this->name;
    }

    public function getPath() : string 
    {
        return $this->path;
    }

    public function parent() : Directory 
    {
        return new Directory(Directory::getParent($this->path));
    }

    public function getCreatedAt() : \DateTime 
    {
        return $this->createdAt;
    }

    public function getChangedAt() : \DateTime 
    {
        return $this->changedAt;
    }

    public function getOwner() : int 
    {
        return $this->owner;
    }

    public function getPermission() : string 
    {
        return $this->permission;
    }

    public function index() 
    {
        $this->createdAt->setTimestamp(filemtime($this->path));
        $this->changedAt->setTimestamp(filectime($this->path));
        $this->owner = fileowner($this->path);
        $this->permission = substr(sprintf('%o', fileperms($this->path)), -4);
    }
}