<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Connection\SqlServerConnection;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\ConnectionFactory: Database connection factory
 *
 * @internal
 */
final class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The mysql connection can be successfully created
     * @covers \phpOMS\DataStorage\Database\Connection\ConnectionFactory
     * @group framework
     */
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

    /**
     * @testdox The postgresql connection can be successfully created
     * @covers \phpOMS\DataStorage\Database\Connection\ConnectionFactory
     * @group framework
     */
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

    /**
     * @testdox The sqlserver connection can be successfully created
     * @covers \phpOMS\DataStorage\Database\Connection\ConnectionFactory
     * @group framework
     */
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

    /**
     * @testdox The sqlite connection can be successfully created
     * @covers \phpOMS\DataStorage\Database\Connection\ConnectionFactory
     * @group framework
     */
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

    /**
     * @testdox A invalid database type throws a InvalidArgumentException
     * @covers \phpOMS\DataStorage\Database\Connection\ConnectionFactory
     * @group framework
     */
    public function testInvalidDatabaseType() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        ConnectionFactory::create(['db' => 'invalid']);
    }
}
