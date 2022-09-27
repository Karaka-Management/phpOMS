<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\System\File\Ftp
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
 * @license OMS License 1.0
 * @link    https://karaka.app
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
     * Is directory initialized
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $isInitialized = false;

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
        if ($this->con === null) {
            return;
        }

        $mtime = \ftp_mdtm($this->con, $this->path);
        $ctime = \ftp_mdtm($this->con, $this->path);

        $this->createdAt = (new \DateTimeImmutable())->setTimestamp($mtime === false ? 0 : $mtime);
        $this->changedAt->setTimestamp($ctime === false ? 0 : $ctime);

        $owner = \fileowner($this->path);

        $this->owner      = $owner === false ? 0 : $owner;
        $this->permission = (int) \substr(\sprintf('%o', \fileperms($this->path)), -4);

        $this->isInitialized = true;
    }
}
