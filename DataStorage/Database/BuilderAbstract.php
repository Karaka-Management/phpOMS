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

use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class BuilderAbstract
{
    /**
     * Grammar.
     *
     * @var   GrammarAbstract
     * @since 1.0.0
     */
    protected GrammarAbstract $grammar;

    /**
     * Database connection.
     *
     * @var   ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $connection;

    /**
     * Query type.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $type = QueryType::NONE;

    /**
     * Prefix.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $prefix = '';

    /**
     * Raw.
     *
     * @var   string
     * @since 1.0.0
     */
    public string $raw = '';

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
     * Set table name prefix prefix
     *
     * @param string $prefix Table name prefix
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function prefix(string $prefix) : self
    {
        $this->prefix = $prefix;

        return $this;
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
     * Get prefix.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPrefix() : string
    {
        return $this->prefix;
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
}
