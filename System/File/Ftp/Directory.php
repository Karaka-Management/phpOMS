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

use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\DirectoryInterface;
use phpOMS\System\File\FileUtils;
use phpOMS\System\File\Local\Directory as LocalDirectory;
use phpOMS\System\File\PathException;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\StringUtils;

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
class Directory extends FileAbstract implements DirectoryInterface
{
    /**
     * Filter for directory listing
     *
     * @var string
     * @since 1.0.0
     */
    //private string $filter = '*';

    /**
     * Directory nodes (files and directories).
     *
     * @var array<string, ContainerInterface>
     * @since 1.0.0
     */
    public array $nodes = [];

    /**
     * Create ftp connection.
     *
     * @param HttpUri $http Uri
     *
     * @return null|\FTP\Connection
     *
     * @since 1.0.0
     */
    public static function ftpConnect(HttpUri $http) : ?\FTP\Connection
    {
        $con = \ftp_connect($http->host, $http->port, 10);
        if ($con === false) {
            return null;
        }

        $status = \ftp_login($con, $http->user, $http->pass);
        if ($status === false) {
            return null;
        }

        if ($http->getPath() !== '') {
            @\ftp_chdir($con, $http->getPath());
        }

        return $con;
    }

    /**
     * Constructor.
     *
     * @param HttpUri         $uri        Uri
     * @param bool            $initialize Should get initialized during construction
     * @param \FTP\Connection $con        Connection
     *
     * @since 1.0.0
     */
    public function __construct(HttpUri $uri, bool $initialize = true, \FTP\Connection $con = null)
    {
        $this->uri = $uri;
        $this->con = $con ?? self::ftpConnect($uri);

        //$this->filter = \ltrim($filter, '\\/');
        parent::__construct($uri->getPath());

        if ($initialize && $this->con !== null && self::exists($this->con, $this->path)) {
            $this->index();
        }
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

        if ($this->con === null) {
            return;
        }

        $list = self::list($this->con, $this->path);

        foreach ($list as $filename) {
            if (!StringUtils::endsWith(\trim($filename), '.')) {
                $uri = clone $this->uri;
                $uri->setPath($filename);

                $file = \ftp_size($this->con, $filename) === -1
                    ? new self($uri, false, $this->con)
                    : new File($uri, $this->con);

                $file->parent = $this;

                $this->addNode($file);
            }
        }
    }

    /**
     * List all files in directory.
     *
     * @param \FTP\Connection $con       FTP connection
     * @param string          $path      Path
     * @param string          $filter    Filter
     * @param bool            $recursive Recursive list
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public static function list(\FTP\Connection $con, string $path, string $filter = '*', bool $recursive = false) : array
    {
        if (!self::exists($con, $path)) {
            return [];
        }

        $list     = [];
        $path     = \rtrim($path, '\\/');
        $detailed = self::parseRawList($con, $path);

        foreach ($detailed as $key => $item) {
            if ($item['type'] === 'dir' && $recursive) {
                $list = \array_merge($list, self::list($con, $key, $filter, $recursive));
            }

            if ($filter !== '*' && \preg_match($filter, $key) !== 1) {
                continue;
            }

            $list[] = $key;
        }

        /** @var string[] $list */
        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(\FTP\Connection $con, string $path) : bool
    {
        return File::exists($con, $path);
    }

    /**
     * Create directory
     *
     * @param \FTP\Connection $con        FTP connection
     * @param string          $path       Path of the resource
     * @param int             $permission Permission
     * @param bool            $recursive  Create recursive in case of subdirectories
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function create(\FTP\Connection $con, string $path, int $permission = 0755, bool $recursive = false) : bool
    {
        if (self::exists($con, $path)) {
            return false;
        }

        $parts = \explode('/', $path);
        if ($parts[0] === '') {
            $parts[0] = '/';
        }

        $depth = \count($parts);

        $currentPath = '';
        foreach ($parts as $key => $part) {
            $currentPath .= ($currentPath !== '' && $currentPath !== '/' ? '/' : '') . $part;

            if (!self::exists($con, $currentPath)) {
                if (!$recursive && $key < $depth - 1) {
                    return false;
                }

                $status = @\ftp_mkdir($con, $part);
                if ($status === false) {
                    return false;
                }

                \ftp_chmod($con, $permission, $part);
            }

            \ftp_chdir($con, $part);
        }

        return self::exists($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function size(\FTP\Connection $con, string $dir, bool $recursive = true) : int
    {
        if (!self::exists($con, $dir)) {
            return -1;
        }

        $countSize   = 0;
        $directories = self::parseRawList($con, $dir);

        foreach ($directories as $key => $filename) {
            if ($key === '..' || $key === '.') {
                continue;
            }

            if ($filename['type'] === 'dir' && $recursive) {
                $countSize += self::size($con, $key, $recursive);
            } elseif ($filename['type'] === 'file') {
                $countSize += \ftp_size($con, $key);
            }
        }

        return $countSize;
    }

    /**
     * {@inheritdoc}
     */
    public static function count(\FTP\Connection $con, string $path, bool $recursive = true, array $ignore = []) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        $size     = 0;
        $files    = self::parseRawList($con, $path);
        $ignore[] = '.';
        $ignore[] = '..';

        foreach ($files as $key => $t) {
            if (\in_array($key, $ignore)) {
                continue;
            }
            if ($t['type'] === 'dir') {
                if ($recursive) {
                    $size += self::count($con, $key, true, $ignore);
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
    public static function delete(\FTP\Connection $con, string $path) : bool
    {
        $path = \rtrim($path, '\\/');

        if (!self::exists($con, $path)) {
            return false;
        }

        $list = self::parseRawList($con, $path);

        foreach ($list as $key => $item) {
            if ($item['type'] === 'dir') {
                self::delete($con, $key);
            } else {
                File::delete($con, $key);
            }
        }

        return \ftp_rmdir($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
        return LocalDirectory::parent($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function created(\FTP\Connection $con, string $path) : \DateTime
    {
        return self::changed($con, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @throws PathException
     */
    public static function changed(\FTP\Connection $con, string $path) : \DateTime
    {
        if (!self::exists($con, $path)) {
            throw new PathException($path);
        }

        $changed = new \DateTime();
        $time    = \ftp_mdtm($con, $path);

        $changed->setTimestamp($time === false ? 0 : $time);

        return $changed;
    }

    /**
     * {@inheritdoc}
     *
     * @throws PathException
     */
    public static function owner(\FTP\Connection $con, string $path) : string
    {
        if (!self::exists($con, $path)) {
            throw new PathException($path);
        }

        return self::parseRawList($con, self::parent($path))[$path]['user'];
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() : string
    {
        if ($this->con === null) {
            return '';
        }

        $this->owner = self::parseRawList($this->con, self::parent($this->path))[$this->path]['user'];

        return $this->owner;
    }

    /**
     * Get detailed file/dir list.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return array<string, array{permission:int, number:string, user:string, group:string, size:string, month:string, day:string, time:string, type:string}>
     *
     * @since 1.0.0
     */
    public static function parseRawList(\FTP\Connection $con, string $path) : array
    {
        $listData = \ftp_rawlist($con, $path);
        $names    = \ftp_nlist($con, $path);
        $data     = [];

        if ($names === false || $listData === false) {
            return [];
        }

        foreach ($listData as $key => $item) {
            $chunks = \preg_split("/\s+/", $item);

            if ($chunks === false) {
                continue;
            }

            $e = [
                'permission' => '',
                'number'     => '',
                'user'       => '',
                'group'      => '',
                'size'       => '',
                'month'      => '',
                'day'        => '',
                'time'       => '',
            ];

            list(
                $e['permission'],
                $e['number'],
                $e['user'],
                $e['group'],
                $e['size'],
                $e['month'],
                $e['day'],
                $e['time']
            ) = $chunks;

            $e['permission'] = FileUtils::permissionToOctal(\substr($e['permission'], 1));
            $e['type']       = $chunks[0][0] === 'd' ? 'dir' : 'file';

            $data[$names[$key]] = $e;
        }

        /** @var array<string, array{permission:int, number:string, user:string, group:string, size:string, month:string, day:string, time:string, type:string}> */
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(\FTP\Connection $con, string $path) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        return self::parseRawList($con, self::parent($path))[$path]['permission'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPermission() : int
    {
        if ($this->con === null) {
            return 0;
        }

        $this->permission = self::parseRawList($this->con, self::parent($this->path))[$this->path]['permission'];

        return $this->permission;
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(\FTP\Connection $con, string $from, string $to, bool $overwrite = false) : bool
    {
        if (!self::exists($con, $from)
            || (!$overwrite && self::exists($con, $to))
        ) {
            return false;
        }

        $tempName = \sys_get_temp_dir() . '/' . \uniqid('omsftp_');
        $status   = @\mkdir($tempName);

        if ($status === false) {
            return false;
        }

        $download = self::get($con, $from, $tempName . '/' . self::name($from));
        if (!$download) {
            LocalDirectory::delete($tempName);

            return false;
        }

        $upload = self::put($con, $tempName . '/' . self::name($from), $to);
        if (!$upload) {
            LocalDirectory::delete($tempName);

            return false;
        }

        LocalDirectory::delete($tempName);

        return self::exists($con, $to);
    }

    /**
     * Download file.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $from Path of the resource to copy
     * @param string          $to   Path of the resource to copy to
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function get(\FTP\Connection $con, string $from, string $to) : bool
    {
        if (!self::exists($con, $from)) {
            return false;
        }

        if (!\is_dir($to)) {
            \mkdir($to);
        }

        $list = self::parseRawList($con, $from);
        foreach ($list as $key => $item) {
            if ($item['type'] === 'dir') {
                self::get($con, $key, $to . '/' . self::name($key));
            } else {
                \file_put_contents($to . '/' . self::name($key), File::get($con, $key));
            }
        }

        return \is_dir($to);
    }

    /**
     * Upload file.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $from Path of the resource to copy
     * @param string          $to   Path of the resource to copy to
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function put(\FTP\Connection $con, string $from, string $to) : bool
    {
        if (!\is_dir($from)) {
            return false;
        }

        if (!self::exists($con, $to)) {
            self::create($con, $to, 0755, true);
        }

        $list = \scandir($from);
        if ($list === false) {
            return false;
        }

        foreach ($list as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $item = $from . '/' . \ltrim($item, '/');

            if (\is_dir($item)) {
                self::put($con, $item, $to . '/' . self::name($item));
            } else {
                $content = \file_get_contents($item);

                if ($content !== false) {
                    File::put($con, $to . '/' . self::name($item), $content);
                }
            }
        }

        return self::exists($con, $to);
    }

    /**
     * Move resource to different location.
     *
     * @param \FTP\Connection $con       FTP connection
     * @param string          $from      Path of the resource to move
     * @param string          $to        Path of the resource to move to
     * @param bool            $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function move(\FTP\Connection $con, string $from, string $to, bool $overwrite = false) : bool
    {
        if (!self::exists($con, $from)
            || (!$overwrite && self::exists($con, $to))
        ) {
            return false;
        }

        if ($overwrite && self::exists($con, $to)) {
            self::delete($con, $to);
        }

        $copy = self::copy($con, $from, $to);

        if (!$copy) {
            return false;
        }

        self::delete($con, $from);

        return true;
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
    public static function name(string $path) : string
    {
        return \basename($path);
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
    public function getNode(string $name) : ?ContainerInterface
    {
        $name = isset($this->nodes[$name]) ? $name : $this->path . '/' . $name;

        if (isset($this->nodes[$name]) && $this->nodes[$name] instanceof self) {
            $this->nodes[$name]->index();
        }

        return $this->nodes[$name] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        if ($this->con === null) {
            return false;
        }

        return self::create($this->con, $this->path, $this->permission, true);
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
     * {@inheritdoc}
     */
    public function getParent() : ContainerInterface
    {
        $uri = clone $this->uri;
        $uri->setPath(self::parent($this->path));

        return $this->parent ?? new self($uri, true, $this->con);
    }

    /**
     * {@inheritdoc}
     */
    public function copyNode(string $to, bool $overwrite = false) : bool
    {
        if ($this->con === null) {
            return false;
        }

        $newParent = $this->findNode($to);

        $state = self::copy($this->con, $this->path, $to, $overwrite);

        /** @var null|Directory $newParent */
        if ($newParent !== null) {
            $uri = clone $this->uri;
            $uri->setPath($to);

            $newParent->addNode(new self($uri));
        }

        return $state;
    }

    /**
     * {@inheritdoc}
     */
    public function moveNode(string $to, bool $overwrite = false) : bool
    {
        if ($this->con === null) {
            return false;
        }

        $state = $this->copyNode($to, $overwrite);

        return $state && $this->deleteNode();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteNode() : bool
    {
        if ($this->con === null) {
            return false;
        }

        if (isset($this->parent)) {
            unset($this->parent->nodes[$this->getBasename()]);
        }

        return self::delete($this->con, $this->path);
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
    public function next() : void
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
    public function offsetSet(mixed $offset, mixed $value) : void
    {
        /** @var \phpOMS\System\File\ContainerInterface $value */
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
    public function offsetExists(mixed $offset) : bool
    {
        $offset = isset($this->nodes[$offset]) ? $offset : $this->path . '/' . $offset;

        return isset($this->nodes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset(mixed $offset) : void
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
    public function offsetGet(mixed $offset) : mixed
    {
        if (isset($this->nodes[$offset]) && $this->nodes[$offset] instanceof self) {
            $this->nodes[$offset]->index();
        }

        return $this->nodes[$offset] ?? null;
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

    /**
     * List all files by extension directory.
     *
     * @param \FTP\Connection $con       FTP connection
     * @param string          $path      Path
     * @param string          $extension Extension
     * @param string          $exclude   Pattern to exclude
     * @param bool            $recursive Recursive
     *
     * @return array<array|string>
     *
     * @since 1.0.0
     */
    public static function listByExtension(\FTP\Connection $con, string $path, string $extension = '', string $exclude = '', bool $recursive = false) : array
    {
        $list = [];
        $path = \rtrim($path, '\\/');

        if (!\is_dir($path)) {
            return $list;
        }

        $files = self::list($con, $path, empty($extension) ? '*' : '/.*\.' . $extension . '$/', $recursive);

        foreach ($files as $file) {
            if (!empty($exclude) && \preg_match('/' . $exclude . '/', $file) === 1) {
                continue;
            }

            $list[] = $file;
        }

        return $list;
    }
}
