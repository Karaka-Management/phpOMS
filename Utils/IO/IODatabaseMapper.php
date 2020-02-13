<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\IO
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO;

/**
 * IO database mapper.
 *
 * @package phpOMS\Utils\IO
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface IODatabaseMapper
{
    /**
     * Insert data from excel sheet into database
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function insert() : void;

    /**
     * Select data from database and store in excel sheet
     *
     * @param \phpOMS\DataStorage\Database\Query\Builder[] $queries Queries to execute
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function select(array $queries) : void;

    /**
     * Update data from excel sheet into database
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function update() : void;
}
