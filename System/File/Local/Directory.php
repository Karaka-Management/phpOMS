<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
use phpOMS\System\File\DirectoryInterface;
use phpOMS\System\File\PathException;
use phpOMS\Utils\StringUtils;

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
final class Directory extends FileAbstract implements DirectoryInterface
{
    /**
     * Directory list filter.
     *
     * @var string
     * @since 1.0.0
     */
    private string $filter = '*';

    /**
     * Directory nodes (files and directories).
     *
     * @var array<string, ContainerInterface>
     * @since 1.0.0
     */
    private array $nodes = [];

    /**
     * Constructor.
     *
     * @param string $path       Path
     * @param string $filter     Filter
     * @param bool   $initialize Should get initialized during construction
     *
     * @since 1.0.0
     */
    public function __construct(string $path, string $filter = '*', bool $initialize = true)
    {
        $this->filter = \ltrim($filter, '\\/');
        parent::__construct($path);

        if ($initialize && \is_dir($this->path)) {
            $this->index();
        }
    }

    /**
     * List all files in directory recursively.
     *
     * @param string $path      Path
     * @param string $filter    Filter
     * @param bool   $recursive Recursive list
     *
     * @return string[] Array of files and directory with relative path to $path
     *
     * @since 1.0.0
     */
    public static function list(string $path, string $filter = '*', bool $recursive = false) : array
    {
        if (!\is_dir($path)) {
            return [];
        }

        $list = [];
        $path = \rtrim($path, '\\/');

        $iterator = $recursive
            ? new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST)
            : new \DirectoryIterator($path);

        if ($filter !== '*') {
            $iterator = new \RegexIterator($iterator, '/' . $filter . '/i', \RecursiveRegexIterator::GET_MATCH);
        }

        foreach ($iterator as $item) {
            if (!$recursive && $item->isDot()) {
                continue;
            }

            $list[] = \substr(\str_replace('\\', '/', $iterator->getPathname()), \strlen($path) + 1);
        }

        /** @var string[] $list */
        return $list;
    }

    /**
     * List all files by extension directory.
     *
     * @param string $path      Path
     * @param string $extension Extension
     * @param string $exclude   Pattern to exclude
     * @param bool   $recursive Recursive
     *
     * @return array<array|string>
     *
     * @since 1.0.0
     */
    public static function listByExtension(string $path, string $extension = '', string $exclude = '', bool $recursive = true) : array
    {
        $list = [];
        $path = \rtrim($path, '\\/');

        if (!\is_dir($path)) {
            return $list;
        }

        $iterator = $recursive
            ? new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST)
            : new \DirectoryIterator($path);

        foreach ($iterator as $item) {
            if (!$recursive && $item->isDot()) {
                continue;
            }

            $subPath = \substr($iterator->getPathname(), \strlen($path) + 1);

            if ((empty($extension) || $item->getExtension() === $extension)
                && (empty($exclude) || (!(bool) \preg_match('/' . $exclude . '/', $subPath)))
            ) {
                $list[] = \str_replace('\\', '/', $subPath);
            }
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function index() : void
    {
        if ($this->isInitialized) {
            return;
        }

        parent::index();

        $files = \glob($this->path . \DIRECTORY_SEPARATOR . $this->filter);
        if ($files === false) {
            return;
        }

        foreach ($files as $filename) {
            if (!StringUtils::endsWith(\trim($filename), '.')) {
                $file = \is_dir($filename) ? new self($filename, '*', false) : new File($filename);

                $this->addNode($file);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addNode(ContainerInterface $node) : self
    {
        $this->count                      += $node->getCount();
        $this->size                       += $node->getSize();
        $this->nodes[$node->getBasename()] = $node;

        $node->createNode();

        return $this;
    }

    /**
     * Create node
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function createNode() : bool
    {
        return self::create($this->path, $this->permission, true);
    }

    /**
     * {@inheritdoc}
     */
    public static function size(string $dir, bool $recursive = true) : int
    {
        if (!\is_dir($dir) || !\is_readable($dir)) {
            return -1;
        }

        $countSize   = 0;
        $directories = \scandir($dir);

        if ($directories === false) {
            return $countSize; // @codeCoverageIgnore
        }

        foreach ($directories as $key => $filename) {
            if ($filename === '..' || $filename === '.') {
                continue;
            }

            $path = $dir . '/' . $filename;
            if (\is_dir($path) && $recursive) {
                $countSize += self::size($path, $recursive);
            } elseif (\is_file($path)) {
                $countSize += \filesize($path);
            }
        }

        return $countSize;
    }

    /**
     * Get amount of sub-resources.
     *
     * A file will always return 1 as it doesn't have any sub-resources.
     *
     * @param string   $path      Path of the resource
     * @param bool     $recursive Should count also sub-sub-resources
     * @param string[] $ignore    Ignore files
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function count(string $path, bool $recursive = true, array $ignore = []) : int
    {
        if (!\is_dir($path)) {
            return -1;
        }

        $size     = 0;
        $files    = \scandir($path);
        $ignore[] = '.';
        $ignore[] = '..';

        if ($files === false) {
            return $size; // @codeCoverageIgnore
        }

        foreach ($files as $t) {
            if (\in_array($t, $ignore)) {
                continue;
            }
            if (\is_dir(\rtrim($path, '/') . '/' . $t)) {
                if ($recursive) {
                    $size += self::count(\rtrim($path, '/') . '/' . $t, true, $ignore);
                }
            } else {
                ++$size;
            }
        }

        return $size;
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        if (empty($path) || !\is_dir($path)) {
            return false;
        }

        $files = \scandir($path);

        if ($files === false) {
            return false; // @codeCoverageIgnore
        }

        /* Removing . and .. */
        unset($files[1]);
        unset($files[0]);

        foreach ($files as $file) {
            if (\is_dir($path . '/' . $file)) {
                self::delete($path . '/' . $file);
            } else {
                \unlink($path . '/' . $file);
            }
        }

        \rmdir($path);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
        $path = \explode('/', \str_replace('\\', '/', $path));
        \array_pop($path);

        return \implode('/', $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {
        if (!\is_dir($path)) {
            throw new PathException($path);
        }

        return (int) \fileowner($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : int
    {
        if (!\is_dir($path)) {
            return -1;
        }

        return (int) \fileperms($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!\is_dir($from)
            || (!$overwrite && \is_dir($to))
        ) {
            return false;
        }

        if (!\is_dir($to)) {
            self::create($to, 0755, true);
        } elseif ($overwrite && \is_dir($to)) {
            self::delete($to);
            self::create($to, 0755, true);
        }

        foreach ($iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($from, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            /** @var \RecursiveDirectoryIterator $iterator */
            $subPath = $iterator->getSubPathname();

            if ($item->isDir()) {
                \mkdir($to . '/' . $subPath);
            } else {
                \copy($from . '/' . $subPath, $to . '/' . $subPath);
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!\is_dir($from)
            || (!$overwrite && \is_dir($to))
        ) {
            return false;
        }

        if ($overwrite && \is_dir($to)) {
            self::delete($to);
        }

        if (!self::exists(self::parent($to))) {
            self::create(self::parent($to), 0755, true);
        }

        \rename($from, $to);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(string $path) : bool
    {
        return \is_dir($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '', string $invalid = '/[^\w\s\d\.\-_~,;:\[\]\(\]\/]/') : string
    {
        return \preg_replace($invalid, $replace, $path) ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(string $name) : ?ContainerInterface
    {
        $name = isset($this->nodes[$name]) ? $name : $this->path . '/' . $name;

        if (isset($this->nodes[$name]) && $this->nodes[$name] instanceof self) {
            $this->nodes[$name]->index();
        }

        return $this->nodes[$name] ?? null;
    }

    /**
     * Check if the child node exists
     *
     * @param string $name Child node name. If empty checks if this node exists.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isExisting(string $name = null) : bool
    {
        if ($name === null) {
            return \is_dir($this->path);
        }

        $name = isset($this->nodes[$name]) ? $name : $this->path . '/' . $name;

        return isset($this->nodes[$name]);
    }

    /**
     * Create directory
     *
     * @param string $path       Path of the resource
     * @param int    $permission Permission
     * @param bool   $recursive  Create recursive in case of subdirectories
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function create(string $path, int $permission = 0755, bool $recursive = false) : bool
    {
        if (!\is_dir($path)) {
            if (!$recursive && !\is_dir(self::parent($path))) {
                return false;
            }

            try {
                \mkdir($path, $permission, $recursive);
            } catch (\Throwable $t) {
                return false; // @codeCoverageIgnore
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind() : void
    {
        \reset($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function current() : ContainerInterface
    {
        $current = \current($this->nodes);
        if ($current instanceof self) {
            $current->index();
        }

        return $current === false ? $this : $current;
    }

    /**
     * {@inheritdoc}
     */
    public function key() : ?string
    {
        return \key($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $next = \next($this->nodes);
        if ($next instanceof self) {
            $next->index();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function valid() : bool
    {
        $key = \key($this->nodes);

        return ($key !== null && $key !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value) : void
    {
        if ($offset === null || !isset($this->nodes[$offset])) {
            $this->addNode($value);
        } else {
            $this->nodes[$offset]->deleteNode();
            $this->addNode($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $offset = isset($this->nodes[$offset]) ? $offset : $this->path . '/' . $offset;

        return isset($this->nodes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset) : void
    {
        $offset = isset($this->nodes[$offset]) ? $offset : $this->path . '/' . $offset;

        if (isset($this->nodes[$offset])) {
            $this->nodes[$offset]->deleteNode();

            unset($this->nodes[$offset]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return \basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function dirname(string $path) : string
    {
        return \basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function dirpath(string $path) : string
    {
        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return \basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() : ContainerInterface
    {
        return new self(self::parent($this->path));
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
        // @todo: update parent

        return self::delete($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (isset($this->nodes[$offset]) && $this->nodes[$offset] instanceof self) {
            $this->nodes[$offset]->index();
        }

        return $this->nodes[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getList() : array
    {
        $pathLength = \strlen($this->path);
        $content    = [];

        foreach ($this->nodes as $node) {
            $content[] = \substr($node->getPath(), $pathLength + 1);
        }

        return $content;
    }
}
