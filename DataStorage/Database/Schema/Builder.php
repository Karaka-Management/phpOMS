<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\DataStorage\Database\Schema\Query;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query;
use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * Database query builder.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Builder extends BuilderAbstract
{
    private $type = QueryType::SELECT;

    private $table = [];

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ConnectionAbstract $connection)
    {
        $this->connection = $connection;
        $this->grammar    = $connection->getSchemaGrammar();
    }

    public function select(...$table)
    {
        $this->type = QueryType::SELECT;
        $this->table += $table;
        $this->table = array_unique($this->table);
    }

    public function drop(\string $table)
    {

    }

    public function create(\string $table)
    {

    }

    public function alter(array $column)
    {

    }
}
