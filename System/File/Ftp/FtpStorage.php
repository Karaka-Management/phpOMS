<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\System\File\Ftp
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System\File\Ftp;

use phpOMS\System\File\PathException;
use phpOMS\System\File\StorageAbstract;

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
class FtpStorage extends StorageAbstract
{
    /**
     * Connection
     *
     * @var resource
     * @since 1.0.0
     */
    private static $con = null;

    /**
     * Set connection
     *
     * @param resource $con Connection
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function with($con) : void
    {
        self::$con = $con;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getClassType(string $path) : string
    {
        return ftp_size(self::$con, $path) === -1 && stripos($path, '.') === false
            ? Directory::class
            : File::class;
    }

    /**
     * {@inheritdoc}
     */
    public static function put(string $path, string $content, int $mode = 0) : bool
    {
        if (static::getClassType($path) === Directory::class) {
            throw new PathException($path);
        }

        return File::put(self::$con, $path, $content, $mode);
    }

    /**
     * {@inheritdoc}
     */
    public static function get(string $path) : string
    {
        if (static::getClassType($path) === Directory::class) {
            throw new PathException($path);
        }

        return File::get(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path) : bool
    {
        return stripos($path, '.') === false
            ? Directory::create(self::$con, $path, 0755, true)
            : File::create(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function list(string $path, string $filter = '*', bool $recursive = false) : array
    {
        if (static::getClassType($path) === File::class) {
            throw new PathException($path);
        }

        return Directory::list(self::$con, $path, $filter, $recursive);
    }

    /**
     * {@inheritdoc}
     */
    public static function set(string $path, string $content) : bool
    {
        if (static::getClassType($path) === Directory::class) {
            throw new PathException($path);
        }

        return File::set(self::$con, $path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public static function append(string $path, string $content) : bool
    {
        if (static::getClassType($path) === Directory::class) {
            throw new PathException($path);
        }

        return File::append(self::$con, $path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public static function prepend(string $path, string $content) : bool
    {
        if (static::getClassType($path) === Directory::class) {
            throw new PathException($path);
        }

        return File::prepend(self::$con, $path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public static function extension(string $path) : string
    {
        if (static::getClassType($path) === Directory::class) {
            throw new PathException($path);
        }

        return File::extension($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function created(string $path) : \DateTime
    {
        return static::getClassType($path)::created(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function changed(string $path) : \DateTime
    {
        return static::getClassType($path)::changed(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {
        return static::getClassType($path)::owner(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : int
    {
        return static::getClassType($path)::permission(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function parent(string $path) : string
    {
        return static::getClassType($path)::parent($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function delete(string $path) : bool
    {
        return static::getClassType($path)::delete(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        return static::getClassType($from)::copy(self::$con, $from, $to, $overwrite);
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        return static::getClassType($from)::move(self::$con, $from, $to, $overwrite);
    }

    /**
     * {@inheritdoc}
     */
    public static function size(string $path, bool $recursive = true) : int
    {
        return static::getClassType($path)::size(self::$con, $path, $recursive);
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(string $path) : bool
    {
        return static::getClassType($path)::exists(self::$con, $path);
    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return static::getClassType($path)::name($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return static::getClassType($path)::basename($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function dirname(string $path) : string
    {
        return static::getClassType($path)::dirname($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function dirpath(string $path) : string
    {
        return static::getClassType($path)::dirpath($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function count(string $path, bool $recursive = true, array $ignore = []) : int
    {
        return static::getClassType($path)::count(self::$con, $path, $recursive, $ignore);
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '', string $invalid = '/[^\w\s\d\.\-_~,;\/\[\]\(\]]/') : string
    {
        return static::getClassType($path)::sanitize($path, $replace);
    }
}
