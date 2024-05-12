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
use phpOMS\DataStorage\Database\Query\QueryType;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class BuilderAbstract
{
    /**
     * Is read only.
     *
     * @var bool
     * @since 1.0.0
     */
    protected bool $isReadOnly = false;

    /**
     * Use prepared statement.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $usePreparedStmt = true;

    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $connection;

    /**
     * Query type.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $type = QueryType::NONE;

    /**
     * Raw.
     *
     * @var string
     * @since 1.0.0
     */
    public string $raw = '';

    /**
     * Binds.
     *
     * @var array
     * @since 1.0.0
     */
    public array $binds = [];

    /**
     * Get connection
     *
     * @return ConnectionAbstract
     *
     * @since 1.0.0
     */
    public function getConnection() : ConnectionAbstract
    {
        return $this->connection;
    }

    /**
     * Escape string value
     *
     * @param string $value Value to escape
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function quote(string $value) : string
    {
        return $this->connection->con->quote($value);
    }

    /**
     * Bind parameter.
     *
     * @param array   $binds Binds
     * @param ?string $key   Key
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function bind(array $binds, ?string $key = null) : self
    {
        if ($key === null) {
            $this->binds[] = $binds;
        } else {
            $this->binds[$key] = $binds;
        }

        return $this;
    }

    /**
     * Get query type.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Parsing to sql string.
     *
     * @return string
     *
     * @since 1.0.0
     */
    abstract public function toSql() : string;

    /**
     * Execute query.
     *
     * @return ?\PDOStatement
     *
     * @since 1.0.0
     */
    abstract public function execute() : ?\PDOStatement;
}
