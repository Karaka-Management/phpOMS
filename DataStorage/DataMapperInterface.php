<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * Datamapper interface.
 *
 * This interface is used for DB, Cache & Session implementations
 *
 * @package phpOMS\DataStorage
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface DataMapperInterface
{
    /**
     * Create data.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function create(mixed $obj);

    /**
     * Update data.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function update(mixed $obj);

    /**
     * Delete data.
     *
     * @param mixed $obj Object to delete
     *
     * @return int Status
     *
     * @since 1.0.0
     */
    public static function delete(mixed $obj);

    /**
     * Find data.
     *
     * @param string $search Search
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function find(string $search) : array;

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function get(mixed $primaryKey);
}
