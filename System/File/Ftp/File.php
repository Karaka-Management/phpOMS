<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
namespace phpOMS\System\File\Ftp;

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
     * {@inheritdoc}
     */
    public static function put(string $path, string $content, int $mode = ContentPutMode::REPLACE | ContentPutMode::CREATE) : bool
    {
        // todo: create all else cases, right now all getting handled the same way which is wrong
        $current = ftp_pwd($con);
        if(!ftp_chdir($con, File::dirpath($path))) {
            return false;
        }

        $exists = self::exists($path);
        $result = false;

        if (
            (($mode & ContentPutMode::APPEND) === ContentPutMode::APPEND && $exists)
            || (($mode & ContentPutMode::PREPEND) === ContentPutMode::PREPEND && $exists)
            || (($mode & ContentPutMode::REPLACE) === ContentPutMode::REPLACE && $exists)
            || (!$exists && ($mode & ContentPutMode::CREATE) === ContentPutMode::CREATE)
        ) {
            if (!Directory::exists(dirname($path))) {
                Directory::create(dirname($path), '0644', true);
            }

            $stream = fopen('data://temp,' . $content, 'r');
            ftp_fput($path, $content);
            fclose($stream);

            $result = true;
        }

        ftp_chdir($current);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function get(string $path) : /* ? */string
    {
        $temp = fopen('php://temp', 'r+');

        $current = ftp_pwd($con);
        if(ftp_chdir($con, File::dirpath($path)) && ftp_fget($con, $temp, $path, FTP_BINARY, 0)) {
            rewind($temp);
            $content = stream_get_contents($temp);
        }

        fclose($temp);
        ftp_chdir($current);

        return $content ?? null;
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
        
        if(($current = ftp_pwd($con)) !== LocalFile::dirpath($path)) {
            if(!ftp_chdir($con, $path)) {
                return false;
            }
        }

        $list = ftp_nlist($con, $path);

        ftp_chdir($con, $current);

        return in_array($path, $list);
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
        return Directory::parent($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '') : string
    {
        return preg_replace('/[^\w\s\d\.\-_~,;\/\[\]\(\]]/', $replace, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function created(string $path) : \DateTime
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function changed(string $path) : \DateTime
    {
        return ftp_mdtm($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function size(string $path, bool $recursive = true) : int
    {
        if (!self::exists($path)) {
            throw new PathException($path);
        }

        return ftp_size($con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : string
    {

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

    }

    /**
     * Gets the directory path of a file.
     * 
     * @param  string $path Path of the file to get the directory name for.
     * 
     * @return string Returns the directory name of the file.
     *
     * @since 1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function dirpath(string $path) : string
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
         if (!self::exists($from)) {
            throw new PathException($from);
        }

        if ($overwrite || !self::exists($to)) {
            if (!Directory::exists(dirname($to))) {
                Directory::create(dirname($to), '0644', true);
            }


            return ftp_rename($con, $from, $to);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        if (!self::exists($path)) {
            return false;
        }

        ftp_delete($con, $path);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path) : bool
    {

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
    public static function extension(string $path) : string
    {
        $extension = explode('.', basename($path));

        return $extension[1] ?? '';
    }
}