<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\System\File\Local
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System\File\Local;

use phpOMS\System\File\PathException;
use phpOMS\System\File\StorageAbstract;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package    phpOMS\System\File\Local
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class LocalStorage extends StorageAbstract
{
    /**
     * Storage instance.
     *
     * @var LocalStorage
     * @since 1.0.0
     */
    private static ?self $instance = null;

    /**
     * Get instance.
     *
     * @return StorageAbstract
     *
     * @since  1.0.0
     */
    public static function getInstance() : StorageAbstract
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getClassType(string $path) : string
    {
        return \is_dir($path) || (!\is_file($path) && \stripos($path, '.') === false) ? Directory::class : File::class;
    }

    /**
     * {@inheritdoc}
     */
    public static function put(string $path, string $content, int $mode = 0) : bool
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::put($path, $content, $mode);
    }

    /**
     * {@inheritdoc}
     */
    public static function get(string $path) : string
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::get($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function list(string $path, string $filter = '*') : array
    {
        if (\is_file($path)) {
            throw new PathException($path);
        }

        return Directory::list($path, $filter);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path) : bool
    {
        return \stripos($path, '.') === false ? Directory::create($path, 0755, true) : File::create($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function set(string $path, string $content) : bool
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::set($path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public static function append(string $path, string $content) : bool
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::append($path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public static function prepend(string $path, string $content) : bool
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::prepend($path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public static function extension(string $path) : string
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::extension($path);
    }
}
