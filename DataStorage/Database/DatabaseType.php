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
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\DataStorage\Database;

use phpOMS\Datatypes\Enum;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class DatabaseType extends Enum
{
    /* public */ const MYSQL = 0; /* MySQL */
    /* public */ const SQLITE = 1; /* SQLITE */
    /* public */ const PGSQL = 2; /* PostgreSQL */
    /* public */ const ORACLE = 3; /* Oracle */
    /* public */ const SQLSRV = 4; /* Microsoft SQL Server */
}
