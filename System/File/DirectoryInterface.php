<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\System\File
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package    phpOMS\System\File
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface DirectoryInterface extends ContainerInterface, \Iterator, \ArrayAccess
{
    /**
     * Get node by name.
     *
     * @param string $name File/direcotry name
     *
     * @return null|ContainerInterface
     *
     * @since  1.0.0
     */
    public function getNode(string $name) : ?ContainerInterface;

    /**
     * Add file or directory.
     *
     * @param mixed $file File to add
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function addNode($file) : bool;
}
