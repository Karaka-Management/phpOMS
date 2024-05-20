<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Connection\ConnectionFactory::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Connection\ConnectionFactory: Database connection factory')]
final class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The mysql connection can be successfully created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The postgresql connection can be successfully created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The sqlserver connection can be successfully created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The sqlite connection can be successfully created')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid database type throws a InvalidArgumentException')]
    public function testInvalidDatabaseType() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        ConnectionFactory::create(['db' => 'invalid']);
    }
}
