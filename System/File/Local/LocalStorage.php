<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System\File\Local
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @package phpOMS\System\File\Local
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class LocalStorage extends StorageAbstract
{
    /**
     * {@inheritdoc}
     */
    protected static function getClassType(string $path) : string
    {
        return \is_dir($path) || (!\is_file($path) && \stripos($path, '.') === false) ? Directory::class : File::class;
    }

    /**
     * {@inheritdoc}
     *
     * @throws PathException
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
     *
     * @throws PathException
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
     *
     * @throws PathException
     */
    public static function list(string $path, string $filter = '*', bool $recursive = false) : array
    {
        if (\is_file($path)) {
            throw new PathException($path);
        }

        return Directory::list($path, $filter, $recursive);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path) : bool
    {
        return \stripos($path, '.') === false
            ? Directory::create($path, 0755, true)
            : File::create($path);
    }

    /**
     * {@inheritdoc}
     *
     * @throws PathException
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
     *
     * @throws PathException
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
     *
     * @throws PathException
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
     *
     * @throws PathException
     */
    public static function extension(string $path) : string
    {
        if (\is_dir($path)) {
            throw new PathException($path);
        }

        return File::extension($path);
    }
}
