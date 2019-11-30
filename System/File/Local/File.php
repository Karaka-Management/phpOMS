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
use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\FileInterface;
use phpOMS\System\File\PathException;

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
final class File extends FileAbstract implements LocalContainerInterface, FileInterface
{

    /**
     * Constructor.
     *
     * @param string $path Path
     *
     * @since 1.0.0
     */
    public function __construct(string $path)
    {
        parent::__construct($path);
        $this->count = 1;

        if (\file_exists($this->path)) {
            $this->index();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function index() : void
    {
        parent::index();

        $this->size = (int) \filesize($this->path);
    }

    /**
     * Save content to file.
     *
     * @param string $path    File path to save the content to
     * @param string $content Content to save in file
     * @param int    $mode    Mode (overwrite, append)
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function put(string $path, string $content, int $mode = ContentPutMode::REPLACE | ContentPutMode::CREATE) : bool
    {
        $exists = \file_exists($path);

        try {
            if (($exists && ContentPutMode::hasFlag($mode, ContentPutMode::APPEND))
                || ($exists && ContentPutMode::hasFlag($mode, ContentPutMode::PREPEND))
                || ($exists && ContentPutMode::hasFlag($mode, ContentPutMode::REPLACE))
                || (!$exists && ContentPutMode::hasFlag($mode, ContentPutMode::CREATE))
            ) {
                if ($exists && ContentPutMode::hasFlag($mode, ContentPutMode::APPEND)) {
                    \file_put_contents($path, \file_get_contents($path) . $content);
                } elseif ($exists && ContentPutMode::hasFlag($mode, ContentPutMode::PREPEND)) {
                    \file_put_contents($path, $content . \file_get_contents($path));
                } else {
                    if (!Directory::exists(\dirname($path))) {
                        Directory::create(\dirname($path), 0755, true);
                    }

                    \file_put_contents($path, $content);
                }

                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }

        return false;
    }

    /**
     * Get content from file.
     *
     * @param string $path File path of content
     *
     * @return string Content of file
     *
     * @since 1.0.0
     */
    public static function get(string $path) : string
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $contents = \file_get_contents($path);

        return $contents === false ? '' : $contents;
    }

    /**
     * {@inheritdoc}
     */
    public static function count(string $path, bool $recursive = true, array $ignore = []) : int
    {
        return 1;
    }

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $path    File path to save the content to
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function set(string $path, string $content) : bool
    {
        return self::put($path, $content, ContentPutMode::REPLACE | ContentPutMode::CREATE);
    }

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or appends existing file.
     *
     * @param string $path    File path to save the content to
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function append(string $path, string $content) : bool
    {
        return self::put($path, $content, ContentPutMode::APPEND | ContentPutMode::CREATE);
    }

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or prepends existing file.
     *
     * @param string $path    File path to save the content to
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
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
        return \file_exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
        return Directory::parent(\dirname($path));
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '', string $invalid = '/[^\w\s\d\.\-_~,;\/\[\]\(\]]/') : string
    {
        return \preg_replace($invalid, $replace, $path) ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public static function created(string $path) : \DateTime
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $time = \filemtime($path);

        return self::createFileTime($time === false ? 0 : $time);
    }

    /**
     * {@inheritdoc}
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
    public static function size(string $path, bool $recursive = true) : int
    {
        if (!\file_exists($path)) {
            return -1;
        }

        return (int) \filesize($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        return (int) \fileowner($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : int
    {
        if (!\file_exists($path)) {
            return -1;
        }

        return (int) \fileperms($path);
    }

    /**
     * Gets the directory name of a file.
     *
     * @param string $path path of the file to get the directory name for
     *
     * @return string returns the directory name of the file
     *
     * @since 1.0.0
     */
    public static function dirname(string $path) : string
    {
        return \basename(\dirname($path));
    }

    /**
     * Gets the directory path of a file.
     *
     * @param string $path path of the file to get the directory name for
     *
     * @return string returns the directory name of the file
     *
     * @since 1.0.0
     */
    public static function dirpath(string $path) : string
    {
        return \dirname($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!\is_file($from)) {
            return false;
        }

        if ($overwrite || !\file_exists($to)) {
            if (!Directory::exists(\dirname($to))) {
                Directory::create(\dirname($to), 0755, true);
            }

            if ($overwrite && \file_exists($to)) {
                \unlink($to);
            }

            \copy($from, $to);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        $result = self::copy($from, $to, $overwrite);

        if (!$result) {
            return false;
        }

        return self::delete($from);
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        if (!\file_exists($path)) {
            return false;
        }

        \unlink($path);

        return true;
    }

    /**
     * Gets the directory name of a file.
     *
     * @return string returns the directory name of the file
     *
     * @since 1.0.0
     */
    public function getDirName() : string
    {
        return \basename(\dirname($this->path));
    }

    /**
     * Gets the directory path of a file.
     *
     * @return string returns the directory path of the file
     *
     * @since 1.0.0
     */
    public function getDirPath() : string
    {
        return \dirname($this->path);
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
        if (!\file_exists($path)) {
            if (!Directory::exists(\dirname($path))) {
                Directory::create(\dirname($path), 0755, true);
            }

            if (!\is_writable(\dirname($path))) {
                return false;
            }

           \touch($path);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent() : string
    {
        $contents = \file_get_contents($this->path);

        return $contents === false ? '' : $contents;
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
        return \explode('.', $this->name)[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension() : string
    {
        $extension = \explode('.', $this->name);

        return $extension[1] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() : ContainerInterface
    {
        return new Directory(self::parent($this->path));
    }

    /**
     * {@inheritdoc}
     */
    public function copyNode(string $to, bool $overwrite = false) : bool
    {
        return self::copy($this->path, $to, $overwrite);
    }

    /**
     * {@inheritdoc}
     */
    public function moveNode(string $to, bool $overwrite = false) : bool
    {
        return self::move($this->path, $to, $overwrite);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteNode() : bool
    {
        return self::delete($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function putContent(string $content, int $mode = ContentPutMode::APPEND | ContentPutMode::CREATE) : bool
    {
        return self::put($this->path, $content, $mode);
    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return \explode('.', \basename($path))[0];
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return \basename($path);
    }

    /**
     * Get file extension.
     *
     * @param string $path File path
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function extension(string $path) : string
    {
        $extension = \explode('.', \basename($path));

        return $extension[1] ?? '';
    }
}
