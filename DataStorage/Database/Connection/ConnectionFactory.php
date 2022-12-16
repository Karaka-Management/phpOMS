<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\DatabaseType;

/**
 * Database connection factory.
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ConnectionFactory
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Create database connection.
     *
     * Overwrites current connection if existing
     *
     * @param array{db:string, host?:string, port?:int, login?:string, password?:string, database:string} $dbdata the basic database information for establishing a connection
     *
     * @return ConnectionAbstract
     *
     * @throws \InvalidArgumentException throws this exception if the database is not supported
     *
     * @since 1.0.0
     */
    public static function create(array $dbdata) : ConnectionAbstract
    {
        switch ($dbdata['db']) {
            case DatabaseType::MYSQL:
                return new MysqlConnection($dbdata);
            case DatabaseType::PGSQL:
                return new PostgresConnection($dbdata);
            case DatabaseType::SQLSRV:
                return new SqlServerConnection($dbdata);
            case DatabaseType::SQLITE:
                return new SQLiteConnection($dbdata);
            default:
                throw new \InvalidArgumentException('Database "' . $dbdata['db'] . '" is not supported.');
        }
    }
}
