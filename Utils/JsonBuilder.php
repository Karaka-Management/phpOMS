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
class JsonBuilder implements \Serializable
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
     * @param mixed  $value     Data to add
     * @param bool   $overwrite Should overwrite existing data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function add(string $path, $value, bool $overwrite = true)
    {
        $this->json = ArrayUtils::setArray($path, $this->json, $value, '/', $overwrite);
    }

    /**
     * Remove data.
     *
     * @param string $path  Path to the element to delete
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function remove(string $path)
    {
        $this->json = ArrayUtils::unsetArray($path, $this->json, '/');
    }

    /**
     * String representation of object
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return json_encode($this->json);
    }

    /**
     * Constructs the object
     * @link  http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->json = json_decode($serialized);
    }
}
