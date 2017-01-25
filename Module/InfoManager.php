<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Module;

use phpOMS\System\File\PathException;
use phpOMS\Utils\ArrayUtils;

/**
 * InfoManager class.
 *
 * Handling the info files for modules
 *
 * @category   Framework
 * @package    phpOMS\Module
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
     * @param string $path Info file path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Load info data from path.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function load() /* : void */
    {
        if (!file_exists($this->path)) {
            throw new PathException($this->path);
        }

        $this->info = json_decode(file_get_contents($this->path), true);
    }

    /**
     * Update info file
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function update() /* : void */
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
    public function set(string $path, $data, string $delim = '/') /* : void */
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

    /**
     * Get info data.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getInternalName() : string
    {
        return $this->info['name']['internal'];
    }

    /**
     * Get info data.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getDependencies() : array
    {
        return $this->info['dependencies'];
    }

    /**
     * Get info data.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getProviding() : array
    {
        return $this->info['providing'];
    }

    /**
     * Get info data.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getDirectory() : string
    {
        return $this->info['directory'];
    }

    /**
     * Get info data.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getVersion() : string
    {
        return $this->info['version'];
    }

    /**
     * Get info data.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getLoad() : array
    {
        return $this->info['load'];
    }
}
