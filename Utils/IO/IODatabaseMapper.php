<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO;

/**
 * IO database mapper.
 *
 * @package phpOMS\Utils\IO
 * @license OMS License 2.0
 * @link    https://jingga.app
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
