<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Exception\InvalidConnectionConfigException;
use phpOMS\DataStorage\Database\Query\Grammar\MysqlGrammar;
use phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar as MysqlSchemaGrammar;

/**
 * Database handler.
 *
 * Handles the database connection.
 * Implementing wrapper functions for multiple databases is planned (far away).
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class MysqlConnection extends ConnectionAbstract
{
    /**
     * Object constructor.
     *
     * Creates the database object and overwrites all default values.
     *
     * @param string[] $dbdata the basic database information for establishing a connection
     *
     * @since 1.0.0
     */
    public function __construct(array $dbdata)
    {
        $this->type          = DatabaseType::MYSQL;
        $this->grammar       = new MysqlGrammar();
        $this->schemaGrammar = new MysqlSchemaGrammar();
        $this->connect($dbdata); // todo: remove since this is a side effect that doesn't belong to constructor
    }

    /**
     * {@inheritdoc}
     */
    public function connect(array $dbdata = null) : void
    {
        $this->dbdata = isset($dbdata) ? $dbdata : $this->dbdata;

        if (!isset($this->dbdata['db'], $this->dbdata['host'], $this->dbdata['port'], $this->dbdata['database'], $this->dbdata['login'], $this->dbdata['password'])
            || !DatabaseType::isValidValue($this->dbdata['db'])
        ) {
            $this->status = DatabaseStatus::FAILURE;
            throw new InvalidConnectionConfigException((string) \json_encode($this->dbdata));
        }

        $this->close();
        $this->prefix = $dbdata['prefix'] ?? '';

        try {
            $this->con = new \PDO($this->dbdata['db'] . ':host=' . $this->dbdata['host'] . ':' . $this->dbdata['port'] . ';dbname=' . $this->dbdata['database'] . ';charset=utf8mb4', $this->dbdata['login'], $this->dbdata['password']);
            $this->con->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->status = DatabaseStatus::OK;
        } catch (\PDOException $e) {
            unset($this->con);
            $this->status = DatabaseStatus::MISSING_DATABASE;
            throw new InvalidConnectionConfigException((string) \json_encode($this->dbdata));
        } finally {
            $this->dbdata['password'] = '****';
        }
    }
}
