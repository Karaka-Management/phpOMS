<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File\Local
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File\Local;

use phpOMS\System\File\PathException;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File\Local
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class FileAbstract implements LocalContainerInterface
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
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

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
     *
     * @throws PathException
     */
    public static function created(string $path) : \DateTime
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $time = \filectime($path);

        return self::createFileTime($time === false ? 0 : $time);
    }

    /**
     * {@inheritdoc}
     *
     * @throws PathException
     */
    public static function changed(string $path) : \DateTime
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $time = \filemtime($path);

        return self::createFileTime($time === false ? 0 : $time);
    }

    /**
     * Create file time.
     *
     * @param int $time Time of the file
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    private static function createFileTime(int $time) : \DateTime
    {
        $fileTime = new \DateTime();
        $fileTime->setTimestamp($time);

        return $fileTime;
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
        $mtime = \filemtime($this->path);
        $ctime = \filectime($this->path);

        $this->createdAt = (new \DateTimeImmutable())->setTimestamp($mtime === false ? 0 : $mtime);
        $this->changedAt->setTimestamp($ctime === false ? 0 : $ctime);

        $owner = \fileowner($this->path);

        $this->owner      = $owner === false ? '' : (string) $owner;
        $this->permission = (int) \substr(\sprintf('%o', \fileperms($this->path)), -4);

        $this->isInitialized = true;
    }

    /**
     * Find an existing node in the node tree
     *
     * @param string $path Path of the node
     *
     * @return null|Directory|File
     *
     * @since 1.0.0
     */
    public function findNode(string $path) : null|Directory|File
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
