<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Where extends Builder
{
    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $connection)
    {
        parent::__construct($connection);
        $this->type = QueryType::SELECT;
    }

    /**
     * {@inheritdoc}
     */
    public function toSql() : string
    {
        $query = $this->grammar->compileWheres($this, $this->wheres);
        $query = \str_starts_with($query, 'WHERE ') ? \substr($query, 6) : $query;

        if (self::$log) {
            \phpOMS\Log\FileLogger::getInstance()->debug($query);
        }

        return $query;
    }
}
