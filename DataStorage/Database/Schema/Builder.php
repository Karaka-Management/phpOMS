<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Schema
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder as QueryBuilder;

/**
 * Database query builder.
 *
 * @package    phpOMS\DataStorage\Database\Schema
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Builder extends QueryBuilder
{
    public $createTable = '';

    public $createFields = [];

    public $dropDatabase = '';

    public $dropTable = '';

    public $selectTables = ['*'];

    public $selectFields = '';

    public $createTableSettings = true;

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     * @param bool               $readOnly   Query is read only
     *
     * @since  1.0.0
     */
    public function __construct(ConnectionAbstract $connection, bool $readOnly = false)
    {
        $this->isReadOnly = $readOnly;
        $this->setConnection($connection);
    }

    /**
     * Set connection for grammar.
     *
     * @param ConnectionAbstract $connection Database connection
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setConnection(ConnectionAbstract $connection) : void
    {
        $this->connection = $connection;
        $this->grammar    = $connection->getSchemaGrammar();
    }

    /**
     * Create schema builder from schema definition.
     *
     * @param array              $definition Database schema definition
     * @param ConnectionAbstract $connection Database connection
     *
     * @return self
     *
     * @since  1.0.0
     */
    public static function createFromSchema(array $definition, ConnectionAbstract $connection) : self
    {
        $builder = new self($connection);
        $builder->prefix($connection->prefix);
        $builder->createTable($definition['name'] ?? '');

        foreach ($definition['fields'] as $name => $def) {
            $builder->field(
                $name, $def['type'], $def['default'] ?? null,
                $def['null'] ?? true, $def['primary'] ?? false, $def['autoincrement'] ?? false,
                $def['foreignTable'] ?? null, $def['foreignKey'] ?? null
            );
        }

        return $builder;
    }

    public function dropDatabase(string $database) : self
    {
        $this->type         = QueryType::DROP_DATABASE;
        $this->dropDatabase = $database;

        return $this;
    }

    public function dropTable(string $table) : self
    {
        $this->type      = QueryType::DROP_TABLE;
        $this->dropTable = $table;

        return $this;
    }

    public function selectTables() : self
    {
        $this->type = QueryType::TABLES;

        return $this;
    }

    public function selectFields(string $table) : self
    {
        $this->type         = QueryType::FIELDS;
        $this->selectFields = $table;

        return $this;
    }

    public function createTable(string $name) : self
    {
        $this->type        = QueryType::CREATE_TABLE;
        $this->createTable = $name;

        return $this;
    }

    // todo: consider to work with flags instead of all these booleans
    public function field(
        string $name, string $type, $default = null,
        bool $isNullable = true, bool $isPrimary = false, bool $autoincrement = false,
        string $foreignTable = null, string $foreignKey = null
    ) : self {
        $this->createFields[$name] = [
            'name'          => $name,
            'type'          => $type,
            'default'       => $default,
            'null'          => $isNullable,
            'primary'       => $isPrimary,
            'autoincrement' => $autoincrement,
            'foreignTable'  => $foreignTable,
            'foreignKey'    => $foreignKey,
        ];

        return $this;
    }

    public function alter(array $column) : void
    {
    }

    /**
     * Parsing to string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function toSql() : string
    {
        return $this->grammar->compileQuery($this);
    }
}
