<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Config
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Config;

/**
 * Options class.
 *
 * @package phpOMS\Config
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface OptionsInterface
{
    /**
     * Is this key set.
     *
     * @param int|string $key Key to check for existence
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function exists(int | string $key) : bool;

    /**
     * Updating or adding settings.
     *
     * @param int|string $key       Unique option key
     * @param mixed      $value     Option value
     * @param bool       $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setOption(int | string $key, mixed $value, bool $overwrite = true) : bool;

    /**
     * Updating or adding settings.
     *
     * @param array $pair      Key value pair
     * @param bool  $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function setOptions(array $pair, bool $overwrite = true) : bool;

    /**
     * Get option by key.
     *
     * @param int|string $key Unique option key
     *
     * @return mixed Option value
     *
     * @since 1.0.0
     */
    public function getOption(int | string $key) : mixed;
}
