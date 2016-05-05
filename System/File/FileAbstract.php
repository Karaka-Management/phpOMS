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
 * @category   Framework
 * @package    phpOMS\System\File
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class FileAbstract
{
    /**
     * Path.
     *
     * @var string
     * @since 1.0.0
     */
    protected $path = '';

    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    protected $name = 'new_directory';

    /**
     * Directory/File count.
     *
     * @var int
     * @since 1.0.0
     */
    protected $count = 0;

    /**
     * Directory/Filesize in bytes.
     *
     * @var int
     * @since 1.0.0
     */
    protected $size = 0;

    /**
     * Created at.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected $createdAt = null;

    /**
     * Last changed at.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected $changedAt = null;

    /**
     * Owner.
     *
     * @var int
     * @since 1.0.0
     */
    protected $owner = 0;

    /**
     * Permission.
     *
     * @var string
     * @since 1.0.0
     */
    protected $permission = '0000';

    /**
     * Constructor.
     *
     * @param string $path Path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->name = basename($path);

        $this->createdAt = new \DateTime('now');
        $this->changedAt = new \DateTime('now');
    }

    /**
     * Get directory/file count.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCount() : int
    {
        return $this->count;
    }

    /**
     * Get directory/file size.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getSize() : int
    {
        return $this->size;
    }

    /**
     * Get name.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get path.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get parent directory.
     *
     * @return Directory
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function parent() : Directory
    {
        return new Directory(Directory::getParent($this->path));
    }

    /**
     * Create file/directory.
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    abstract public function createNode() : bool;

    /**
     * Get created at.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCreatedAt() : \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Get last changed at.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getChangedAt() : \DateTime
    {
        return $this->changedAt;
    }

    /**
     * Get owner.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getOwner() : int
    {
        return $this->owner;
    }

    /**
     * Get permission.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPermission() : string
    {
        return $this->permission;
    }

    /**
     * (Re-)Index path.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function index()
    {
        $this->createdAt->setTimestamp(filemtime($this->path));
        $this->changedAt->setTimestamp(filectime($this->path));
        $this->owner      = fileowner($this->path);
        $this->permission = substr(sprintf('%o', fileperms($this->path)), -4);
    }
}