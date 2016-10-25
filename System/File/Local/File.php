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
use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\FileInterface;
use phpOMS\System\File\PathException;

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
class File extends FileAbstract implements FileInterface
{

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
        parent::__construct($path);
        $this->count = 1;

        if (file_exists($this->path)) {
            $this->index();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function index()
    {
        parent::index();

        $this->size = filesize($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public static function put(string $path, string $content, int $mode = ContentPutMode::APPEND | ContentPutMode::CREATE) : bool
    {
        // todo: create all else cases, right now all getting handled the same way which is wrong
        if (
            (($mode & ContentPutMode::APPEND) === ContentPutMode::APPEND && file_exists($path))
            || (($mode & ContentPutMode::PREPEND) === ContentPutMode::PREPEND && file_exists($path))
            || (($mode & ContentPutMode::REPLACE) === ContentPutMode::REPLACE && file_exists($path))
            || (!file_exists($path) && ($mode & ContentPutMode::CREATE) === ContentPutMode::CREATE)
        ) {
            if (!Directory::exists(dirname($path))) {
                Directory::create(dirname($path), '0644', true);
            }

            file_put_contents($path, $content);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function get(string $path) : string
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return file_get_contents($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function set(string $path, string $content) : bool
    {
        return self::put($path, $content, ContentPutMode::REPLACE | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function append(string $path, string $content) : bool
    {
        return self::put($path, $content, ContentPutMode::APPEND | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function prepend(string $path, string $content) : bool
    {
        return self::put($path, $content, ContentPutMode::PREPEND | ContentPutMode::CREATE);
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
    public static function parent(string $path) : string
    {
        return Directory::parent(dirname($path));
    }

    /**
     * {@inheritdoc}
     */
    public static function created(string $path) : \DateTime
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        $created = new \DateTime();
        $created->setTimestamp(filemtime($path));

        return $created;
    }

    /**
     * {@inheritdoc}
     */
    public static function changed(string $path) : \DateTime
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        $changed = new \DateTime();
        $changed->setTimestamp(filectime($path));

        return $changed;
    }

    /**
     * {@inheritdoc}
     */
    public static function size(string $path, bool $recursive = true) : int
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return filesize($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return fileowner($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : int
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return fileperms($path);
    }

    /**
     * Gets the directory name of a file.
     * 
     * @param  string $path Path of the file to get the directory name for.
     * 
     * @return string Returns the directory name of the file.
     *
     * @since 1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function dirname(string $path) : string
    {
        return dirname($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!file_exists($from)) {
            throw new PathException($from);
        }

        if ($overwrite || !file_exists($to)) {
            if (!Directory::exists(dirname($to))) {
                Directory::create(dirname($to), '0644', true);
            }

            copy($from, $to);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!file_exists($from)) {
            throw new PathException($from);
        }

        if ($overwrite || !file_exists($to)) {
            if (!Directory::exists(dirname($to))) {
                Directory::create(dirname($to), '0644', true);
            }

            rename($from, $to);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        if (!file_exists($path)) {
            return false;
        }

        unlink($path);

        return true;
    }

    public function getDirName() : string
    {
        return basename(dirname($this->path));
    }

    public function getDirPath() : string
    {
        return dirname($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        return self::create($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path) : bool
    {
        if (!file_exists($path)) {
            if (!Directory::exists(dirname($path))) {
                Directory::create(dirname($path), '0644', true);
            }

            touch($path);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent() : string
    {
        return file_get_contents($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::REPLACE | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public function appendContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::APPEND | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public function prependContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::PREPEND | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return explode('.', $this->name)[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension() : string
    {
        $extension = explode('.', $this->name);

        return $extension[1] ?? '';
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
     * {@inheritdoc}
     */
    public function putContent(string $content, int $mode = ContentPutMode::APPEND | ContentPutMode::CREATE) : bool
    {
        // TODO: Implement putContent() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return explode('.', basename($path))[0];
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        // TODO: Implement basename() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function count(string $path, bool $recursive = false) : int
    {
        // TODO: Implement count() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function extension(string $path) : string
    {
        $extension = explode('.', basename($path));

        return $extension[1] ?? '';
    }
}