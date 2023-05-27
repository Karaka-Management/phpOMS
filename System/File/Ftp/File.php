<?php
/**
 * Karaka
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class File extends FileAbstract implements FileInterface
{
    /**
     * Create ftp connection
     *
     * @param HttpUri         $uri Ftp uri/path including username and password
     * @param \FTP\Connection $con Connection
     *
     * @since 1.0.0
     */
    public function __construct(HttpUri $uri, \FTP\Connection $con = null)
    {
        $this->uri = $uri;
        $this->con = $con ?? self::ftpConnect($this->uri);

        parent::__construct($uri->getPath());

        if ($this->con !== null && self::exists($this->con, $this->path)) {
            $this->index();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function index() : void
    {
        parent::index();

        if ($this->con === null) {
            return;
        }

        $this->size = (int) \ftp_size($this->con, $this->path);
    }

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
     * {@inheritdoc}
     */
    public static function exists(\FTP\Connection $con, string $path) : bool
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
    public static function put(\FTP\Connection $con, string $path, string $content, int $mode = ContentPutMode::REPLACE | ContentPutMode::CREATE) : bool
    {
        $exists = self::exists($con, $path);

        if ((ContentPutMode::hasFlag($mode, ContentPutMode::APPEND) && $exists)
            || (ContentPutMode::hasFlag($mode, ContentPutMode::PREPEND) && $exists)
            || (ContentPutMode::hasFlag($mode, ContentPutMode::REPLACE) && $exists)
            || (!$exists && ContentPutMode::hasFlag($mode, ContentPutMode::CREATE))
        ) {
            if (ContentPutMode::hasFlag($mode, ContentPutMode::APPEND) && $exists) {
                $content .= self::get($con, $path);
            } elseif (ContentPutMode::hasFlag($mode, ContentPutMode::PREPEND) && $exists) {
                $content = $content . self::get($con, $path);
            } elseif (!Directory::exists($con, \dirname($path))) {
                Directory::create($con, \dirname($path), 0755, true);
            }

            $fp = \fopen('php://memory', 'r+');
            if ($fp === false) {
                return false; // @codeCoverageIgnore
            }

            $status = \fwrite($fp, $content);
            if ($status === false) {
                \fclose($fp);

                return false;
            }

            \rewind($fp);

            $status = @\ftp_fput($con, $path, $fp);
            if ($status === false) {
                \fclose($fp);

                return false;
            }

            \fclose($fp);

            \ftp_chmod($con, 0755, $path);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function get(\FTP\Connection $con, string $path) : string
    {
        if (!self::exists($con, $path)) {
            return '';
        }

        $fp = \fopen('php://temp', 'r+');
        if ($fp === false) {
            return '';
        }

        $content = '';
        if (@\ftp_fget($con, $fp, $path, \FTP_BINARY, 0)) {
            \rewind($fp);
            $content = \stream_get_contents($fp);
        }

        return $content === false ? '' : $content;
    }

    /**
     * {@inheritdoc}
     */
    public static function count(\FTP\Connection $con, string $path, bool $recursive = true, array $ignore = []) : int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public static function set(\FTP\Connection $con, string $path, string $content) : bool
    {
        return self::put($con, $path, $content, ContentPutMode::REPLACE | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function append(\FTP\Connection $con, string $path, string $content) : bool
    {
        return self::put($con, $path, $content, ContentPutMode::APPEND | ContentPutMode::CREATE);
    }

    /**
     * {@inheritdoc}
     */
    public static function prepend(\FTP\Connection $con, string $path, string $content) : bool
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
    public static function created(\FTP\Connection $con, string $path) : \DateTime
    {
        return self::changed($con, $path);
    }

    /**
     * {@inheritdoc}
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
     */
    public static function size(\FTP\Connection $con, string $path, bool $recursive = true) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        return \ftp_size($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(\FTP\Connection $con, string $path) : string
    {
        if (!self::exists($con, $path)) {
            throw new PathException($path);
        }

        return Directory::parseRawList($con, self::dirpath($path))[$path]['user'];
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() : string
    {
        if ($this->con === null) {
            return '';
        }

        $this->owner = Directory::parseRawList($this->con, self::dirpath($this->path))[$this->path]['user'];

        return $this->owner;
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(\FTP\Connection $con, string $path) : int
    {
        if (!self::exists($con, $path)) {
            return -1;
        }

        return Directory::parseRawList($con, self::dirpath($path))[$path]['permission'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPermission() : int
    {
        if ($this->con === null) {
            return 0;
        }

        $this->permission = Directory::parseRawList($this->con, self::dirpath($this->path))[$this->path]['permission'];

        return $this->permission;
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
    public static function copy(\FTP\Connection $con, string $from, string $to, bool $overwrite = false) : bool
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
    public static function move(\FTP\Connection $con, string $from, string $to, bool $overwrite = false) : bool
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
    public static function delete(\FTP\Connection $con, string $path) : bool
    {
        if (!self::exists($con, $path)) {
            return false;
        }

        return \ftp_delete($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(\FTP\Connection $con, string $path) : bool
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
        if ($this->con === null) {
            return false;
        }

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

        return new Directory($uri, true, $this->con);
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
        if ($this->con === null) {
            return false;
        }

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
        if ($this->con === null) {
            return false;
        }

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
        if ($this->con === null) {
            return false;
        }

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
        if ($this->con === null) {
            return false;
        }

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
        if ($this->con === null) {
            return false;
        }

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
        if ($this->con === null) {
            return '';
        }

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

        return new Directory($uri, true, $this->con);
    }
}
