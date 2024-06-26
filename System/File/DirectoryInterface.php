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

namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\System\File
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @phpstan-extends \ArrayAccess<string, mixed>
 * @phpstan-extends \Iterator<string, mixed>
 */
interface DirectoryInterface extends \ArrayAccess, \Iterator, ContainerInterface
{
    /**
     * Get node by name.
     *
     * @param string $name File/directory name
     *
     * @return null|ContainerInterface
     *
     * @since 1.0.0
     */
    public function getNode(string $name) : ?ContainerInterface;

    /**
     * Add file or directory.
     *
     * @param ContainerInterface $file File to add
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function addNode(ContainerInterface $file) : self;

    /**
     * Get files in directory.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getList() : array;
}
