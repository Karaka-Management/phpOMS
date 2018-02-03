<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\DataStorage\Database;

use phpOMS\Stdlib\Base\Enum;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class DatabaseType extends Enum
{
    /* public */ const MYSQL  = 'mysql'; /* MySQL */
    /* public */ const SQLITE = 'sqlite'; /* SQLITE */
    /* public */ const PGSQL  = 2; /* PostgreSQL */
    /* public */ const ORACLE = 3; /* Oracle */
    /* public */ const SQLSRV = 'mssql'; /* Microsoft SQL Server */
}
