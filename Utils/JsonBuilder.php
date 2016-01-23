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
namespace phpOMS\Utils;



/**
 * Json builder class.
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class JsonBuilder
{

    /**
     * Json data.
     *
     * @var array
     * @since 1.0.0
     */
    private $json = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct()
    {
    }

    /**
     * Get json data.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getJson() : array
    {
        return $this->json;
    }

    /**
     * Add data.
     *
     * @param string $path      Path used for storage
     * @param array   $value     Data to add
     * @param bool   $overwrite Should overwrite existing data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function add(string $path, array $value, bool $overwrite = true)
    {
        $this->json = ArrayUtils::setArray($path, $this->json, $value, '/', $overwrite);
    }

    /**
     * Remove data.
     *
     * @param string $path  Path to the element to delete
     * @param string $delim Delim used inside path
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function remove(string $path, string $delim)
    {
        $this->json = ArrayUtils::unsetArray($path, $this->json, $delim);
    }

    /**
     * Get json string.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __toString()
    {
        return json_encode($this->json);
    }
}
