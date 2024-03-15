<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage;

/**
 * Database connection interface.
 *
 * @package phpOMS\DataStorage
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface DataStorageConnectionInterface
{
    /**
     * Connect to datastorage.
     *
     * Overwrites current connection if existing
     *
     * @param null|array $data the basic datastorage information for establishing a connection
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function connect(?array $data = null) : void;

    /**
     * Get the datastorage type.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getType() : string;

    /**
     * Get the datastorage status.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatus() : int;

    /**
     * Close datastorage connection.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function close() : void;
}
