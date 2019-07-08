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
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\DataStorage\DataStorageConnectionInterface;

/**
 * Database query builder.
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class BuilderAbstract
{
    /**
     * Grammar.
     *
     * @var GrammarAbstract
     * @since 1.0.0
     */
    protected $grammar = null;

    /**
     * Database connection.
     *
     * @var DataStorageConnectionInterface
     * @since 1.0.0
     */
    protected $connection = null;

    /**
     * Query type.
     *
     * @var int
     * @since 1.0.0
     */
    protected $type = QueryType::NONE;

    /**
     * Prefix.
     *
     * @var string
     * @since 1.0.0
     */
    protected $prefix = '';

    /**
     * Raw.
     *
     * @var string
     * @since 1.0.0
     */
    public $raw = '';

    /**
     * Get connection
     *
     * @return DataStorageConnectionInterface
     *
     * @since  1.0.0
     */
    public function getConnection() : DataStorageConnectionInterface
    {
        return $this->connection;
    }

    /**
     * Set table name prefix prefix
     *
     * @param string $prefix Table name prefix
     *
     * @return self
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    abstract public function toSql() : string;
}
