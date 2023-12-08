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

namespace phpOMS\tests\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Connection\PostgresConnection;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Connection\SqlServerConnection;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Parameter;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Query\BuilderTest: Query builder for sql queries
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
     * @testdox Mysql selects form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testSelect($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] as t FROM [a] as b WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->selectAs('a.test', 't')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT DISTINCT [a].[test] FROM [a] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->distinct()->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test], [b].[test] FROM [a], [b] WHERE [a].[test] = \'abc\';';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test', 'b.test')->from('a', 'b')->where('a.test', '=', 'abc')->toSql());

        $query    = new Builder($con);
        $datetime = new \DateTime('now');
        $sql      = 'SELECT [a].[test], [b].[test] FROM [a], [b] WHERE [a].[test] = \'' . $datetime->format('Y-m-d H:i:s')
        . '\';';
        $sql      = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test', 'b.test')->from('a', 'b')->where('a.test', '=', $datetime)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test], [b].[test] FROM [a], [b] WHERE [a].[test] = \'abc\' ORDER BY [a].[test] ASC, [b].[test] DESC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql,
            $query->select('a.test', 'b.test')
                ->from('a', 'b')
                ->where('a.test', '=', 'abc')
                ->orderBy(['a.test', 'b.test', ], ['ASC', 'DESC', ])
                ->toSql()
        );

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test], [b].[test] FROM [a], [b] WHERE [a].[test] = :abcValue ORDER BY [a].[test] ASC, [b].[test] DESC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql,
            $query->select('a.test', 'b.test')
                ->from('a', 'b')
                ->where('a.test', '=', new Parameter('abcValue'))
                ->orderBy(['a.test', 'b.test', ], ['ASC', 'DESC', ])
                ->toSql()
        );

        self::assertEquals($query->toSql(), $query->__toString());
    }

    public function testRandomMysql() : void
    {
        $con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] as b WHERE [a].[test] = 1 ORDER BY RAND() LIMIT 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->random('a.test')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());
    }

    public function testRandomPostgresql() : void
    {
        $con = new PostgresConnection($GLOBALS['CONFIG']['db']['core']['postgresql']['admin']);

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] as b ORDER BY RANDOM() LIMIT 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->random('a.test')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());
    }

    public function testRandomSQLite() : void
    {
        $con = new SQLiteConnection($GLOBALS['CONFIG']['db']['core']['sqlite']['admin']);

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] as b ORDER BY RANDOM() LIMIT 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->random('a.test')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());
    }

    public function testRandomSqlServer() : void
    {
        $con = new SqlServerConnection($GLOBALS['CONFIG']['db']['core']['mssql']['admin']);

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT TOP 1 [a].[test] FROM [a] as b ORDER BY IDX FETCH FIRST 1 ROWS ONLY;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->random('a.test')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());
    }

    /**
     * @testdox Mysql orders form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testOrder($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 ORDER BY [a].[test] DESC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->newest('a.test')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 ORDER BY [a].[test] ASC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->oldest('a.test')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 ORDER BY [a].[test] DESC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy('a.test', 'DESC')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 ORDER BY [a].[test] ASC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy('a.test', 'ASC')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 ORDER BY [a].[test] DESC, [a].[test2] DESC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy(['a.test', 'a.test2'], ['DESC', 'DESC'])->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 ORDER BY [a].[test] ASC, [a].[test2] ASC;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy(['a.test', 'a.test2'], 'ASC')->toSql());
    }

    /**
     * @testdox Mysql offsets and limits form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testOffsetLimit($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 LIMIT 3;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->limit(3)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OFFSET 3;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->offset(3)->toSql());
    }

    /**
     * @testdox Mysql groupings form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testGroup($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 GROUP BY [a];';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->groupBy('a')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 GROUP BY [a], [b];';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->groupBy('a')->groupBy('b')->toSql());

        $query = new Builder($con);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->groupBy('a', 'b')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = :test GROUP BY [a], [b];';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', new Parameter('test'))->groupBy('a', 'b')->toSql());
    }

    /**
     * @testdox Mysql wheres form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testWheres($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 0;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', false)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', true)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = \'string\';';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 'string')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1.23;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1.23)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 AND [a].[test2] = 2;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->where('a.test2', '=', 2, 'and')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 AND [a].[test2] = 2;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->andWhere('a.test2', '=', 2)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OR [a].[test2] = 2;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->where('a.test2', '=', 2, 'or')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OR [a].[test2] = 2;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orWhere('a.test2', '=', 2)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OR [a].[test2] IS NULL;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereNull('a.test2', 'or')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OR [a].[test2] IS NOT NULL;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereNotNull('a.test2', 'or')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OR [a].[test2] IN (1, 2, 3);';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereIn('a.test2', [1, 2, 3], 'or')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = 1 OR [a].[test2] IN (\'a\', \'b\', \'c\');';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereIn('a.test2', ['a', 'b', 'c'], 'or')->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] WHERE [a].[test] = :testWhere OR [a].[test2] IN (\'a\', :bValue, \'c\');';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', new Parameter('testWhere'))->whereIn('a.test2', ['a', new Parameter('bValue'), 'c'], 'or')->toSql());
    }

    /**
     * @testdox Mysql joins form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testJoins($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->join('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] JOIN [b] ON [a].[id] = [b].[id] OR [a].[id2] = [b].[id2] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->join('b')->on('a.id', '=', 'b.id')->orOn('a.id2', '=', 'b.id2')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] JOIN [b] ON [a].[id] = [b].[id] AND [a].[id2] = [b].[id2] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->join('b')->on('a.id', '=', 'b.id')->andOn('a.id2', '=', 'b.id2')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] LEFT JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->leftJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] LEFT OUTER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->leftOuterJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] LEFT INNER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->leftInnerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] RIGHT JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->rightJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] RIGHT OUTER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->rightOuterJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] RIGHT INNER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->rightInnerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] OUTER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->outerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] INNER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->innerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] CROSS JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->crossJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] FULL JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->fullJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'SELECT [a].[test] FROM [a] FULL OUTER JOIN [b] ON [a].[id] = [b].[id] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->select('a.test')->from('a')->fullOuterJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());
    }

    /**
     * @testdox Mysql inserts form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testInsert($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'INSERT INTO [a] VALUES (1, \'test\');';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->insert()->into('a')->values(1, 'test')->toSql());

        $query = new Builder($con);
        $sql   = 'INSERT INTO [a] VALUES (1, \'test\');';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->insert()->into('a')->value([1, 'test'])->toSql());

        $query = new Builder($con);
        $sql   = 'INSERT INTO [a] ([test], [test2]) VALUES (1, \'test\');';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->insert('test', 'test2')->into('a')->values(1, 'test')->toSql());
        self::assertEquals([[1, 'test']], $query->getValues());

        $query = new Builder($con);
        $sql   = 'INSERT INTO [a] ([test], [test2]) VALUES (1, \'test\'), (2, \'test2\');';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->insert('test', 'test2')->into('a')->values(1, 'test')->values(2, 'test2')->toSql());

        $query = new Builder($con);
        $sql   = 'INSERT INTO [a] ([test], [test2]) VALUES (:test, :test2);';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->insert('test', 'test2')->into('a')->values(new Parameter('test'), new Parameter('test2'))->toSql());
    }

    /**
     * @testdox Mysql deletes form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testDelete($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'DELETE FROM [a] WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->delete()->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'DELETE FROM [a] WHERE [a].[test] = :testVal;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->delete()->from('a')->where('a.test', '=', new Parameter('testVal'))->toSql());
    }

    /**
     * @testdox Mysql updates form a valid query
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testUpdate($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        $sql   = 'UPDATE [a] SET [test] = 1, [test2] = 2 WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->update('a')->set(['test' => 1])->set(['test2' => 2])->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'UPDATE [a] SET [test] = 1, [test2] = 2 WHERE [a].[test] = 1;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->update('a')->sets('test', 1)->sets('test2', 2)->where('a.test', '=', 1)->toSql());

        $query = new Builder($con);
        $sql   = 'UPDATE [a] SET [test] = 1, [test2] = :test2 WHERE [a].[test] = :test3;';
        $sql   = \strtr($sql, '[]', $iS . $iE);
        self::assertEquals($sql, $query->update('a')->set(['test' => 1])->set(['test2' => new Parameter('test2')])->where('a.test', '=', new Parameter('test3'))->toSql());
    }

    /**
     * @testdox Raw queries get output as defined
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testRawInputOutput($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con);
        self::assertEquals('SELECT test.val FROM test;', $query->raw('SELECT test.val FROM test;')->toSql());
    }

    /**
     * @testdox Read only queries allow selects
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyRawSelect($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $query = new Builder($con, true);
        self::assertInstanceOf(Builder::class, $query->raw('SELECT * from oms;'));
    }

    /**
     * @testdox Read only queries don't allow drops
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyRawDrop($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->raw('DROP DATABASE oms;');
    }

    /**
     * @testdox Read only queries don't allow deletes
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyRawDelete($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->raw('DELETE oms;');
    }

    /**
     * @testdox Read only queries don't allow creates
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyRawCreate($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->raw('CREATE oms;');
    }

    /**
     * @testdox Read only queries don't allow modifications
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyRawAlter($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->raw('ALTER oms;');
    }

    /**
     * @testdox Read only queries don't allow inserts
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyInsert($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->insert('test');
    }

    /**
     * @testdox Read only queries don't allow updates
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyUpdate($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->update('table');
    }

    /**
     * @testdox Read only queries don't allow deletes
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testReadOnlyDelete($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\Exception::class);

        $query = new Builder($con, true);
        $query->delete();
    }

    /**
     * @testdox Invalid from types throw a InvalidArgumentException
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testInvalidFromParameter($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\InvalidArgumentException::class);

        $query = new Builder($con, true);
        $query->from(false);
    }

    /**
     * @testdox Invalid group types throw a InvalidArgumentException
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testInvalidGroupByParameter($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\InvalidArgumentException::class);

        $query = new Builder($con, true);
        $query->groupBy(false);
    }

    /**
     * @testdox Invalid where operators throw a InvalidArgumentException
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testInvalidWhereOperator($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\InvalidArgumentException::class);

        $query = new Builder($con, true);
        $query->where('a', 'invalid', 'b');
    }

    /**
     * @testdox Invalid join operators throw a InvalidArgumentException
     * @group framework
     * @dataProvider dbConnectionProvider
     */
    public function testInvalidJoinOperator($con) : void
    {
        if (!$con->isInitialized()) {
            self::markTestSkipped();

            return;
        }

        $iS = $con->getGrammar()->systemIdentifierStart;
        $iE = $con->getGrammar()->systemIdentifierEnd;

        $this->expectException(\InvalidArgumentException::class);

        $query = new Builder($con, true);
        $query->join('b')->on('a', 'invalid', 'b');
    }
}
