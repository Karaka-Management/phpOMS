<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database\Schema
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder as QueryBuilder;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Schema
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/phpOMS#32
 *  Implement schema grammar
 *  Basic create/drop schema grammar created. Next step is to be able to update existing schema and read existing schema.
 */
class Builder extends QueryBuilder
{
    /**
     * Table to create.
     *
     * @var string
     * @since 1.0.0
     */
    public string $createTable = '';

    /**
     * Fields.
     *
     * @var array
     * @since 1.0.0
     */
    public array $createFields = [];

    /**
     * Database to drop.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dropDatabase = '';

    /**
     * Table to drop.
     *
     * @var string
     * @since 1.0.0
     */
    public string $dropTable = '';

    /**
     * Tables.
     *
     * @var array
     * @since 1.0.0
     */
    public array $selectTables = ['*'];

    /**
     * Select fields.
     *
     * @var string
     * @since 1.0.0
     */
    public string $selectFields = '';

    /**
     * @todo: ?????.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $createTableSettings = true;

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     * @param bool               $readOnly   Query is read only
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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

    /**
     * Drop database
     *
     * @param string $database Database to drop
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function dropDatabase(string $database) : self
    {
        $this->type         = QueryType::DROP_DATABASE;
        $this->dropDatabase = $database;

        return $this;
    }

    /**
     * Drop table
     *
     * @param string $table Table to drop
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function dropTable(string $table) : self
    {
        $this->type      = QueryType::DROP_TABLE;
        $this->dropTable = $table;

        return $this;
    }

    /**
     * Select all tables
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function selectTables() : self
    {
        $this->type = QueryType::TABLES;

        return $this;
    }

    /**
     * Select all fields of table
     *
     * @param string $table Table to select fields from
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function selectFields(string $table) : self
    {
        $this->type         = QueryType::FIELDS;
        $this->selectFields = $table;

        return $this;
    }

    /**
     * Create table
     *
     * @param string $name Table to create
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function createTable(string $name) : self
    {
        $this->type        = QueryType::CREATE_TABLE;
        $this->createTable = $name;

        return $this;
    }

    /**
     * Define field for create
     *
     * @param string $name          Field name
     * @param string $type          Field type
     * @param mixed  $default       Default value
     * @param bool   $isNullable    Can be null
     * @param bool   $isPrimary     Is a primary field
     * @param bool   $autoincrement Autoincrements
     * @param string $foreignTable  Foreign table (in case of foreign key)
     * @param string $foreignKey    Foreign key
     *
     * @return self
     *
     * @since 1.0.0
     */
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

    /**
     * Alter a field.
     *
     * @param array $column Column data
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function alter(array $column) : void
    {
    }

    /**
     * Parsing to string.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function toSql() : string
    {
        return $this->grammar->compileQuery($this);
    }
}
