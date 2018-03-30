<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage;

/**
 * Datamapper interface.
 *
 * DB, Cache, Session
 *
 * @package    phpOMS\DataStorage
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface DataStoragePoolInterface
{

    /**
     * Add connection.
     *
     * @param string                         $key Connection key
     * @param DataStorageConnectionInterface $db  Connection
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function add(string $key, DataStorageConnectionInterface $db) : bool;

    /**
     * Get connection.
     *
     * @param string $key Connection key
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function get(string $key = '') : DataStorageConnectionInterface;

    /**
     * Remove connection.
     *
     * @param string $key Connection key
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function remove(string $key) : bool;

    /**
     * Create connection.
     *
     * @param string $key    Connection key
     * @param array  $config Connection config data
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function create(string $key, array $config) : bool;
}
