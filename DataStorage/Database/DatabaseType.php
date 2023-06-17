<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\Stdlib\Base\Enum;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DatabaseType extends Enum
{
    public const MYSQL = 'mysql'; /* MySQL */

    public const SQLITE = 'sqlite'; /* SQLITE */

    public const PGSQL = 'pgsql'; /* PostgreSQL */

    public const SQLSRV = 'mssql'; /* Microsoft SQL Server */

    public const UNDEFINED = 'undefined';
}
