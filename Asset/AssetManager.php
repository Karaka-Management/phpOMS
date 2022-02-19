<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Asset
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Asset;

/**
 * Asset manager class.
 *
 * @package phpOMS\Asset
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class AssetManager implements \Countable
{
    /**
     * Assets.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    private array $assets = [];

    /**
     * Add asset.
     *
     * @param string $id        Asset id
     * @param string $asset     Asset
     * @param bool   $overwrite Overwrite
     *
     * @return bool Returns true if the asset could be set otherwise false
     *
     * @since 1.0.0
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
     * @return bool Returns true if the asset could be removed otherwise false
     *
     * @since 1.0.0
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
     * @return null|string
     *
     * @since 1.0.0
     */
    public function get(string $id) : ?string
    {
        if (isset($this->assets[$id])) {
            return $this->assets[$id];
        }

        return null;
    }

    /**
     * Get asset count.
     *
     * @return int Returns the amount of assets (>= 0)
     *
     * @since 1.0.0
     */
    public function count() : int
    {
        return \count($this->assets);
    }
}
