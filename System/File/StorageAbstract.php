<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @category   Framework
 * @package    phpOMS\System\File
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class StorageAbstract
{
    /**
     * Storage type.
     *
     * @var int
     * @since 1.0.0
     */
    protected $type = 0;

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    private function __construct()
    {
    }

    /**
     * Get instance.
     *
     * @return mixed Storage instance.
     *
     * @since  1.0.0
     */
    public static function getInstance() : StorageAbstract
    {
        return null;
    }
    /**
     * Get storage type.
     *
     * @return int Storage type.
     *
     * @since  1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public abstract static function created(string $path) : \DateTime;

    /**
     * {@inheritdoc}
     */
    public abstract static function changed(string $path) : \DateTime;

    /**
     * {@inheritdoc}
     */
    public abstract static function owner(string $path) : int;

    /**
     * {@inheritdoc}
     */
    public abstract static function permission(string $path) : int;

    /**
     * {@inheritdoc}
     */
    public abstract static function parent(string $path) : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function create(string $path) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function delete(string $path) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function copy(string $from, string $to, bool $overwrite = false) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function move(string $from, string $to, bool $overwrite = false) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function size(string $path, bool $recursive = true) : int;

    /**
     * {@inheritdoc}
     */
    public abstract static function exists(string $path) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function name(string $path) : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function basename(string $path) : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function dirname(string $path) : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function dirpath(string $path) : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function list(string $path, string $filter = '*') : array;

    /**
     * {@inheritdoc}
     */
    public abstract static function count(string $path, bool $recursive = true, array $ignore = []) : int;

    /**
     * {@inheritdoc}
     */
    public abstract static function put(string $path, string $content, int $mode = 0) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function get(string $path) : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function sanitize(string $path, string $replace = '') : string;

    /**
     * {@inheritdoc}
     */
    public abstract static function set(string $path, string $content) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function append(string $path, string $content) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function prepend(string $path, string $content) : bool;

    /**
     * {@inheritdoc}
     */
    public abstract static function extension(string $path) : string;
}
