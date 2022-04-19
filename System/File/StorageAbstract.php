<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class StorageAbstract
{
    /**
     * Get the internal class type (directory or file) based on path.
     *
     * @param string $path Path to the directory or file
     *
     * @return string Class namespace
     *
     * @since 1.0.0
     */
    abstract protected static function getClassType(string $path) : string;

    /**
     * Get the datetime when the resource got created.
     *
     * @param string $path Path of the resource
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function created(string $path) : \DateTime
    {
        return static::getClassType($path)::created($path);
    }

    /**
     * Get the datetime when the resource got last modified.
     *
     * @param string $path Path of the resource
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public static function changed(string $path) : \DateTime
    {
        return static::getClassType($path)::changed($path);
    }

    /**
     * Get the owner id of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function owner(string $path) : int
    {
        return static::getClassType($path)::owner($path);
    }

    /**
     * Get the permissions id of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return int Permissions (e.g. 0755);
     *
     * @since 1.0.0
     */
    public static function permission(string $path) : int
    {
        return static::getClassType($path)::permission($path);
    }

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
    public static function parent(string $path) : string
    {
        return static::getClassType($path)::parent($path);
    }

    /**
     * Delete resource at destination path.
     *
     * @param string $path Path of the resource
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function delete(string $path) : bool
    {
        return static::getClassType($path)::delete($path);
    }

    /**
     * Copy resource to different location.
     *
     * @param string $from      Path of the resource to copy
     * @param string $to        Path of the resource to copy to
     * @param bool   $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        return static::getClassType($from)::copy($from, $to, $overwrite);
    }

    /**
     * Move resource to different location.
     *
     * @param string $from      Path of the resource to move
     * @param string $to        Path of the resource to move to
     * @param bool   $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        return static::getClassType($from)::move($from, $to, $overwrite);
    }

    /**
     * Get size of resource.
     *
     * @param string $path      Path of the resource
     * @param bool   $recursive Should include sub-sub-resources
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function size(string $path, bool $recursive = true) : int
    {
        return static::getClassType($path)::size($path, $recursive);
    }

    /**
     * Check existence of resource.
     *
     * @param string $path Path of the resource
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function exists(string $path) : bool
    {
        return static::getClassType($path)::exists($path);
    }

    /**
     * Get name of resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function name(string $path) : string
    {
        return static::getClassType($path)::name($path);
    }

    /**
     * Get basename of resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function basename(string $path) : string
    {
        return static::getClassType($path)::basename($path);
    }

    /**
     * Get the directoryname of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function dirname(string $path) : string
    {
        return static::getClassType($path)::dirname($path);
    }

    /**
     * Get the directory path of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function dirpath(string $path) : string
    {
        return static::getClassType($path)::dirpath($path);
    }

    /**
     * Count subresources.
     *
     * @param string   $path      Path of the resource
     * @param bool     $recursive Consider subdirectories
     * @param string[] $ignore    Files/paths to ignore (no regex)
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function count(string $path, bool $recursive = true, array $ignore = []) : int
    {
        return static::getClassType($path)::count($path, $recursive, $ignore);
    }

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
    public static function sanitize(string $path, string $replace = '', string $invalid = '/[^\w\s\d\.\-_~,;\/\[\]\(\]]/') : string
    {
        return static::getClassType($path)::sanitize($path, $replace);
    }
}
