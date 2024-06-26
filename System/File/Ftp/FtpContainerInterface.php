<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\File\Ftp;

use phpOMS\System\File\ContainerInterface;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface FtpContainerInterface extends ContainerInterface
{
    /**
     * Get the datetime when the resource got created.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function created(\FTP\Connection $con, string $path) : \DateTime;

    /**
     * Get the datetime when the resource got last modified.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function changed(\FTP\Connection $con, string $path) : \DateTime;

    /**
     * Get the owner id of the resource.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function owner(\FTP\Connection $con, string $path) : string;

    /**
     * Get the permissions id of the resource.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return int Permissions (e.g. 0755);
     *
     * @since 1.0.0
     */
    public static function permission(\FTP\Connection $con, string $path) : int;

    /**
     * Get the parent path of the resource.
     *
     * The parent resource path is always a directory.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function parent(string $path) : string;

    /**
     * Delete resource at destination path.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function delete(\FTP\Connection $con, string $path) : bool;

    /**
     * Copy resource to different location.
     *
     * @param \FTP\Connection $con       FTP connection
     * @param string          $from      Path of the resource to copy
     * @param string          $to        Path of the resource to copy to
     * @param bool            $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function copy(\FTP\Connection $con, string $from, string $to, bool $overwrite = false) : bool;

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
    public static function move(\FTP\Connection $con, string $from, string $to, bool $overwrite = false) : bool;

    /**
     * Get size of resource.
     *
     * @param \FTP\Connection $con       FTP connection
     * @param string          $path      Path of the resource
     * @param bool            $recursive Should include sub-sub-resources
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function size(\FTP\Connection $con, string $path, bool $recursive = true) : int;

    /**
     * Check existence of resource.
     *
     * @param \FTP\Connection $con  FTP connection
     * @param string          $path Path of the resource
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function exists(\FTP\Connection $con, string $path) : bool;

    /**
     * Get name of resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function name(string $path) : string;

    /**
     * Get basename of resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function basename(string $path) : string;

    /**
     * Get the directory name of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function dirname(string $path) : string;

    /**
     * Get the directory path of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function dirpath(string $path) : string;

    /**
     * Count subresources.
     *
     * @param \FTP\Connection $con       FTP connection
     * @param string          $path      Path of the resource
     * @param bool            $recursive Consider subdirectories
     * @param string[]        $ignore    Files/paths to ignore (no regex)
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function count(\FTP\Connection $con, string $path, bool $recursive = true, array $ignore = []) : int;

    /**
     * Make name/path operating system safe.
     *
     * @param string $path    Path of the resource
     * @param string $replace Replace invalid chars with
     * @param string $invalid Invalid chars to sanitize
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function sanitize(string $path, string $replace = '', string $invalid = '/[^\w\s\d\.\-_~,;\/\[\]\(\]]/') : string;
}
