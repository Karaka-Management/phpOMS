<?php
/**
 * Karaka
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

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Connection\SqlServerConnection;
use phpOMS\DataStorage\Database\Schema\Builder;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Schema\BuilderTest: Query builder for sql schemas
 *
 * @internal
 */
final class BuilderTest extends \PHPUnit\Framework\TestCase
{
    public function dbConnectionProvider() : array
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

    /**
     * @testdox Mysql database drop forms a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testMysqlDrop($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DROP DATABASE [test];';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);
        self::assertEquals($sql, $query->dropDatabase('test')->toSql());
    }

    /**
     * @testdox Mysql table drop forms a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testMysqlDropTable($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DROP TABLE [test];';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);
        self::assertEquals($sql, $query->dropTable('test')->toSql());
    }

    /**
     * @testdox Mysql show tables form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testMysqlShowTables($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [table_name] FROM [information_schema].[tables] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\';';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);
        self::assertEquals($sql, $query->selectTables()->toSql());
    }

    /**
     * @testdox Mysql show fields form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testMysqlShowFields($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT * FROM [information_schema].[columns] WHERE [table_schema] = \'' . $GLOBALS['CONFIG']['db']['core']['masters']['admin']['database']. '\' AND table_name = \'test\';';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);
        self::assertEquals($sql, $query->selectFields('test')->toSql());
    }

    /**
     * @testdox Mysql create tables form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testMysqlCreateTable($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'CREATE TABLE IF NOT EXISTS [user_roles] (user_id INT AUTO_INCREMENT, role_id VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY (user_id), FOREIGN KEY (user_id) REFERENCES users (ext1_id), FOREIGN KEY (role_id) REFERENCES roles (ext2_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, false, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, false, 'roles', 'ext2_id')
            ->toSql()
        );
    }

    /*
    public function testMysqlAlter($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'CREATE TABLE IF NOT EXISTS user_roles (user_id INT NOT NULL AUTO_INCREMENT, role_id VARCHAR(10) DEFAULT \'1\' NULL, PRIMARY KEY (user_id), FOREIGN KEY (user_id) REFERENCES users (ext1_id), FOREIGN KEY (role_id) REFERENCES roles (ext2_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);
        self::assertEquals(
            $sql,
            $query->createTable('user_roles')
                ->field('user_id', 'INT', null, false, true, false, true, 'users', 'ext1_id')
                ->field('role_id', 'VARCHAR(10)', '1', true, false, false, false, 'roles', 'ext2_id')
            ->toSql()
        );
    }
    */

    /**
     * @testdox The grammar correctly deletes a table
     * @covers phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar<extended>
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testMysqlCreateFromSchema($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DROP TABLE [test], [test_foreign];';
        $sql   = \str_replace(['[', ']'], [$iS, $iE], $sql);

        self::assertEquals(
            $sql,
            $query->dropTable('test')->dropTable('test_foreign')->toSql()
        );
    }
}
