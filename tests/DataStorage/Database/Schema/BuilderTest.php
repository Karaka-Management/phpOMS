<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Connection\SqlServerConnection;
use phpOMS\DataStorage\Database\Schema\Builder;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Schema\BuilderTest: Query builder for sql schemas')]
final class BuilderTest extends \PHPUnit\Framework\TestCase
{
    public static function dbConnectionProvider() : array
    {
        $cons = [
            [new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])],
            [new PostgresConnection($GLOBALS['CONFIG']['db']['core']['postgresql']['admin'])],
            [new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin'])],
            [new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin'])],
        ];

        $cons[0][0]->connect();
        $cons[1][0]->connect();
        $cons[2][0]->connect();
        $cons[3][0]->connect();

        return $cons;
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dbConnectionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Mysql database drop forms a valid query')]
    public function testDrop($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DROP DATABASE [test];';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->dropDatabase('test')->toSql());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dbConnectionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Mysql table drop forms a valid query')]
    public function testDropTable($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DROP TABLE [test];';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->dropTable('test')->toSql());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dbConnectionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Mysql show tables form a valid query')]
    public function testShowTables($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);

        $sql = '';
        if ($con instanceof MysqlConnection) {
            $sql = 'SELECT [table_name] FROM [information_schema].[tables] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\';';
        } elseif ($con instanceof PostgresConnection) {
            $sql = 'SELECT [table_name] FROM [information_schema].[tables] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\';';
        } elseif ($con instanceof SqlServerConnection) {
            $sql = 'SELECT [table_name] FROM [sys].[tables] INNER JOIN [sys].[schemas] ON [sys].[tables.schema_id] = [sys].[schemas.schema_id];';
        } elseif ($con instanceof SQLiteConnection) {
            $sql = 'SELECT `name` FROM `sqlite_master` WHERE `type` = \'table\';';
        }

        $sql = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->selectTables()->toSql());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dbConnectionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Mysql show fields form a valid query')]
    public function testShowFields($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);

        $sql = '';
        if ($con instanceof MysqlConnection) {
            $sql = 'SELECT * FROM [information_schema].[columns] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND [table_name] = \'test\';';
        } elseif ($con instanceof PostgresConnection) {
            $sql = 'SELECT * FROM [information_schema].[columns] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND [table_name] = \'test\';';
        } elseif ($con instanceof SqlServerConnection) {
            $sql = 'SELECT * FROM [information_schema].[columns] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND [table_name] = \'test\';';
        } elseif ($con instanceof SQLiteConnection) {
            $sql = 'SELECT * FROM pragma_table_info(\'test\') WHERE pragma_table_info(\'test\') = \'test\';';
        }

        $sql = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->selectFields('test')->toSql());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dbConnectionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Mysql create tables form a valid query')]
    public function testCreateTable($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);

        $sql = '';
        if ($con instanceof MysqlConnection) {
            $sql = 'CREATE TABLE IF NOT EXISTS [user_roles] ([user_id] INT AUTO_INCREMENT, [role_id] VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY ([user_id]), FOREIGN KEY ([user_id]) REFERENCES [users] ([ext1_id]), FOREIGN KEY ([role_id]) REFERENCES [roles] ([ext2_id])) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        } elseif ($con instanceof PostgresConnection) {
            $sql = 'CREATE TABLE IF NOT EXISTS [user_roles] ([user_id] INT AUTO_INCREMENT, [role_id] VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY ([user_id]), FOREIGN KEY ([user_id]) REFERENCES [users] ([ext1_id]), FOREIGN KEY ([role_id]) REFERENCES [roles] ([ext2_id]));';
        } elseif ($con instanceof SqlServerConnection) {
            $sql = 'CREATE TABLE IF NOT EXISTS [user_roles] ([user_id] INT AUTO_INCREMENT, [role_id] VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY ([user_id]), FOREIGN KEY ([user_id]) REFERENCES [users] ([ext1_id]), FOREIGN KEY ([role_id]) REFERENCES [roles] ([ext2_id]));';
        } elseif ($con instanceof SQLiteConnection) {
            $sql = 'CREATE TABLE [user_roles] ([user_id] INTEGER AUTOINCREMENT PRIMARY KEY, [role_id] TEXT DEFAULT \'1\' NULL, FOREIGN KEY ([user_id]) REFERENCES [users] ([ext1_id]), FOREIGN KEY ([role_id]) REFERENCES [roles] ([ext2_id]));';
        }

        $sql = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, false, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, false, 'roles', 'ext2_id')
            ->toSql()
        );
    }

    /*
    public function testAlter($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'CREATE TABLE IF NOT EXISTS user_roles (user_id INT NOT NULL AUTO_INCREMENT, role_id VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY (user_id), FOREIGN KEY (user_id) REFERENCES users (ext1_id), FOREIGN KEY (role_id) REFERENCES roles (ext2_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, false, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, false, 'roles', 'ext2_id')
            ->toSql()
        );
    }
    */
    #[\PHPUnit\Framework\Attributes\DataProvider('dbConnectionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The grammar correctly deletes a table')]
    public function testCreateFromSchema($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DROP TABLE [test], [test_foreign];';
        $sql   = \strtr($sql, '[]', $iS . $iE);

        self::assertEquals(
            $sql,
            $query->dropTable('test')->dropTable('test_foreign')->toSql()
        );
    }
}
