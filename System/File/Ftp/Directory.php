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
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\System\File\Ftp;

use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\DirectoryInterface;
use phpOMS\System\File\PathException;
use phpOMS\Utils\StringUtils;
use phpOMS\System\File\Local\Directory as DirectoryLocal;

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
class Directory extends FileAbstract implements DirectoryInterface
{
    /**
     * {@inheritdoc}
     */
    public static function size(string $dir, bool $recursive = true) : int
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function count(string $path, bool $recursive = true, array $ignore = ['.', '..', 'cgi-bin',
                                                                                               '.DS_Store']) : int
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
    public static function parent(string $path) : string
    {

    }

    /**
     * {@inheritdoc}
     * todo: move to fileAbastract since it should be the same for file and directory?
     */
    public static function created(string $path) : \DateTime
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function changed(string $path) : \DateTime
    {
        // TODO: Implement changed() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function owner(string $path) : int
    {
        // TODO: Implement owner() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function permission(string $path) : string
    {
        // TODO: Implement permission() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement copy() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        // TODO: Implement move() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function exists(string $path) : bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function sanitize(string $path, string $replace = '') : string
    {
        return DirectoryLocal::sanitize($path, $replace);
    }

    /**
     * {@inheritdoc}
     */
    public static function create(string $path, string $permission = '0644', bool $recursive = false) : bool
    {

    }

    /**
     * {@inheritdoc}
     */
    public static function name(string $path) : string
    {
        return DirectoryLocal::name($path);
    }

    /**
     * {@inheritdoc}
     */
    public static function basename(string $path) : string
    {
        return DirectoryLocal::basename($path);
    }
}