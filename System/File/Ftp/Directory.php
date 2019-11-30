<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\System\File\Ftp
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System\File\Ftp;

use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\DirectoryInterface;
use phpOMS\System\File\FileUtils;
use phpOMS\System\File\Local\Directory as LocalDirectory;
use phpOMS\System\File\PathException;
use phpOMS\Uri\Http;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File\Ftp
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Directory extends FileAbstract implements FtpContainerInterface, DirectoryInterface
{
    /**
     * Directory nodes (files and directories).
     *
     * @var   FileAbstract[]
     * @since 1.0.0
     */
    private array $nodes = [];

    /**
     * Create ftp connection.
     *
     * @param HTTP $http Uri
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function ftpConnect(Http $http)
    {
        $con = \ftp_connect($http->getHost(), $http->getPort());

        if ($con === false) {
            return false;
        }

        \ftp_login($con, $http->getUser(), $http->getPass());

        if ($http->getPath() !== '') {
            \ftp_chdir($con, $http->getPath());
        }

        return $con;
    }

    /**
     * List all files in directory.
     *
     * @param resource $con    FTP connection
     * @param string   $path   Path
     * @param string   $filter Filter
     *
     * @return array<int, string>
     *
     * @since 1.0.0
     */
    public static function list($con, string $path, string $filter = '*') : array
    {
        if (!self::exists($con, $path)) {
            return [];
        }

        $list     = [];
        $path     = \rtrim($path, '\\/');
        $detailed = self::parseRawList($con, $path);

        foreach ($detailed as $key => $item) {
            $list[] = $key;

            if ($item['type'] === 'dir') {
                $list = \array_merge($list, self::list($con, $key));
            }
        }

        /** @var array<int, string> $list */
        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public static function exists($con, string $path) : bool
    {
        return File::exists($con, $path);
    }

    /**
     * Create directory
     *
     * @param resource $con        FTP connection
     * @param string   $path       Path of the resource
     * @param int      $permission Permission
     * @param bool     $recursive  Create recursive in case of subdirectories
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function create($con, string $path, int $permission = 0755, bool $recursive = false) : bool
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

                \ftp_mkdir($con, $part);
                \ftp_chmod($con, $permission, $part);
            }

            \ftp_chdir($con, $part);
        }

        return self::exists($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function size($con, string $dir, bool $recursive = true) : int
    {
        if (!self::exists($con, $dir)) {
            return -1;
        }

        $countSize   = 0;
        $directories = self::parseRawList($con, $dir);

        if ($directories === false) {
            return $countSize;
        }

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
    public static function count($con, string $path, bool $recursive = true, array $ignore = []) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        $size     = 0;
        $files    = self::parseRawList($con, $path);
        $ignore[] = '.';
        $ignore[] = '..';

        if ($files === false) {
            return $size;
        }

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
    public static function delete($con, string $path) : bool
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
    public static function created($con, string $path) : \DateTime
    {
        return self::changed($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function changed($con, string $path) : \DateTime
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
     */
    public static function owner($con, string $path) : string
    {
        if (!self::exists($con, $path)) {
            throw new PathException($path);
        }

        return self::parseRawList($con, self::parent($path))[$path]['user'];
    }

    /**
     * Get detailed file/dir list.
     *
     * @param resource $con  FTP connection
     * @param string   $path Path of the resource
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function parseRawList($con, string $path) : array
    {
        $listData = \ftp_rawlist($con, $path);
        $names    = \ftp_nlist($con, $path);
        $data     = [];

        foreach ($listData as $key => $item) {
            $chunks = \preg_split("/\s+/", $item);
            list(
                $e['permission'],
                $e['number'],
                $e['user'],
                $e['group'],
                $e['size'],
                $e['month'],
                $e['day'],
                $e['time']
            )       = $chunks;

            $e['permission'] = FileUtils::permissionToOctal(\substr($e['permission'], 1));
            $e['type']       = $chunks[0][0] === 'd' ? 'dir' : 'file';

            $data[$names[$key]] = $e;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public static function permission($con, string $path) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        return self::parseRawList($con, self::parent($path))[$path]['permission'];
    }

    /**
     * {@inheritdoc}
     */
    public static function copy($con, string $from, string $to, bool $overwrite = false) : bool
    {
        if (!self::exists($con, $from)) {
            return false;
        }

        $tempName = 'temp' . \mt_rand();
        \mkdir($tempName);
        $download = self::get($con, $from, $tempName . '/' . self::name($from));

        if (!$download) {
            return false;
        }

        $upload = self::put($con, \realpath($tempName) . '/' . self::name($from), $to);

        if (!$upload) {
            return false;
        }

        LocalDirectory::delete($tempName);

        return self::exists($con, $to);
    }

    /**
     * Download file.
     *
     * @param resource $con  FTP connection
     * @param string   $from Path of the resource to copy
     * @param string   $to   Path of the resource to copy to
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function get($con, string $from, string $to) : bool
    {
        if (!self::exists($con, $from)) {
            return false;
        }

        if (!\file_exists($to)) {
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

        return \file_exists($to);
    }

    /**
     * Upload file.
     *
     * @param resource $con  FTP connection
     * @param string   $from Path of the resource to copy
     * @param string   $to   Path of the resource to copy to
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function put($con, string $from, string $to) : bool
    {
        if (!\file_exists($from)) {
            return false;
        }

        if (!self::exists($con, $to)) {
            self::create($con, $to, 0755, true);
        }

        $list = \scandir($from);
        foreach ($list as $key => $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $item = $from . '/' . \ltrim($item, '/');

            if (\is_dir($item)) {
                self::put($con, $item, $to . '/' . self::name($item));
            } else {
                File::put($con, $to . '/' . self::name($item), \file_get_contents($item));
            }
        }

        return self::exists($con, $to);
    }

    /**
     * Move resource to different location.
     *
     * @param resource $con       FTP connection
     * @param string   $from      Path of the resource to move
     * @param string   $to        Path of the resource to move to
     * @param bool     $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function move($con, string $from, string $to, bool $overwrite = false) : bool
    {
        if (!self::exists($con, $from)) {
            return false;
        }

        if ($overwrite && self::exists($con, $to)) {
            self::delete($con, $to);
        } elseif (self::exists($con, $to)) {
            return false;
        }

        $copy = self::copy($con, $from, $to);
        self::delete($con, $from);

        return $copy;
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '', string $invalid = '/[^\w\s\d\.\-_~,;\/\[\]\(\]]/') : string
    {
        return LocalDirectory::sanitize($path, $replace, $invalid);
    }

    /**
     * {@inheritdoc}
     */
    public static function dirname(string $path) : string
    {
        return LocalDirectory::dirname($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function dirpath(string $path) : string
    {
        return LocalDirectory::dirpath($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return LocalDirectory::name($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return LocalDirectory::basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(string $name) : ?ContainerInterface
    {
        return $this->nodes[$name] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        return self::create($this->path, $this->permission, true);

        // todo: add node
    }

    /**
     * {@inheritdoc}
     */
    public function addNode($file) : bool
    {
        $this->count                  += $file->getCount();
        $this->size                   += $file->getSize();
        $this->nodes[$file->getName()] = $file;

        return $file->createNode();
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
    public function rewind() : void
    {
        \reset($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return \current($this->nodes);
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
        return \next($this->nodes);
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
        if ($offset === null) {
            $this->addNode($value);
        } else {
            $this->nodes[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->nodes[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset) : void
    {
        if (isset($this->nodes[$offset])) {
            unset($this->nodes[$offset]);
        }
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param  mixed $offset <p>
     *                       The offset to retrieve.
     *                       </p>
     * @return mixed can return all value types
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }
}
