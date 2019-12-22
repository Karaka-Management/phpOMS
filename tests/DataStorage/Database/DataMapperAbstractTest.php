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
namespace phpOMS\tests\DataStorage\Database;

use phpOMS\tests\DataStorage\Database\TestModel\BaseModel;
use phpOMS\tests\DataStorage\Database\TestModel\BaseModelMapper;
use phpOMS\tests\DataStorage\Database\TestModel\ManyToManyDirectModelMapper;

/**
 * @testdox phpOMS\tests\DataStorage\Database\DataMapperAbstract: Datamapper for database models
 *
 * @internal
 */
class DataMapperAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $model      = null;
    protected $modelArray = null;

    protected function setUp() : void
    {
        $this->model      = new BaseModel();
        $this->modelArray = [
            'id' => 0,
            'string' => 'Base',
            'int' => 11,
            'bool' => false,
            'null' => null,
            'float' => 1.3,
            'json' => [1, 2, 3],
            'jsonSerializable' => new class() implements \JsonSerializable {
                public function jsonSerialize()
                {
                    return [1, 2, 3];
                }
            },
            'datetime' => new \DateTime('2005-10-11'),
            'datetime_null' => null,
            'ownsOneSelf' => [
                'id' => 0,
                'string' => 'OwnsOne',
            ],
            'belongsToOne' => [
                'id' => 0,
                'string' => 'BelongsTo',
            ],
            'hasManyDirect' => [
                [
                    'id' => 0,
                    'string' => 'ManyToManyDirect',
                    'to' => 0,
                ],
                [
                    'id' => 0,
                    'string' => 'ManyToManyDirect',
                    'to' => 0,
                ],
            ],
            'hasManyRelations' => [
                [
                    'id' => 0,
                    'string' => 'ManyToManyRel',
                ],
                [
                    'id' => 0,
                    'string' => 'ManyToManyRel',
                ],
            ],
        ];

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_base` (
                `test_base_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_base_string` varchar(254) NOT NULL,
                `test_base_int` int(11) NOT NULL,
                `test_base_bool` tinyint(1) DEFAULT NULL,
                `test_base_null` int(11) DEFAULT NULL,
                `test_base_float` decimal(5, 4) DEFAULT NULL,
                `test_base_belongs_to_one` int(11) DEFAULT NULL,
                `test_base_owns_one_self` int(11) DEFAULT NULL,
                `test_base_json` varchar(254) DEFAULT NULL,
                `test_base_json_serializable` varchar(254) DEFAULT NULL,
                `test_base_datetime` datetime DEFAULT NULL,
                `test_base_datetime_null` datetime DEFAULT NULL, /* There was a bug where it returned the current date because new \DateTime(null) === current date which is wrong, we want null as value! */
                PRIMARY KEY (`test_base_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_belongs_to_one` (
                `test_belongs_to_one_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_belongs_to_one_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_belongs_to_one_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_owns_one` (
                `test_owns_one_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_owns_one_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_owns_one_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_has_many_direct` (
                `test_has_many_direct_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_direct_string` varchar(254) NOT NULL,
                `test_has_many_direct_to` int(11) NOT NULL,
                PRIMARY KEY (`test_has_many_direct_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_has_many_rel` (
                `test_has_many_rel_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_rel_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_has_many_rel_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_has_many_rel_relations` (
                `test_has_many_rel_relations_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_rel_relations_src` int(11) NOT NULL,
                `test_has_many_rel_relations_dest` int(11) NOT NULL,
                PRIMARY KEY (`test_has_many_rel_relations_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();
    }

    protected function tearDown() : void
    {
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_base')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_belongs_to_one')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_owns_one')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_has_many_direct')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_has_many_rel')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_has_many_rel_relations')->execute();
    }

    /**
     * @testdox The datamapper has the expected default values after initialization
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('test_base_id', BaseModelMapper::getPrimaryField());
        self::assertEquals('test_base', BaseModelMapper::getTable());
        self::assertEquals('test_base_datetime', BaseModelMapper::getCreatedAt());
    }

    /**
     * @testdox The datamapper successfully creates a database entry of a model
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testCreate() : void
    {
        self::assertGreaterThan(0, BaseModelMapper::create($this->model));
        self::assertGreaterThan(0, $this->model->id);
    }

    /**
     * @testdox The datamapper successfully creates a database entry of array data
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testCreateArray() : void
    {
        self::assertGreaterThan(0, BaseModelMapper::createArray($this->modelArray));
        self::assertGreaterThan(0, $this->modelArray['id']);
    }

    /**
     * @testdox The datamapper successfully returns a database entry as model
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testRead() : void
    {
        $id     = BaseModelMapper::create($this->model);
        $modelR = BaseModelMapper::get($id);

        self::assertEquals($this->model->id, $modelR->id);
        self::assertEquals($this->model->string, $modelR->string);
        self::assertEquals($this->model->int, $modelR->int);
        self::assertEquals($this->model->bool, $modelR->bool);
        self::assertEquals($this->model->float, $modelR->float);
        self::assertEquals($this->model->null, $modelR->null);
        self::assertEquals($this->model->datetime->format('Y-m-d'), $modelR->datetime->format('Y-m-d'));
        self::assertNull($modelR->datetime_null);

        // todo implement these
        //self::assertEquals('123', $modelR->serializable);
        //self::assertEquals($this->model->json, $modelR->json);
        //self::assertEquals([1, 2, 3], $modelR->jsonSerializable);

        self::assertCount(2, $modelR->hasManyDirect);
        self::assertCount(2, $modelR->hasManyRelations);
        self::assertEquals(\reset($this->model->hasManyDirect)->string, \reset($modelR->hasManyDirect)->string);
        self::assertEquals(\reset($this->model->hasManyRelations)->string, \reset($modelR->hasManyRelations)->string);
        self::assertEquals($this->model->ownsOneSelf->string, $modelR->ownsOneSelf->string);
        self::assertEquals($this->model->belongsToOne->string, $modelR->belongsToOne->string);

        $for = ManyToManyDirectModelMapper::getFor($id, 'to');
        self::assertEquals(\reset($this->model->hasManyDirect)->string, \reset($for)->string);

        self::assertCount(1, BaseModelMapper::getAll());
    }

    /**
     * @testdox The datamapper successfully returns a database entry as array
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testReadArray() : void
    {
        $id     = BaseModelMapper::createArray($this->modelArray);
        $modelR = BaseModelMapper::getArray($id);

        self::assertEquals($this->modelArray['id'], $modelR['id']);
        self::assertEquals($this->modelArray['string'], $modelR['string']);
        self::assertEquals($this->modelArray['int'], $modelR['int']);
        self::assertEquals($this->modelArray['bool'], $modelR['bool']);
        self::assertEquals($this->modelArray['float'], $modelR['float']);
        self::assertEquals($this->modelArray['null'], $modelR['null']);
        self::assertEquals($this->modelArray['datetime']->format('Y-m-d'), $modelR['datetime']->format('Y-m-d'));
        self::assertNull($modelR['datetime_null']);

        self::assertCount(2, $modelR['hasManyDirect']);
        self::assertCount(2, $modelR['hasManyRelations']);
        self::assertEquals(\reset($this->modelArray['hasManyDirect'])['string'], \reset($modelR['hasManyDirect'])['string']);
        self::assertEquals(\reset($this->modelArray['hasManyRelations'])['string'], \reset($modelR['hasManyRelations'])['string']);
        self::assertEquals($this->modelArray['ownsOneSelf']['string'], $modelR['ownsOneSelf']['string']);
        self::assertEquals($this->modelArray['belongsToOne']['string'], $modelR['belongsToOne']['string']);

        $for = ManyToManyDirectModelMapper::getForArray($id, 'to');
        self::assertEquals(\reset($this->modelArray['hasManyDirect'])['string'], \reset($for)['string']);

        self::assertCount(1, BaseModelMapper::getAllArray());
    }

    /**
     * @testdox The datamapper successfully updates a database entry from a model
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testUpdate() : void
    {
        $id     = BaseModelMapper::create($this->model);
        $modelR = BaseModelMapper::get($id);

        $modelR->string   = 'Update';
        $modelR->int      = '321';
        $modelR->bool     = true;
        $modelR->float    = 3.15;
        $modelR->null     = null;
        $modelR->datetime = new \DateTime('now');
        $modelR->datetime_null = null;

        $id2     = BaseModelMapper::update($modelR);
        $modelR2 = BaseModelMapper::get($id2);

        self::assertEquals($modelR->string, $modelR2->string);
        self::assertEquals($modelR->int, $modelR2->int);
        self::assertEquals($modelR->bool, $modelR2->bool);
        self::assertEquals($modelR->float, $modelR2->float);
        self::assertEquals($modelR->null, $modelR2->null);
        self::assertEquals($modelR->datetime->format('Y-m-d'), $modelR2->datetime->format('Y-m-d'));
        self::assertNull($modelR2->datetime_null);

        // todo test update relations
    }

    /**
     * @testdox The datamapper successfully updates a database entry from an array
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testUpdateArray() : void
    {
        $id     = BaseModelMapper::createArray($this->modelArray);
        $modelR = BaseModelMapper::getArray($id);

        $modelR['string']   = 'Update';
        $modelR['int']      = '321';
        $modelR['bool']     = true;
        $modelR['float']    = 3.15;
        $modelR['null']     = null;
        $modelR['datetime'] = new \DateTime('now');
        $modelR['datetime_null'] = null;

        $id2     = BaseModelMapper::updateArray($modelR);
        $modelR2 = BaseModelMapper::getArray($id2);

        self::assertEquals($modelR['string'], $modelR2['string']);
        self::assertEquals($modelR['int'], $modelR2['int']);
        self::assertEquals($modelR['bool'], $modelR2['bool']);
        self::assertEquals($modelR['float'], $modelR2['float']);
        self::assertEquals($modelR['null'], $modelR2['null']);
        self::assertEquals($modelR['datetime']->format('Y-m-d'), $modelR2['datetime']->format('Y-m-d'));
        self::assertNull($modelR2['datetime_null']);

        // todo test update relations
    }

    /**
     * @testdox The datamapper successfully deletes a database entry from a model
     * @covers phpOMS\DataStorage\Database\DataMapperAbstract
     * @group framework
     */
    public function testDelete() : void
    {
        $id = BaseModelMapper::create($this->model);
        BaseModelMapper::delete($this->model);
        $modelR = BaseModelMapper::get($id);

        self::assertInstanceOf('phpOMS\tests\DataStorage\Database\TestModel\NullBaseModel', $modelR);

        // todo test if relations also deleted
    }
}
