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

use phpOMS\DataStorage\DataStorageConnectionInterface;
use phpOMS\DataStorage\Database\Query\QueryType;

/**
 * Database query builder.
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
     * {@inheritdoc}
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
