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
final class Directory extends FileAbstract implements DirectoryInterface, LocalContainerInterface
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
     * @var FileAbstract[]
     * @since 1.0.0
     */
    private array $nodes = [];

    /**
     * Is directory initialized
     *
     * @var bool
     * @since 1.0.0
     */
    private bool $isInitialized = false;

    /**
     * Constructor.
     *
     * @param string $path   Path
     * @param string $filter Filter
     *
     * @since 1.0.0
     */
    public function __construct(string $path, string $filter = '*', bool $initialize = true)
    {
        $this->filter = \ltrim($filter, '\\/');
        parent::__construct($path);

        if ($initialize && \file_exists($this->path)) {
            $this->index();
        }
    }

    /**
     * List all files in directory recursively.
     *
     * @param string $path   Path
     * @param string $filter Filter
     *
     * @return string[] Array of files and directory with relative path to $path
     *
     * @since 1.0.0
     */
    public static function list(string $path, string $filter = '*') : array
    {
        if (!\file_exists($path)) {
            return [];
        }

        $list     = [];
        $path     = \rtrim($path, '\\/');
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        if ($filter !== '*') {
            $iterator = new \RegexIterator($iterator, '/' . $filter . '/i', \RecursiveRegexIterator::GET_MATCH);
        }

        foreach ($iterator as $item) {
            $list[] = \str_replace('\\', '/', $iterator->getSubPathname());
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
     *
     * @return array<array|string>
     *
     * @since 1.0.0
     */
    public static function listByExtension(string $path, string $extension = '', string $exclude = '') : array
    {
        $list = [];
        $path = \rtrim($path, '\\/');

        if (!\file_exists($path)) {
            return $list;
        }

        foreach ($iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ((empty($extension) || $item->getExtension() === $extension)
                && (empty($exclude) || (!(bool) \preg_match('/' . $exclude . '/', $iterator->getSubPathname())))
            ) {
                $list[] = \str_replace('\\', '/', $iterator->getSubPathname());
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

        $this->isInitialized = true;
        parent::index();

        foreach (\glob($this->path . \DIRECTORY_SEPARATOR . $this->filter) as $filename) {
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
        if (!\file_exists($dir) || !\is_readable($dir)) {
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
        if (!\file_exists($path)) {
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
        if (empty($path) || !\file_exists($path)) {
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
     *
     * @todo Orange-Management/phpOMS#??? [p:low] [t:optimization] [d:beginner] [t:question]
     *  Consider to move this to fileAbastract since it should be the same for file and directory?
     */
    public static function created(string $path) : \DateTime
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $created = new \DateTime('now');
        $time    = \filemtime($path);

        $created->setTimestamp($time === false ? 0 : $time);

        return $created;
    }

    /**
     * {@inheritdoc}
     */
    public static function changed(string $path) : \DateTime
    {
        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        $changed = new \DateTime();
        $time    = \filectime($path);

        $changed->setTimestamp($time === false ? 0 : $time);

        return $changed;
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
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!\is_dir($from)
            || (!$overwrite && \file_exists($to))
        ) {
            return false;
        }

        if (!\file_exists($to)) {
            self::create($to, 0755, true);
        } elseif ($overwrite && \file_exists($to)) {
            self::delete($to);
            self::create($to, 0755, true);
        }

        foreach ($iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($from, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                \mkdir($to . '/' . $iterator->getSubPathname());
            } else {
                \copy($from . '/' . $iterator->getSubPathname(), $to . '/' . $iterator->getSubPathname());
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!\is_dir($from)) {
            return false;
        }

        if (!$overwrite && \file_exists($to)) {
            return false;
        } elseif ($overwrite && \file_exists($to)) {
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
        return \file_exists($path);
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
            return \file_exists($this->path);
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
        if (!\file_exists($path)) {
            if (!$recursive && !\file_exists(self::parent($path))) {
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
    public function current()
    {
        $current = \current($this->nodes);

        if (isset($current) && $current instanceof self) {
            $current->index();
        }

        return $current;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return \key($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $next = \next($this->nodes);

        if (isset($next) && $next instanceof self) {
            $next->index();
        }

        return $next;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
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
