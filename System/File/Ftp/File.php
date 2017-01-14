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
    }

    /**
     * {@inheritdoc}
     */
    public static function get(string $path) : string
    {
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
        if(ftp_pwd($con) !== LocalFile::dirpath($path)) {
            if(!ftp_chdir($con, $path)) {
                return false;
            }
        }

        $list = ftp_nlist($con, $path);

        return in_array($path, $list);
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
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

    }

    /**
     * {@inheritdoc}
     */
    public static function size(string $path, bool $recursive = true) : int
    {

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

    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {

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