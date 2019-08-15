<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Connection\SqlServerConnection;

/**
 * @internal
 */
class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateMysql() : void
    {
        if (!\extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
              'The Mysql extension is not available.'
            );

            return;
        }

        self::assertInstanceOf(
            MysqlConnection::class,
            ConnectionFactory::create($GLOBALS['CONFIG']['db']['core']['masters']['admin'])
        );
    }

    public function testCreatePostgres() : void
    {
        if (!\extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped(
              'The Postresql extension is not available.'
            );

            return;
        }

        self::assertInstanceOf(
            PostgresConnection::class,
            ConnectionFactory::create($GLOBALS['CONFIG']['db']['core']['postgresql']['admin'])
        );
    }

    public function testCreateSqlsrv() : void
    {
        if (!\extension_loaded('pdo_sqlsrv')) {
            $this->markTestSkipped(
              'The Sqlsrv extension is not available.'
            );

            return;
        }

        self::assertInstanceOf(
            SqlServerConnection::class,
            ConnectionFactory::create($GLOBALS['CONFIG']['db']['core']['mssql']['admin'])
        );
    }

    public function testCreateSqlite() : void
    {
        if (!\extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped(
              'The SQLite extension is not available.'
            );

            return;
        }

        self::assertInstanceOf(
            SQLiteConnection::class,
            ConnectionFactory::create($GLOBALS['CONFIG']['db']['core']['sqlite']['admin'])
        );
    }

    public function testInvalidDatabaseType() : void
    {
        self::expectException(\InvalidArgumentException::class);

        ConnectionFactory::create(['db' => 'invalid']);
    }
}
