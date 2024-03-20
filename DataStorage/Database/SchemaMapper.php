<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Schema\Builder;
use phpOMS\DataStorage\Database\Schema\Field;
use phpOMS\DataStorage\Database\Schema\Table;

/**
 * Database schema mapper.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @feature Implement schema modification grammar (alter tables)
 *      https://github.com/Karaka-Management/phpOMS/issues/275
 */
class SchemaMapper
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $db;

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
        $tables  = [];
        $builder = new Builder($this->db);

        /** @var array<int, string[]> $tNames */
        $tNames = $builder->selectTables()->execute()?->fetchAll(\PDO::FETCH_ASSOC);

        if ($tNames === null) {
            return $tables;
        }

        foreach ($tNames as $name) {
            $tables[] = \array_values($name)[0];
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
        return new Table();
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
        $fields  = $builder->selectFields($table)->execute()?->fetchAll(\PDO::FETCH_ASSOC);

        return $fields === null ? [] : $fields;
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
        return new Field();
    }
}
