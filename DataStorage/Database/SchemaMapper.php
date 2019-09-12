<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Schema\Builder;

/**
 * Database schema mapper.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class SchemaMapper
{
    /**
     * Database connection.
     *
     * @var   null|ConnectionAbstract
     * @since 1.0.0
     */
    protected ?ConnectionAbstract $db = null;

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $db)
    {
        $this->db = $db;
    }

    /**
     * Get all tables of database
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getTables() : array
    {
        $builder = new Builder($this->db);
        $tNames  = $builder->selectTables()->execute();

        $tables = [];
        foreach ($tNames as $name) {
            $tables[] = $this->getTable($name);
        }

        return $tables;
    }

    /**
     * Get table by name
     *
     * @param string $name Name of the table
     *
     * @return Table
     *
     * @since 1.0.0
     */
    public function getTable(string $name) : Table
    {
        $table = new Table();

        return $table;
    }

    /**
     * Get fields of table
     *
     * @param string $table Name of the table
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getFields(string $table) : array
    {
        $builder = new Builder($this->db);
        $tNames  = $builder->selectFields()->execute();

        $fields = [];
        foreach ($tNames as $name) {
            $fields[] = $this->getField($name);
        }

        return $fields;
    }

    /**
     * Get field of table
     *
     * @param string $table Name of the table
     * @param string $name  Name of the field
     *
     * @return Field
     *
     * @since 1.0.0
     */
    public function getField(string $table, string $name) : Field
    {
        $field = new Field();

        return $field;
    }
}
