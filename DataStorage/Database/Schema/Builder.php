<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Schema
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Schema\Grammar\Grammar;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Schema
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @property \phpOMS\DataStorage\Database\Schema\Grammar $grammar Grammar.
 */
class Builder extends BuilderAbstract
{
    /**
     * Grammar.
     *
     * @var Grammar
     * @since 1.0.0
     */
    protected Grammar $grammar;

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
     * @var array<string, array{name:string, type:string, default:mixed, null:bool, primary:bool, autoincrement:bool, foreignTable:?string, foreignKey:?string}>
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
     * @var array
     * @since 1.0.0
     */
    public array $dropTable = [];

    /**
     * Tables.
     *
     * @var string[]
     * @since 1.0.0
     */
    public array $selectTables = ['*'];

    /**
     * Always calls compileCreateTableSettings in the Grammar
     *
     * This is important to set the correct table settings (e.g. utf8mb4 instead of utf8)
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $createTableSettings = true;

    /**
     * Select fields.
     *
     * @var string
     * @since 1.0.0
     */
    public string $selectFields = '';

    /**
     * Table to alter.
     *
     * @var string
     * @since 1.0.0
     */
    public string $alterTable = '';

    /**
     * Column to alter.
     *
     * @var string
     * @since 1.0.0
     */
    public string $alterColumn = '';

    /**
     * Data to add.
     *
     * @var array
     * @since 1.0.0
     */
    public array $alterAdd = [];

    /**
     * Has post query to run.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasPostQuery = false;

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
     * Get the column type from a variable value.
     *
     * @param mixed $value Variable value
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getTypeFromVariable(mixed $value) : string
    {
        if (\is_string($value)) {
            return 'TEXT';
        } elseif (\is_int($value)) {
            return 'INT';
        } elseif (\is_bool($value)) {
            return 'TINYINT(1)';
        } elseif (\is_float($value)) {
            return 'DECIMAL(10,6)';
        } elseif ($value instanceof \DateTimeInterface) {
            return 'DATETIME';
        }

        return 'TEXT';
    }

    /**
     * Create schema builder from schema definition.
     *
     * @param array              $definition Database schema definition
     * @param ConnectionAbstract $connection Database connection
     *
     * @return self
     *
     * @todo Allow to create db indices in json files
     *      e.g. "index": {"idx1": ["col1"], "idx2": ["col1", "col2"] }
     *      https://github.com/Karaka-Management/phpOMS/issues/355
     *
     * @since 1.0.0
     */
    public static function createFromSchema(array $definition, ConnectionAbstract $connection) : self
    {
        $builder                  = new self($connection);
        $builder->usePreparedStmt = false;
        $builder->createTable($definition['name'] ?? '');

        foreach ($definition['fields'] as $name => $def) {
            $builder->field(
                $name, $def['type'], $def['default'] ?? null,
                $def['null'] ?? true, $def['primary'] ?? false, $def['unique'] ?? false, $def['autoincrement'] ?? false,
                $def['foreignTable'] ?? null, $def['foreignKey'] ?? null,
                $def
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
        $this->type        = QueryType::DROP_TABLE;
        $this->dropTable[] = $table;

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
     * @param bool   $isUnique      Is a unique field
     * @param bool   $autoincrement Auto increments
     * @param string $foreignTable  Foreign table (in case of foreign key)
     * @param string $foreignKey    Foreign key
     * @param array  $meta          Meta data
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function field(
        string $name, string $type, $default = null,
        bool $isNullable = true, bool $isPrimary = false, bool $isUnique = false, bool $autoincrement = false,
        ?string $foreignTable = null, ?string $foreignKey = null, array $meta = []
    ) : self {
        $this->createFields[$name] = [
            'name'          => $name,
            'type'          => $type,
            'default'       => $default,
            'null'          => $isNullable,
            'primary'       => $isPrimary,
            'unique'        => $isUnique,
            'autoincrement' => $autoincrement,
            'foreignTable'  => $foreignTable,
            'foreignKey'    => $foreignKey,
            'meta'          => $meta,
        ];

        return $this;
    }

    /**
     * Alter a table.
     *
     * @param string $table Table
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function alterTable(string $table) : self
    {
        $this->type       = QueryType::ALTER;
        $this->alterTable = $table;

        return $this;
    }

    /**
     * Add a constraint
     *
     * @param string $key          Key
     * @param string $foreignTable Foreign table
     * @param string $foreignKey   Foreign key
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function addConstraint(string $key, string $foreignTable, string $foreignKey, ?string $constraint = null) : self
    {
        $this->alterAdd['type']         = 'CONSTRAINT';
        $this->alterAdd['key']          = $key;
        $this->alterAdd['foreignTable'] = $foreignTable;
        $this->alterAdd['foreignKey']   = $foreignKey;
        $this->alterAdd['constraint']   = $constraint;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare() : ?\PDOStatement
    {
        $sth = null;
        $sql = '';

        try {
            $sth = $this->connection->con->prepare($sql = $this->toSql());
            if ($sth === false) {
                return null;
            }

            foreach ($this->binds as $key => $bind) {
                if (!isset($bind['type'])) {
                    $bind['type'] = self::getBindParamType($bind['value']);
                }

                $sth->bindParam(\is_int($key) ? $key + 1 : $key, $bind['value'], $bind['type']);
            }
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $sql,
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => \json_encode($this->binds),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            $sth = null;
            // @codeCoverageIgnoreEnd
        }

        return $sth;
    }

    /**
     * {@inheritdoc}
     */
    public function execute() : ?\PDOStatement
    {
        $sth = null;
        try {
            $sth = $this->prepare();
            if ($sth !== null) {
                $sth->execute();
            }

            if ($this->hasPostQuery) {
                $sqls = $this->grammar->compilePostQueries($this);

                foreach ($sqls as $post) {
                    $this->connection->con->exec($post);
                }
            }
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $this->toSql(),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => \json_encode($this->binds),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            $sth = null;
            // @codeCoverageIgnoreEnd
        }

        return $sth;
    }

    /**
     * {@inheritdoc}
     */
    public function toSql() : string
    {
        $components  = $this->grammar->compileComponents($this);
        $queryString = '';

        foreach ($components as $component) {
            if ($component !== '') {
                $queryString .= $component . ' ';
            }
        }

        $query = \substr($queryString, 0, -1) . ';';

        if (self::$log) {
            \phpOMS\Log\FileLogger::getInstance()->debug($query);
        }

        return $query;
    }
}
