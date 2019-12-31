<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Parameter;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Query\BuilderTest: Query builder for sql queries
 *
 * @internal
 */
class BuilderTest extends \PHPUnit\Framework\TestCase
{
    protected $con;

    protected function setUp() : void
    {
        $this->con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
    }

    /**
     * @testdox Mysql selects form a valid query
     * @group framework
     */
    public function testMysqlSelect() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` as t FROM `a` as b WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->selectAs('a.test', 't')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT DISTINCT `a`.`test` FROM `a` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->distinct()->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test`, `b`.`test` FROM `a`, `b` WHERE `a`.`test` = \'abc\';';
        self::assertEquals($sql, $query->select('a.test', 'b.test')->from('a', 'b')->where('a.test', '=', 'abc')->toSql());

        $query    = new Builder($this->con);
        $datetime = new \DateTime('now');
        $sql      = 'SELECT `a`.`test`, `b`.`test` FROM `a`, `b` WHERE `a`.`test` = \'' . $datetime->format('Y-m-d H:i:s') . '\';';
        self::assertEquals($sql, $query->select('a.test', 'b.test')->from('a', 'b')->where('a.test', '=', $datetime)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test`, `b`.`test` FROM `a`, `b` WHERE `a`.`test` = \'abc\' ORDER BY `a`.`test` ASC, `b`.`test` DESC;';
        self::assertEquals($sql,
            $query->select('a.test', 'b.test')
                ->from('a', 'b')
                ->where('a.test', '=', 'abc')
                ->orderBy(['a.test', 'b.test', ], ['ASC', 'DESC', ])
                ->toSql()
        );

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test`, `b`.`test` FROM `a`, `b` WHERE `a`.`test` = :abcValue ORDER BY `a`.`test` ASC, `b`.`test` DESC;';
        self::assertEquals($sql,
            $query->select('a.test', 'b.test')
                ->from('a', 'b')
                ->where('a.test', '=', new Parameter('abcValue'))
                ->orderBy(['a.test', 'b.test', ], ['ASC', 'DESC', ])
                ->toSql()
        );

        self::assertEquals($query->toSql(), $query->__toString());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` as b WHERE `a`.`test` = 1 ORDER BY \rand() LIMIT 1;';
        self::assertEquals($sql, $query->random('a.test')->fromAs('a', 'b')->where('a.test', '=', 1)->toSql());
    }

    /**
     * @testdox Mysql orders form a valid query
     * @group framework
     */
    public function testMysqlOrder() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 ORDER BY `a`.`test` DESC;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->newest('a.test')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 ORDER BY `a`.`test` ASC;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->oldest('a.test')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 ORDER BY `a`.`test` DESC;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy('a.test', 'DESC')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 ORDER BY `a`.`test` ASC;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy('a.test', 'ASC')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 ORDER BY `a`.`test`, `a`.`test2` DESC;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy(['a.test', 'a.test2'], ['DESC', 'DESC'])->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 ORDER BY `a`.`test`, `a`.`test2` ASC;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orderBy(['a.test', 'a.test2'], 'ASC')->toSql());
    }

    /**
     * @testdox Mysql offsets and limits form a valid query
     * @group framework
     */
    public function testMysqlOffsetLimit() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 LIMIT 3;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->limit(3)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OFFSET 3;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->offset(3)->toSql());
    }

    /**
     * @testdox Mysql groupings form a valid query
     * @group framework
     */
    public function testMysqlGroup() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 GROUP BY `a`;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->groupBy('a')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 GROUP BY `a`, `b`;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->groupBy('a')->groupBy('b')->toSql());

        $query = new Builder($this->con);
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->groupBy('a', 'b')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = :test GROUP BY `a`, `b`;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', new Parameter('test'))->groupBy('a', 'b')->toSql());
    }

    /**
     * @testdox Mysql wheres form a valid query
     * @group framework
     */
    public function testMysqlWheres() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 0;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', false)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', true)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = \'string\';';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 'string')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1.23;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1.23)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 AND `a`.`test2` = 2;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->where('a.test2', '=', 2, 'and')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 AND `a`.`test2` = 2;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->andWhere('a.test2', '=', 2)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OR `a`.`test2` = 2;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->where('a.test2', '=', 2, 'or')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OR `a`.`test2` = 2;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->orWhere('a.test2', '=', 2)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OR `a`.`test2` IS NULL;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereNull('a.test2', 'or')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OR `a`.`test2` IS NOT NULL;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereNotNull('a.test2', 'or')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OR `a`.`test2` IN (1, 2, 3);';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereIn('a.test2', [1, 2, 3], 'or')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = 1 OR `a`.`test2` IN (\'a\', \'b\', \'c\');';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', 1)->whereIn('a.test2', ['a', 'b', 'c'], 'or')->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` WHERE `a`.`test` = :testWhere OR `a`.`test2` IN (\'a\', :bValue, \'c\');';
        self::assertEquals($sql, $query->select('a.test')->from('a')->where('a.test', '=', new Parameter('testWhere'))->whereIn('a.test2', ['a', new Parameter('bValue'), 'c'], 'or')->toSql());
    }

    /**
     * @testdox Mysql joins form a valid query
     * @group framework
     */
    public function testMysqlJoins() : void
    {
        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->join('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` JOIN `b` ON `a`.`id` = `b`.`id` OR `a`.`id2` = `b`.`id2` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->join('b')->on('a.id', '=', 'b.id')->orOn('a.id2', '=', 'b.id2')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` JOIN `b` ON `a`.`id` = `b`.`id` AND `a`.`id2` = `b`.`id2` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->join('b')->on('a.id', '=', 'b.id')->andOn('a.id2', '=', 'b.id2')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` LEFT JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->leftJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` LEFT OUTER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->leftOuterJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` LEFT INNER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->leftInnerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` RIGHT JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->rightJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` RIGHT OUTER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->rightOuterJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` RIGHT INNER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->rightInnerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` OUTER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->outerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` INNER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->innerJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` CROSS JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->crossJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` FULL JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->fullJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'SELECT `a`.`test` FROM `a` FULL OUTER JOIN `b` ON `a`.`id` = `b`.`id` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->select('a.test')->from('a')->fullOuterJoin('b')->on('a.id', '=', 'b.id')->where('a.test', '=', 1)->toSql());
    }

    /**
     * @testdox Mysql inserts form a valid query
     * @group framework
     */
    public function testMysqlInsert() : void
    {
        $query = new Builder($this->con);
        $sql   = 'INSERT INTO `a` VALUES (1, \'test\');';
        self::assertEquals($sql, $query->insert()->into('a')->values(1, 'test')->toSql());

        $query = new Builder($this->con);
        $sql   = 'INSERT INTO `a` VALUES (1, \'test\');';
        self::assertEquals($sql, $query->insert()->into('a')->value([1, 'test'])->toSql());

        $query = new Builder($this->con);
        $sql   = 'INSERT INTO `a` (`test`, `test2`) VALUES (1, \'test\');';
        self::assertEquals($sql, $query->insert('test', 'test2')->into('a')->values(1, 'test')->toSql());
        self::assertEquals([[1, 'test']], $query->getValues());

        $query = new Builder($this->con);
        $sql   = 'INSERT INTO `a` (`test`, `test2`) VALUES (1, \'test\'), (2, \'test2\');';
        self::assertEquals($sql, $query->insert('test', 'test2')->into('a')->values(1, 'test')->values(2, 'test2')->toSql());

        $query = new Builder($this->con);
        $sql   = 'INSERT INTO `a` (`test`, `test2`) VALUES (:test, :test2);';
        self::assertEquals($sql, $query->insert('test', 'test2')->into('a')->values(new Parameter('test'), new Parameter('test2'))->toSql());
    }

    /**
     * @testdox Mysql deletes form a valid query
     * @group framework
     */
    public function testMysqlDelete() : void
    {
        $query = new Builder($this->con);
        $sql   = 'DELETE FROM `a` WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->delete()->from('a')->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'DELETE FROM `a` WHERE `a`.`test` = :testVal;';
        self::assertEquals($sql, $query->delete()->from('a')->where('a.test', '=', new Parameter('testVal'))->toSql());
    }

    /**
     * @testdox Mysql updates form a valid query
     * @group framework
     */
    public function testMysqlUpdate() : void
    {
        $query = new Builder($this->con);
        $sql   = 'UPDATE `a` SET `a`.`test` = 1, `a`.`test2` = 2 WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->update('a')->set(['a.test' => 1])->set(['a.test2' => 2])->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'UPDATE `a` SET `a`.`test` = 1, `a`.`test2` = 2 WHERE `a`.`test` = 1;';
        self::assertEquals($sql, $query->update('a')->sets('a.test', 1)->sets('a.test2', 2)->where('a.test', '=', 1)->toSql());

        $query = new Builder($this->con);
        $sql   = 'UPDATE `a` SET `a`.`test` = 1, `a`.`test2` = :test2 WHERE `a`.`test` = :test3;';
        self::assertEquals($sql, $query->update('a')->set(['a.test' => 1])->set(['a.test2' => new Parameter('test2')])->where('a.test', '=', new Parameter('test3'))->toSql());
    }

    /**
     * @testdox Raw queries get output as defined
     * @group framework
     */
    public function testRawInputOutput() : void
    {
        $query = new Builder($this->con);
        self::assertEquals('SELECT test.val FROM test;', $query->raw('SELECT test.val FROM test;')->toSql());
    }

    /**
     * @testdox Read only queries don't allow drops
     * @group framework
     */
    public function testReadOnlyRaw() : void
    {
        self::expectException(\Exception::class);

        $query = new Builder($this->con, true);
        $query->raw('DROP DATABASE oms;');
    }

    /**
     * @testdox Read only queries don't allow inserts
     * @group framework
     */
    public function testReadOnlyInsert() : void
    {
        self::expectException(\Exception::class);

        $query = new Builder($this->con, true);
        $query->insert('test');
    }

    /**
     * @testdox Read only queries don't allow updates
     * @group framework
     */
    public function testReadOnlyUpdate() : void
    {
        self::expectException(\Exception::class);

        $query = new Builder($this->con, true);
        $query->update();
    }

    /**
     * @testdox Read only queries don't allow deletes
     * @group framework
     */
    public function testReadOnlyDelete() : void
    {
        self::expectException(\Exception::class);

        $query = new Builder($this->con, true);
        $query->delete();
    }

    /**
     * @testdox Invalid select types throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidSelectParameter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->select(false);
    }

    /**
     * @testdox Invalid from types throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidFromParameter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->from(false);
    }

    /**
     * @testdox Invalid group types throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidGroupByParameter() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->groupBy(false);
    }

    /**
     * @testdox Invalid where operators throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidWhereOperator() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->where('a', 'invalid', 'b');
    }

    /**
     * @testdox Invalid join types throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidJoinTable() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->join(null);
    }

    /**
     * @testdox Invalid join operators throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidJoinOperator() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->join('b')->on('a', 'invalid', 'b');
    }

    /**
     * @testdox Invalid order types throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidOrderType() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->orderBy('a', 1);
    }

    /**
     * @testdox Invalid order column types throw a InvalidArgumentException
     * @group framework
     */
    public function testInvalidOrderColumnType() : void
    {
        self::expectException(\InvalidArgumentException::class);

        $query = new Builder($this->con, true);
        $query->orderBy(null, 'DESC');
    }
}
