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
class FileAbstract
{
    private $path = '';
    private $name = 'new_directory';
    private $count = 0;
    private $size = 0;

    public function __construct(string $path) 
    {
        $this->path = $path;
        $this->name = basename($path);
    }

    public function getCount() : int
    {
        return $this->count;
    }

    public function getSize() : int
    {
        return $this->size;
    }

    public function getName() : string 
    {
        return $this->name;
    }

    public function getPath() : string 
    {
        return $this->path;
    }

    abstract private function index();
}