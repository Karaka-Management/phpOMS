<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Schema\Builder;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class SchemaMapper
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected $db = null;

    public function __construct(ConnectionAbstract $db)
    {
        $this->db = $db;
    }

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

    public function getTable(string $name) : Table
    {
        $table = new Table();

        return $table;
    }

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

    public function getField(string $table, string $name) : Field
    {
        $field = new Field();

        return $field;
    }
}
