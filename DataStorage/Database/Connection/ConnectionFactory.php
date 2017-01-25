<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;


/**
 * Database connection factory.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ConnectionFactory
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * Create database connection.
     *
     * Overwrites current connection if existing
     *
     * @param string[] $dbdata the basic database information for establishing a connection
     *
     * @return ConnectionInterface
     *
     * @throws \InvalidArgumentException Throws this exception if the database is not supported.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function create(array $dbdata) : ConnectionInterface
    {
        switch ($dbdata['db']) {
            case 'mysql':
                return new MysqlConnection($dbdata);
                break;
            case 'mssql':
                return new SqlServerConnection($dbdata);
                break;
            default:
                throw new \InvalidArgumentException('Database "' . $dbdata['db'] . '" is not supported.');
        }
    }
}
