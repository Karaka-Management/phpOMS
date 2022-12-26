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
 * @link      https://jingga.app
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
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface ContainerInterface
{
    /**
     * Get amount of sub-resources.
     *
     * A file will always return 1 as it doesn't have any sub-resources.
     *
     * @param bool $recursive Should count also sub-sub-resources
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getCount(bool $recursive = false) : int;

    /**
     * Get size of resource.
     *
     * @param bool $recursive Should include sub-sub-resources
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSize(bool $recursive = false) : int;

    /**
     * Get name of the resource.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string;

    /**
     * Get base name of the resource incl. extension if available.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getBasename() : string;

    /**
     * Get absolute path of the resource.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPath() : string;

    /**
     * Get the parent path of the resource.
     *
     * The parent resource path is always a directory.
     *
     * @return ContainerInterface
     *
     * @since 1.0.0
     */
    public function getParent() : self;

    /**
     * Create resource at destination path.
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function createNode() : bool;

    /**
     * Copy resource to different location.
     *
     * @param string $to        Path of the resource to copy to
     * @param bool   $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function copyNode(string $to, bool $overwrite = false) : bool;

    /**
     * Move resource to different location.
     *
     * @param string $to        Path of the resource to move to
     * @param bool   $overwrite Overwrite/replace existing file
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function moveNode(string $to, bool $overwrite = false) : bool;

    /**
     * Delete resource at destination path.
     *
     * @return bool True on success and false on failure
     *
     * @since 1.0.0
     */
    public function deleteNode() : bool;

    /**
     * Get the datetime when the resource got created.
     *
     * @return \DateTimeImmutable
     *
     * @since 1.0.0
     */
    public function getCreatedAt() : \DateTimeImmutable;

    /**
     * Get the datetime when the resource got last modified.
     *
     * @return \DateTime
     *
     * @since 1.0.0
     */
    public function getChangedAt() : \DateTime;

    /**
     * Get the owner id of the resource.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getOwner() : string;

    /**
     * Get the permissions id of the resource.
     *
     * @return int Permissions (e.g. 0755);
     *
     * @since 1.0.0
     */
    public function getPermission() : int;

    /**
     * (Re-)Initialize resource
     *
     * This is used in order to initialize all resources.
     * Sub-sub-resources are only initialized once they are needed.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function index() : void;
}
