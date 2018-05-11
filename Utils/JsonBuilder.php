<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Json builder class.
 *
 * @package    phpOMS\Utils
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class JsonBuilder implements \Serializable, \JsonSerializable
{

    /**
     * Json data.
     *
     * @var array
     * @since 1.0.0
     */
    private $json = [];

    /**
     * Get json data.
     *
     * @return array
     *
     * @since  1.0.0
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
     */
    public function add(string $path, $value, bool $overwrite = true) : void
    {
        $this->json = ArrayUtils::setArray($path, $this->json, $value, '/', $overwrite);
    }

    /**
     * Remove data.
     *
     * @param string $path Path to the element to delete
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function remove(string $path) : void
    {
        $this->json = ArrayUtils::unsetArray($path, $this->json, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return json_encode($this->json);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $this->json = json_decode($serialized, true);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getJson();
    }
}
