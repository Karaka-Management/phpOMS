<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Query\Grammar\Grammar;
use phpOMS\DataStorage\Database\Schema\Grammar\Grammar as SchemaGrammar;

/**
 * Database handler.
 *
 * Handles the database connection.
 * Implementing wrapper functions for multiple databases is planned (far away).
 *
 * @property \PDO $con
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ConnectionAbstract implements ConnectionInterface
{
    /**
     * Connection object.
     *
     * This can be used externally to define queries and execute them.
     *
     * @var \PDO
     * @since 1.0.0
     */
    protected \PDO $con;

    /**
     * Database data.
     *
     * @var array{db:string, database:string}|array{db:string, host:string, port:int, login:string, password:string, database:string}
     * @since 1.0.0
     */
    protected array $dbdata;

    /**
     * Database type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $type = DatabaseType::UNDEFINED;

    /**
     * Database status.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $status = DatabaseStatus::CLOSED;

    /**
     * Database grammar.
     *
     * @var Grammar
     * @since 1.0.0
     */
    protected Grammar $grammar;

    /**
     * Database grammar.
     *
     * @var SchemaGrammar
     * @since 1.0.0
     */
    protected SchemaGrammar $schemaGrammar;

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Get database name.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDatabase() : string
    {
        return $this->dbdata['database'] ?? '';
    }

    /**
     * Get database host.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getHost() : string
    {
        return $this->dbdata['host'] ?? '';
    }

    /**
     * Get database port.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getPort() : int
    {
        return (int) ($this->dbdata['port'] ?? 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getGrammar() : Grammar
    {
        return $this->grammar;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaGrammar() : SchemaGrammar
    {
        return $this->schemaGrammar;
    }

    /**
     * Connect to database
     *
     * @param null|array{db:string, database:string}|array{db:string, host:string, port:int, login:string, password:string, database:string} $dbdata the basic database information for establishing a connection
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function connect(array $dbdata = null) : void;

    /**
     * Object destructor.
     *
     * Sets the database connection to null
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close() : void
    {
        $this->con    = new NullPDO();
        $this->status = DatabaseStatus::CLOSED;
    }

    /**
     * Checks if the connection is initialized
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isInitialized() : bool
    {
        return !($this->con instanceof NullPDO);
    }

    /**
     * Get values
     *
     * @param string $name Variable name
     *
     * @return mixed Returns the value of the connection
     *
     * @since 1.0.0
     */
    public function __get(string $name) : mixed
    {
        return isset($this->{$name}) ? $this->{$name} : null;
    }

    /**
     * Start a transaction
     *
     * @since 1.0.0
     */
    abstract public function beginTransaction() : void;

    /**
     * Roll back a transaction
     *
     * @since 1.0.0
     */
    abstract public function rollBack() : void;

    /**
     * Commit a transaction
     *
     * @since 1.0.0
     */
    abstract public function commit() : void;
}
