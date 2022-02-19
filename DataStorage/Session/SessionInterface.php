<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Session
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

/**
 * Session interface.
 *
 * Sessions can be used by http requests, console interaction and socket connections
 *
 * @package phpOMS\DataStorage\Session
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface SessionInterface
{
    /**
     * Get session variable by key.
     *
     * @param string $key Value key
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function get(string $key) : mixed;

    /**
     * Store session value by key.
     *
     * @param string $key       Value key
     * @param mixed  $value     Value to store
     * @param bool   $overwrite Overwrite existing values
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function set(string $key, mixed $value, bool $overwrite = false) : bool;

    /**
     * Remove value from session by key.
     *
     * @param string $key Value key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(string $key) : bool;

    /**
     * Save session.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function save() : bool;

    /**
     * @return string
     *
     * @since 1.0.0
     */
    public function getSID() : string;

    /**
     * @param string $sid Session id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setSID(string $sid) : void;

    /**
     * Lock session from further adjustments.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function lock() : void;
}
