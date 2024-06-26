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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class File extends FileAbstract implements FileInterface
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

        if (\is_file($this->path)) {
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
        $exists = \is_file($path);

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
        } catch (\Throwable $_) {
            return false; // @codeCoverageIgnore
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
     * @throws PathException
     *
     * @since 1.0.0
     */
    public static function get(string $path) : string
    {
        if (!\is_file($path)) {
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
        return \is_file($path);
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
    public static function size(string $path, bool $recursive = true) : int
    {
        if (!\is_file($path)) {
            return -1;
        }

        return (int) \filesize($path);
    }

    /**
     * {@inheritdoc}
     *
     * @throws PathException
     */
    public static function owner(string $path) : int
    {
        if (!\is_file($path)) {
            throw new PathException($path);
        }

        return (int) \fileowner($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : int
    {
        if (!\is_file($path)) {
            return -1;
        }

        return (int) \fileperms($path);
    }

    /**
     * Multi-byte path info
     *
     * @param string $path Path
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function pathInfo(string $path) : array
    {
        $temp = [];
        \preg_match('#^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^.\\\\/]+?)|))[\\\\/.]*$#m', $path, $temp);

        $info              = [];
        $info['dirname']   = $temp[1] ?? '';
        $info['basename']  = $temp[2] ?? '';
        $info['filename']  = $temp[3] ?? '';
        $info['extension'] = $temp[5] ?? '';

        return $info;
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
        if (!\is_file($from)
            || (!$overwrite && \is_file($to))
        ) {
            return false;
        }

        if (!Directory::exists(\dirname($to))) {
            Directory::create(\dirname($to), 0755, true);
        }

        if ($overwrite && \is_file($to)) {
            \unlink($to);
        }

        \copy($from, $to);

        return true;
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

        self::delete($from);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        if (!\is_file($path)) {
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
     * Check if the file exists
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isExisting() : bool
    {
        return \is_file($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path) : bool
    {
        if (!\is_file($path)) {
            if (!Directory::exists(\dirname($path))) {
                Directory::create(\dirname($path), 0755, true);
            }

            if (!\is_writable(\dirname($path))) {
                return false; // @codeCoverageIgnore
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
        return $this->putContent($content, ContentPutMode::APPEND);
    }

    /**
     * {@inheritdoc}
     */
    public function prependContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::PREPEND);
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
        return $this->parent ?? new Directory(self::parent($this->path));
    }

    /**
     * Get directory of the file
     *
     * @return ContainerInterface
     *
     * @since 1.0.0
     */
    public function getDirectory() : ContainerInterface
    {
        return $this->parent ?? new Directory(self::dirpath($this->path));
    }

    /**
     * {@inheritdoc}
     */
    public function copyNode(string $to, bool $overwrite = false) : bool
    {
        $newParent = $this->findNode(\dirname($to));

        $state = self::copy($this->path, $to, $overwrite);

        /** @var null|Directory $newParent */
        if ($newParent !== null) {
            $newParent->addNode(new self($to));
        }

        return $state;
    }

    /**
     * {@inheritdoc}
     */
    public function moveNode(string $to, bool $overwrite = false) : bool
    {
        $state = $this->copyNode($to, $overwrite);

        return $state && $this->deleteNode();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteNode() : bool
    {
        if (isset($this->parent)) {
            unset($this->parent->nodes[$this->getBasename()]);
        }

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
