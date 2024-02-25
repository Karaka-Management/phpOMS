<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
class Concat extends Builder
{
    public string $delim = '';

    public string $as = '';

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

    public function columns(string $as, string $delim, ...$columns) : void
    {
        $this->delim = $delim;
        $this->as = $as;

        $this->select($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function toSql() : string
    {
        $query = $this->grammar->compileConcat($this, $this->selects);

        if (self::$log) {
            \phpOMS\Log\FileLogger::getInstance()->debug($query);
        }

        return $query;
    }
}