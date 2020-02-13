<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\System\File\Local
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System\File\Local;

use phpOMS\System\File\ContainerInterface;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File\Local
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class FileAbstract implements ContainerInterface
{
    /**
     * Path.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $path = '';

    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $name = 'new_directory';

    /**
     * Directory/File count.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $count = 0;

    /**
     * Directory/Filesize in bytes.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $size = 0;

    /**
     * Created at.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected \DateTime $createdAt;

    /**
     * Last changed at.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected \DateTime $changedAt;

    /**
     * Owner.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $owner = 0;

    /**
     * Permission.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $permission = 0755;

    /**
     * Constructor.
     *
     * @param string $path Path
     *
     * @since 1.0.0
     */
    public function __construct(string $path)
    {
        $this->path = \rtrim($path, '/\\');
        $this->name = \basename($path);

        $this->createdAt = new \DateTime('now');
        $this->changedAt = new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    public function getCount(bool $recursive = true) : int
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(bool $recursive = true) : int
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function parentNode() : Directory
    {
        return new Directory(Directory::parent($this->path));
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt() : \DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getChangedAt() : \DateTime
    {
        return $this->changedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() : int
    {
        return $this->owner;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermission() : int
    {
        return $this->permission;
    }

    /**
     * {@inheritdoc}
     */
    public function index() : void
    {
        $mtime = \filemtime($this->path);
        $ctime = \filectime($this->path);

        $this->createdAt->setTimestamp($mtime === false ? 0 : $mtime);
        $this->changedAt->setTimestamp($ctime === false ? 0 : $ctime);

        $owner = \fileowner($this->path);

        $this->owner      = $owner === false ? 0 : $owner;
        $this->permission = (int) \substr(\sprintf('%o', \fileperms($this->path)), -4);
    }
}
