<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
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
namespace phpOMS\System\File;
use phpOMS\System\File\Local\FileAbstract;

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
interface ContainerInterface
{
    /**
     * Get the datetime when the resource got created.
     *
     * @param string $path Path of the resource
     *
     * @return \DateTime 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function created(string $path) : \DateTime;

    /**
     * Get the datetime when the resource got last modified.
     *
     * @param string $path Path of the resource
     *
     * @return \DateTime 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function changed(string $path) : \DateTime;

    /**
     * Get the owner id of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return int 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function owner(string $path) : int;

    /**
     * Get the permissions id of the resource.
     *
     * @param string $path Path of the resource
     *
     * @return int Permissions (e.g. 0644);
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function permission(string $path) : int;

    /**
     * Get the parent path of the resource.
     *
     * The parent resource path is always a directory.
     *
     * @param string $path Path of the resource
     *
     * @return string 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function parent(string $path) : string;

    /**
     * Create resource at destination path.
     *
     * @param string $path Path of the resource
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function create(string $path) : bool;

    /**
     * Delete resource at destination path.
     *
     * @param string $path Path of the resource
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function delete(string $path) : bool;

    /**
     * Copy resource to different location.
     *
     * @param string $from Path of the resource to copy
     * @param string $to Path of the resource to copy to
     * @param bool $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function copy(string $from, string $to, bool $overwrite = false) : bool;

    /**
     * Move resource to different location.
     *
     * @param string $from Path of the resource to move
     * @param string $to Path of the resource to move to
     * @param bool $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function move(string $from, string $to, bool $overwrite = false) : bool;

    /**
     * Get size of resource.
     *
     * @param string $path Path of the resource
     * @param bool $recursive Should include sub-sub-resources
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function size(string $path, bool $recursive = true) : int;

    /**
     * Check existence of resource.
     *
     * @param string $path Path of the resource
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function exists(string $path) : bool;

    /**
     * Get name of resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function name(string $path) : string;

    /**
     * Get basename of resource.
     *
     * @param string $path Path of the resource
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function basename(string $path) : string;

    /**
     * Get amount of sub-resources.
     *
     * A file will always return 1 as it doesn't have any sub-resources. 
     *
     * @param string $path Path of the resource
     * @param bool $recursive Should count also sub-sub-resources
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function count(string $path, bool $recursive = false) : int;

    /**
     * Get amount of sub-resources.
     *
     * A file will always return 1 as it doesn't have any sub-resources. 
     *
     * @param bool $recursive Should count also sub-sub-resources
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCount(bool $recursive = false) : int;

    /**
     * Get size of resource.
     *
     * @param bool $recursive Should include sub-sub-resources
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getSize(bool $recursive = false) : int;

    /**
     * Get name of the resource.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : string;

    /**
     * Get absolute path of the resource.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPath() : string;

    /**
     * Get the parent path of the resource.
     *
     * The parent resource path is always a directory.
     *
     * @return ContainerInterface 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getParent() : ContainerInterface;

    /**
     * Create resource at destination path.
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function createNode() : bool;

    /**
     * Copy resource to different location.
     *
     * @param string $to Path of the resource to copy to
     * @param bool $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function copyNode(string $to, bool $overwrite = false) : bool;

    /**
     * Move resource to different location.
     *
     * @param string $from Path of the resource to move
     * @param string $to Path of the resource to move to
     * @param bool $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function moveNode(string $to, bool $overwrite = false) : bool;

    /**
     * Delete resource at destination path.
     *
     * @return bool True on success and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function deleteNode() : bool;

    /**
     * Get the datetime when the resource got created.
     *
     * @return \DateTime 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCreatedAt() : \DateTime;

    /**
     * Get the datetime when the resource got last modified.
     *
     * @return \DateTime 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getChangedAt() : \DateTime;

    /**
     * Get the owner id of the resource.
     *
     * @return int 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getOwner() : int;

    /**
     * Get the permissions id of the resource.
     *
     * @return int Permissions (e.g. 0644);
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPermission() : string;

    /**
     * (Re-)Initialize resource
     *
     * This is used in order to initialize all resources.
     * Sub-sub-resources are only initialized once they are needed.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function index();
}
