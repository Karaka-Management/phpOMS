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
abstract class StorageAbstract implements DirectoryInterface, FileInterface
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
}
