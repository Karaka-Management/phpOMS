<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\File;

use phpOMS\DataStorage\File\JsonBuilder;

class JsonBuilderTest extends \PHPUnit\Framework\TestCase
{
    private $table1 = [];
    private $table2 = [];

    protected function setUp() : void
    {
        $this->table1 = \json_decode(\file_get_contents(__DIR__ . '/testDb1.json'), true);
        $this->table2 = \json_decode(\file_get_contents(__DIR__ . '/testDb2.json'), true);
    }

    public function testJsonSelect() : void
    {
        $this->markTestSkipped();

        $query = new JsonBuilder();
        self::assertEquals('acc1', $query->select('/0/account/*/name')->from($this->table1, $this->table2)->where('/0/account/*/id', '=', 1)->execute()['name']);
        self::assertEquals('acc6', $query->select('/1/account/*/name')->from($this->table1, $this->table2)->where('/1/account/*/id', '=', 2)->execute()['name']);

        //$query = new JsonBuilder();
        //self::assertEquals($sql, $query->select('a.test')->distinct()->from('a')->where('a.test', '=', 1)->execute());

        $query    = new JsonBuilder();
        $datetime = new \DateTime('1999-31-12');
        self::assertEquals('dog2', $query->select('/0/animals/dog')->from($this->table2)->where('/0/animals/dog/created', '>', $datetime)->execute()['name']);

        $table            = $this->table2;
        $query            = new JsonBuilder();
        self::assertEquals(['dog1', 'dog2', 'cat2'], $query->select('/0/animals/dog/*/name', function () {
            return '/0/animals/cat/*/name';
        })->from(function () use ($table) {
            return $table;
        })->where(['/0/animals/cat/*/owner', '/0/animals/dog/*/owner'], ['=', '='], [1, 4], ['and', 'or'])->execute());
    }

    public function testJsonOrder() : void
    {
        $this->markTestSkipped();

        $query = new JsonBuilder();
        self::assertEquals(['acc2', 'acc1', 'acc4'],
            $query->select('/0/account/*/name')
                ->from($this->table1)
                ->where('/0/account/*/id', '>', 0)
                ->orderBy('/0/account/status', 'ASC')
                ->execute()
        );
    }

    public function testJsonOffsetLimit() : void
    {
        $this->markTestSkipped();

        $query = new JsonBuilder();
        self::assertEquals(['acc2', 'acc1'],
            $query->select('/0/account/*/name')
                ->from($this->table1)
                ->where('/0/account/*/id', '>', 0)
                ->orderBy('/0/account/status', 'ASC')
                ->limit(2)
                ->execute()
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testReadOnlyInsert() : void
    {
        $query = new JsonBuilder(true);
        $query->insert('test');
    }

    /**
     * @expectedException \Exception
     */
    public function testReadOnlyUpdate() : void
    {
        $query = new JsonBuilder(true);
        $query->update();
    }

    /**
     * @expectedException \Exception
     */
    public function testReadOnlyDelete() : void
    {
        $query = new JsonBuilder(true);
        $query->delete();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidWhereOperator() : void
    {
        $query = new JsonBuilder(true);
        $query->where('a', 'invalid', 'b');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJoinTable() : void
    {
        $query = new JsonBuilder(true);
        $query->join(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJoinOperator() : void
    {
        $query = new JsonBuilder(true);
        $query->join('b')->on('a', 'invalid', 'b');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOrOrderType() : void
    {
        $query = new JsonBuilder(true);
        $query->orderBy('a', 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOrColumnType() : void
    {
        $query = new JsonBuilder(true);
        $query->orderBy(null, 'DESC');
    }
}