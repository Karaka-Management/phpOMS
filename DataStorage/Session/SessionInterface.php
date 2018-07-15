<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

/**
 * Session interface.
 *
 * Sessions can be used by http requests, console interaction and socket connections
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface SessionInterface
{

    /**
     * Get session variable by key.
     *
     * @param string|int $key Value key
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function get($key);

    /**
     * Store session value by key.
     *
     * @param string|int $key       Value key
     * @param mixed      $value     Value to store
     * @param bool       $overwrite Overwrite existing values
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function set($key, $value, bool $overwrite = true) : bool;

    /**
     * Remove value from session by key.
     *
     * @param string|int $key Value key
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function remove($key) : bool;

    /**
     * Save session.
     *
     * @todo   : implement save type (session, cache, database)
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function save() : void;

    /**
     * @return int|string|null
     *
     * @since  1.0.0
     */
    public function getSID();

    /**
     * @param int|string|null $sid Session id
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setSID($sid) : void;

    /**
     * Lock session from further adjustments.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function lock() : void;
}
