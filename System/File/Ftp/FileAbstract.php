<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\System\File\Ftp
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File\Ftp;

use phpOMS\Uri\HttpUri;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File\Ftp
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class FileAbstract implements FtpContainerInterface
{
    /**
     * Ftp connection
     *
     * @var null|\FTP\Connection
     * @since 1.0.0
     */
    protected ?\FTP\Connection $con = null;

    /**
     * Ftp uri
     *
     * @var HttpUri
     * @since 1.0.0
     */
    protected HttpUri $uri;

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
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    protected \DateTimeImmutable $createdAt;

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
     * @var string
     * @since 1.0.0
     */
    protected string $owner = '';

    /**
     * Permission.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $permission = 0755;

    /**
     * Is directory initialized
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $isInitialized = false;

    /**
     * Parent element
     *
     * @var null|Directory
     * @since 1.0.0
     */
    protected ?Directory $parent = null;

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

        $this->createdAt = new \DateTimeImmutable('now');
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
    public function getBasename() : string
    {
        return \basename($this->path);
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
    public function getCreatedAt() : \DateTimeImmutable
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
    public function getOwner() : string
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
        if ($this->con === null) {
            return;
        }

        $mtime = \ftp_mdtm($this->con, $this->path);
        $ctime = \ftp_mdtm($this->con, $this->path);

        $this->createdAt = (new \DateTimeImmutable())->setTimestamp($mtime === false ? 0 : $mtime);
        $this->changedAt->setTimestamp($ctime === false ? 0 : $ctime);

        $this->owner      = '';
        $this->permission = 0;

        $this->isInitialized = true;
    }

    /**
     * Find an existing node in the node tree
     *
     * @param string $path Path of the node
     *
     * @return null|Directory
     *
     * @since 1.0.0
     */
    public function findNode(string $path) : ?Directory
    {
        // Change parent element
        $currentPath = \explode('/', \trim($this->path, '/'));
        $newPath     = \explode('/', \trim($path, '/'));

        // Remove last element which is the current name
        $currentName = \array_pop($currentPath);
        $newName     = \array_pop($newPath);

        $currentParentName = \end($currentPath);
        $newParentName     = \end($newPath);

        $currentLength = \count($currentPath);
        $newLength     = \count($newPath);

        $max       = \max($currentLength, $newLength);
        $newParent = $this;

        // Evaluate path similarity
        for ($i = 0; $i < $max; ++$i) {
            if (!isset($currentPath[$i]) || !isset($newPath[$i])
                || $currentPath[$i] !== $newPath[$i]
            ) {
                break;
            }
        }

        // Walk parent path
        for ($j = $currentLength - $i; $j > 0; --$j) {
            if ($newParent->parent === null) {
                // No parent found

                $newParent = null;
                break;
            }

            $newParent = $newParent->parent;
        }

        // Walk child path if new path even is in child path
        for ($j = $i; $i < $newLength; ++$j) {
            if (!isset($newParent->nodes[$newPath[$j]])) {
                // Path tree is not defined that deep -> no updating needed

                $newParent = null;
                break;
            }

            $newParent = $newParent->nodes[$newPath[$j]];
        }

        return $newParent;
    }
}
