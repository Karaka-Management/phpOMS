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
namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Query\Grammar\MysqlGrammar;

/**
 * Database handler.
 *
 * Handles the database connection.
 * Implementing wrapper functions for multiple databases is planned (far away).
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MysqlConnection extends ConnectionAbstract
{

    /**
     * Object constructor.
     *
     * Creates the database object and overwrites all default values.
     *
     * @param \string[] $dbdata the basic database information for establishing a connection
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(array $dbdata)
    {
        $this->type    = DatabaseType::MYSQL;
        $this->grammar = new MysqlGrammar();
        $this->connect($dbdata);
    }

    /**
     * {@inheritdoc}
     */
    public function connect(array $dbdata = null)
    {
        $this->close();

        $this->dbdata = isset($dbdata) ? $dbdata : $this->dbdata;
        $this->prefix = $dbdata['prefix'];

        try {
            $this->con = new \PDO($this->dbdata['db'] . ':host=' . $this->dbdata['host'] . ';dbname=' . $this->dbdata['database'] . ';charset=utf8', $this->dbdata['login'], $this->dbdata['password']);
            $this->con->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->status = DatabaseStatus::OK;
        } catch (\PDOException $e) {
            $this->status = DatabaseStatus::MISSING_DATABASE;
            $this->con    = null;
        }
    }

}
