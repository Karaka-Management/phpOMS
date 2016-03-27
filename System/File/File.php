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

    public static function create(string $path) : bool
    {
        if (!file_exists($path)) {
            if(is_writable(Directory::getParent($path))) {
                touch($path);

                return true;
            } else {
                throw new PathException($path);
            }
        }

        return false;
    }

    public function __construct(string $path) 
    {
        parent::__constrct($path);
        $this->count = 1;
        
        if(file_exists($this->path)) {
            $this->index();
        }
    }

    public function index() 
    {
        parent::index();
        
        $this->size = filesize($this->path);
    }
}