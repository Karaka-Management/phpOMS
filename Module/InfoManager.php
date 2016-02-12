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
namespace phpOMS\Module;

use phpOMS\System\FilePathException;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Validation\Validator;

/**
 * InfoManager class.
 *
 * Handling the info files for modules
 *
 * @category   Module
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class InfoManager
{

    /**
     * File path.
     *
     * @var string
     * @since 1.0.0
     */
    private $path = '';

    /**
     * Info data.
     *
     * @var array
     * @since 1.0.0
     */
    private $info = [];

    /**
     * Object constructor.
     *
     * @param string $module Module name
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct(string $module)
    {
        if (($path = realpath($oldPath = ModuleAbstract::MODULE_PATH . '/' . $module . '/info.json')) === false || Validator::startsWith($path, ModuleAbstract::MODULE_PATH)) {
            throw new FilePathException($oldPath);
        }

        $this->path = $path;
        $this->info = json_decode(file_get_contents($this->path), true);
    }

    /**
     * Update info file
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function update()
    {
        file_put_contents($this->path, json_encode($this->info, JSON_PRETTY_PRINT));
    }

    /**
     * Set data
     *
     * @param string $path  Value path
     * @param mixed  $data  Scalar or array of data to set
     * @param string $delim Delimiter of path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function set(string $path, $data, string $delim = '/')
    {
        if (!is_scalar($data) || !is_array($data)) {
            throw new \InvalidArgumentException('Type of $data "' . gettype($data) . '" is not supported.');
        }

        ArrayUtils::setArray($path, $this->info, $data, $delim);
    }

    /**
     * Get info data.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function get() : array
    {
        return $this->info;
    }
}
