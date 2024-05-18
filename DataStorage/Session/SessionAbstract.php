<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Session
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

/**
 * Session interface.
 *
 * Sessions can be used by http requests, console interaction and socket connections
 *
 * @package phpOMS\DataStorage\Session
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class SessionAbstract
{
    /**
     * Raw session data.
     *
     * @var array<string, mixed>
     * @since 1.0.0
     */
    public array $data = [];

    /**
     * Get session variable by key.
     *
     * @param string $key Value key
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    abstract public function get(string $key) : mixed;

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
    abstract public function set(string $key, mixed $value, bool $overwrite = false) : bool;

    /**
     * Remove value from session by key.
     *
     * @param string $key Value key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    abstract public function remove(string $key) : bool;

    /**
     * Save session.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    abstract public function save() : bool;

    /**
     * @return string
     *
     * @since 1.0.0
     */
    abstract public function getSID() : string;

    /**
     * @param string $sid Session id
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function setSID(string $sid) : void;

    /**
     * Lock session from further adjustments.
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function lock() : void;
}
