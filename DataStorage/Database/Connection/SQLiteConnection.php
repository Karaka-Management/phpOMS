<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar;
use phpOMS\DataStorage\Database\Schema\Grammar\SQLiteGrammar as SQLiteSchemaGrammar;

/**
 * Database handler.
 *
 * Handles the database connection.
 * Implementing wrapper functions for multiple databases is planned (far away).
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SQLiteConnection extends ConnectionAbstract
{
    /**
     * Object constructor.
     *
     * Creates the database object and overwrites all default values.
     *
     * @param array{db:string, database:string} $dbdata the basic database information for establishing a connection
     *
     * @since 1.0.0
     */
    public function __construct(array $dbdata)
    {
        $this->type          = DatabaseType::SQLITE;
        $this->grammar       = new SQLiteGrammar();
        $this->schemaGrammar = new SQLiteSchemaGrammar();

        if (isset($dbdata['datetimeformat'])) {
            $this->grammar->setDateTimeFormat($dbdata['datetimeformat']);
            $this->schemaGrammar->setDateTimeFormat($dbdata['datetimeformat']);
        }

        $this->dbdata = $dbdata;
    }

    /**
     * Connect to database
     *
     * @param null|array{db:string, database:string} $dbdata the basic database information for establishing a connection
     *
     * @return void
     *
     * @throws \PDOException
     *
     * @since 1.0.0
     */
    public function connect(?array $dbdata = null) : void
    {
        if ($this->status === DatabaseStatus::OK) {
            return;
        }

        $this->dbdata = $dbdata ?? $this->dbdata;

        if (!isset($this->dbdata['db'], $this->dbdata['database'])
            || !DatabaseType::isValidValue($this->dbdata['db'])
        ) {
            $this->status             = DatabaseStatus::FAILURE;
            $this->dbdata['password'] = '****';

            return;
        }

        $this->close();

        try {
            $this->con = new \PDO($this->dbdata['db'] . ':' . $this->dbdata['database']);
            $this->con->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            if (!\is_file($this->dbdata['database'])) {
                throw new \PDOException();
            }

            $this->status = DatabaseStatus::OK;
        } catch (\PDOException $_) {
            $this->con    = new NullPDO();
            $this->status = DatabaseStatus::MISSING_DATABASE;
        } finally {
            $this->dbdata['password'] = '****';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction() : void
    {
        $this->con->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function rollBack() : void
    {
        $this->con->rollBack();
    }

    /**
     * {@inheritdoc}
     */
    public function commit() : void
    {
        $this->con->commit();
    }
}
