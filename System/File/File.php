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
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class File extends FileAbstract
{

    /**
     * Create file.
     *
     * @param string $path Path
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function createFile(string $path) : bool
    {
        if (!file_exists($path)) {
            if (is_writable(Directory::getParent($path))) {
                touch($path);

                return true;
            } else {
                throw new PermissionException($path);
            }
        }

        return false;
    }

    /**
     * Constructor.
     *
     * @param string $path Path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $path) 
    {
        parent::__construct($path);
        $this->count = 1;
        
        if (file_exists($this->path)) {
            $this->index();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        return self::create($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function removeNode() : bool
    {
        return true;
    }

    /**
     * Get file content.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getContent() : string
    {
        return file_get_contents($this->path);
    }

    /**
     * Set file content.
     *
     * @param string $content Content to set
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setContent(string $content)
    {
        file_put_contents($this->path, $content);
    }

    /**
     * Index file.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function index() 
    {
        parent::index();
        
        $this->size = filesize($this->path);
    }
}