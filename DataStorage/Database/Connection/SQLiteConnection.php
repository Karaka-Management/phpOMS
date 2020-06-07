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
use phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar;
use phpOMS\DataStorage\Database\Schema\Grammar\SQLiteGrammar as SQLiteSchemaGrammar;

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

        /**
         * @todo Orange-Management/phpOMS#219
         *  Don't automatically connect to the database during initialization. This should be done in a separate step.
         * This also requires to adjust some other framework code which currently expects the database connection to be established after initialization.
         *  Sometimes DB connections may not be needed and should only be connected to once required.
         */
        $this->connect($dbdata);
    }

    /**
     * Connect to database
     *
     * @param null|array{db:string, database:string} $dbdata the basic database information for establishing a connection
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function connect(array $dbdata = null) : void
    {
        $this->dbdata = $dbdata ?? $this->dbdata;

        if (!isset($this->dbdata['db'], $this->dbdata['database'])
            || !DatabaseType::isValidValue($this->dbdata['db'])
        ) {
            $this->status = DatabaseStatus::FAILURE;
            $this->dbdata['password'] = '****';
            //throw new InvalidConnectionConfigException((string) \json_encode($this->dbdata));

            return;
        }

        $this->close();

        try {
            $this->con = new \PDO($this->dbdata['db'] . ':' . $this->dbdata['database']);
            $this->con->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->status = DatabaseStatus::OK;
        } catch (\PDOException $e) {
            unset($this->con);
            $this->status = DatabaseStatus::MISSING_DATABASE;
        } finally {
            $this->dbdata['password'] = '****';
        }
    }
}
