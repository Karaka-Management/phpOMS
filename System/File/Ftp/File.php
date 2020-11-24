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
use phpOMS\System\File\ContentPutMode;
use phpOMS\System\File\FileInterface;
use phpOMS\System\File\Local\Directory as LocalDirectory;
use phpOMS\System\File\Local\File as LocalFile;
use phpOMS\System\File\PathException;
use phpOMS\Uri\HttpUri;

/**
 * Filesystem class.
 *
 * Performing operations on the file system.
 *
 * All static implementations require a path/uri in the following form: ftp://user:pass@domain.com/path/subpath...
 *
 * @package phpOMS\System\File\Ftp
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class File extends FileAbstract implements FileInterface
{
    /**
     * Create ftp connection
     *
     * @param HttpUri       $uri Ftp uri/path including username and password
     * @param null|resource $con Connection
     *
     * @since 1.0.0
     */
    public function __construct(HttpUri $uri, $con = null)
    {
        $this->uri = $uri;
        $this->con = $con ?? self::ftpConnect($this->uri);

        parent::__construct($uri->getPath());

        if (self::exists($this->con, $this->path)) {
            $this->index();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function index() : void
    {
        parent::index();

        $this->size = (int) \ftp_size($this->con, $this->path);
    }

    /**
     * Create ftp connection.
     *
     * @param HttpUri $http Uri
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function ftpConnect(HttpUri $http)
    {
        $con = \ftp_connect($http->host, $http->port, 10);

        if ($con === false) {
            return false;
        }

        \ftp_login($con, $http->user, $http->pass);

        if ($http->getPath() !== '') {
            @\ftp_chdir($con, $http->getPath());
        }

        return $con;
    }

    /**
     * {@inheritdoc}
     */
    public static function exists($con, string $path) : bool
    {
        if ($path === '/') {
            return true;
        }

        $parent = LocalDirectory::parent($path);
        $list   = \ftp_nlist($con, $parent === '' ? '/' : $parent);

        if ($list === false) {
            return false;
        }

        $pathName = LocalDirectory::name($path);
        foreach ($list as $item) {
            if ($pathName === LocalDirectory::name($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function put($con, string $path, string $content, int $mode = ContentPutMode::REPLACE | ContentPutMode::CREATE) : bool
    {
        $exists = self::exists($con, $path);

        if ((ContentPutMode::hasFlag($mode, ContentPutMode::APPEND) && $exists)
            || (ContentPutMode::hasFlag($mode, ContentPutMode::PREPEND) && $exists)
            || (ContentPutMode::hasFlag($mode, ContentPutMode::REPLACE) && $exists)
            || (!$exists && ContentPutMode::hasFlag($mode, ContentPutMode::CREATE))
        ) {
            $tmpFile = 'file' . \mt_rand();
            if (ContentPutMode::hasFlag($mode, ContentPutMode::APPEND) && $exists) {
                \file_put_contents($tmpFile, self::get($con, $path) . $content);
            } elseif (ContentPutMode::hasFlag($mode, ContentPutMode::PREPEND) && $exists) {
                \file_put_contents($tmpFile, $content . self::get($con, $path));
            } else {
                if (!Directory::exists($con, \dirname($path))) {
                    Directory::create($con, \dirname($path), 0755, true);
                }

                \file_put_contents($tmpFile, $content);
            }

            \ftp_put($con, $path, $tmpFile, \FTP_BINARY);
            \ftp_chmod($con, 0755, $path);
            \unlink($tmpFile);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function get($con, string $path) : string
    {
        if (!self::exists($con, $path)) {
            throw new PathException($path);
        }

        $fp = \fopen('php://temp', 'r+');
        if ($fp === false) {
            return '';
        }

        $content = '';
        if (\ftp_fget($con, $fp, $path, \FTP_BINARY, 0)) {
            \rewind($fp);
            $content = \stream_get_contents($fp);
        }

        return $content === false ? '' : $content;
    }

    /**
     * {@inheritdoc}
     */
    public static function count($con, string $path, bool $recursive = true, array $ignore = []) : int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public static function set($con, string $path, string $content) : bool
    {
        return self::put($con, $path, $content, ContentPutMode::REPLACE | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function append($con, string $path, string $content) : bool
    {
        return self::put($con, $path, $content, ContentPutMode::APPEND | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function prepend($con, string $path, string $content) : bool
    {
        return self::put($con, $path, $content, ContentPutMode::PREPEND | ContentPutMode::CREATE);
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
        return LocalFile::sanitize($path, $replace, $invalid);
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
    public static function size($con, string $path, bool $recursive = true) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        return \ftp_size($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner($con, string $path) : string
    {
        if (!self::exists($con, $path)) {
            throw new PathException($path);
        }

        return Directory::parseRawList($con, self::dirpath($path))[$path]['user'];
    }

    /**
     * {@inheritdoc}
     */
    public static function permission($con, string $path) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        return Directory::parseRawList($con, self::dirpath($path))[$path]['permission'];
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
        return LocalFile::dirname($path);
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
        return LocalFile::dirpath($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function copy($con, string $from, string $to, bool $overwrite = false) : bool
    {
        if (!self::exists($con, $from)
            || (!$overwrite && self::exists($con, $to))
        ) {
            return false;
        }

        $download = self::get($con, $from);
        $upload   = self::put($con, $to, $download);

        if (!$upload) {
            return false;
        }

        return self::exists($con, $to);
    }

    /**
     * {@inheritdoc}
     */
    public static function move($con, string $from, string $to, bool $overwrite = false) : bool
    {
        $result = self::copy($con, $from, $to, $overwrite);

        if (!$result) {
            return false;
        }

        self::delete($con, $from);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function delete($con, string $path) : bool
    {
        if (!self::exists($con, $path)) {
            return false;
        }

        return \ftp_delete($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function create($con, string $path) : bool
    {
        return self::put($con, $path, '', ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return LocalFile::name($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return LocalFile::basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function extension(string $path) : string
    {
        return LocalFile::extension($path);
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
        return self::exists($this->con, $this->path);
    }

    /**
     * Get the parent path of the resource.
     *
     * The parent resource path is always a directory.
     *
     * @return ContainerInterface
     *
     * @since 1.0.0
     */
    public function getParent() : ContainerInterface
    {
        $uri = clone $this->uri;
        $uri->setPath(self::parent($this->path));

        return new Directory($uri, '*', true, $this->con);
    }

    /**
     * Create resource at destination path.
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function createNode() : bool
    {
        return self::create($this->con, $this->uri->getPath());
    }

    /**
     * Copy resource to different location.
     *
     * @param string $to        Path of the resource to copy to
     * @param bool   $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function copyNode(string $to, bool $overwrite = false) : bool
    {
        return self::copy($this->con, $this->path, $to, $overwrite);
    }

    /**
     * Move resource to different location.
     *
     * @param string $to        Path of the resource to move to
     * @param bool   $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function moveNode(string $to, bool $overwrite = false) : bool
    {
        return self::move($this->con, $this->path, $to, $overwrite);
    }

    /**
     * Delete resource at destination path.
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function deleteNode() : bool
    {
        return self::delete($this->con, $this->path);
    }

    /**
     * Save content to file.
     *
     * @param string $content Content to save in file
     * @param int    $mode    Mode (overwrite, append)
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function putContent(string $content, int $mode = ContentPutMode::APPEND | ContentPutMode::CREATE) : bool
    {
        return self::put($this->con, $this->path, $content, $mode);
    }

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::REPLACE | ContentPutMode::CREATE);
    }

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function appendContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::APPEND);
    }

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function prependContent(string $content) : bool
    {
        return $this->putContent($content, ContentPutMode::PREPEND);
    }

    /**
     * Get content from file.
     *
     * @return string Content of file
     *
     * @since 1.0.0
     */
    public function getContent() : string
    {
        return self::get($this->con, $this->path);
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
     * Get directory of the file
     *
     * @return ContainerInterface
     *
     * @since 1.0.0
     */
    public function getDirectory() : ContainerInterface
    {
        $uri = clone $this->uri;
        $uri->setPath(self::dirpath($this->path));

        return new Directory($uri, '*', true, $this->con);
    }
}
