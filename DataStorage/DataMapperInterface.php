<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage;

/**
 * Datamapper interface.
 *
 * This interface is used for DB, Cache & Session implementations
 *
 * @package phpOMS\DataStorage
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
    public static function create($obj);

    /**
     * Update data.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function update($obj);

    /**
     * Delete data.
     *
     * @param mixed $obj Object to delete
     *
     * @return int Status
     *
     * @since 1.0.0
     */
    public static function delete($obj);

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
     * Load.
     *
     * @param array ...$objects Objects to load
     *
     * @return $this
     *
     * @since 1.0.0
     */
    public static function with(...$objects);

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function get($primaryKey);
}
