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
declare(strict_types=1);

namespace phpOMS\Asset;

/**
 * Asset manager class.
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class AssetManager implements \Countable
{

    /**
     * Assets.
     *
     * @var array
     * @since 1.0.0
     */
    private $assets = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Add asset.
     *
     * @param string $id        Asset id
     * @param string $asset     Asset
     * @param bool   $overwrite Overwrite
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(string $id, string $asset, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset($this->assets[$id])) {
            $this->assets[$id] = $asset;

            return true;
        }

        return false;
    }

    /**
     * Remove asset.
     *
     * @param string $id Asset id
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(string $id) : bool
    {
        if (isset($this->assets[$id])) {
            unset($this->assets[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get asset.
     *
     * @param string $id Asset id
     *
     * @return mixed Asset
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get(string $id) /* : ?string */
    {
        if (isset($this->assets[$id])) {
            return $this->assets[$id];
        }

        return null;
    }

    /**
     * Get asset count.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function count() : int
    {
        return count($this->assets);
    }

}
