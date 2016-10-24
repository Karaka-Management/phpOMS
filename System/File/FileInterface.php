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
interface FileInterface extends ContainerInterface
{

	/**
     * Save content to file.
     *
     * @param string $path File path to save the content to
     * @param string $content Content to save in file
     * @param int $mode Mode (overwrite, append)
     *
     * @return bool 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function put(string $path, string $content, int $mode = 0) : bool;

    /**
     * Get content from file.
     *
     * @param string $path File path of content
     *
     * @return string Content of file
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function get(string $path) : string;

	/**
     * Save content to file.
     *
     * @param string $content Content to save in file
     * @param int $mode Mode (overwrite, append)
     *
     * @return bool 
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function putContent(string $content, int $mode = 0) : bool;

    /**
     * Get content from file.
     *
     * @return string Content of file
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getContent() : string;
}
