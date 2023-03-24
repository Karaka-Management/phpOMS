<?php
/**
 * Karaka
 *
 * PHP Version 8.1
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
 */
interface FileInterface extends ContainerInterface
{
    /**
     * Save content to file.
     *
     * @param string $content Content to save in file
     * @param int    $mode    Mode (overwrite, append)
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function putContent(string $content, int $mode = ContentPutMode::APPEND | ContentPutMode::CREATE) : bool;

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setContent(string $content) : bool;

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function appendContent(string $content) : bool;

    /**
     * Save content to file.
     *
     * Creates new file if it doesn't exist or overwrites existing file.
     *
     * @param string $content Content to save in file
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function prependContent(string $content) : bool;

    /**
     * Get content from file.
     *
     * @return string Content of file
     *
     * @since 1.0.0
     */
    public function getContent() : string;

    /**
     * Get file extension.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getExtension() : string;
}
