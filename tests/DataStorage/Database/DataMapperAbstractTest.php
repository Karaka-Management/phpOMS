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

namespace phpOMS\tests\DataStorage\Database;

include_once __DIR__ . '/../../Autoloader.php';

use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\tests\DataStorage\Database\TestModel\BaseModel;
use phpOMS\tests\DataStorage\Database\TestModel\BaseModelMapper;
use phpOMS\tests\DataStorage\Database\TestModel\Conditional;
use phpOMS\tests\DataStorage\Database\TestModel\ConditionalMapper;
use phpOMS\tests\DataStorage\Database\TestModel\ManyToManyDirectModelMapper;
use phpOMS\tests\DataStorage\Database\TestModel\ManyToManyRelModel;
use phpOMS\tests\DataStorage\Database\TestModel\ManyToManyRelModelMapper;
use phpOMS\tests\DataStorage\Database\TestModel\NullBaseModel;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Mapper\DataMapperAbstract::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Mapper\DataMapperFactory::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Mapper\ReadMapper::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Mapper\WriteMapper::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Mapper\UpdateMapper::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Mapper\DeleteMapper::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Mapper\DataMapperAbstractTest: Datamapper for database models')]
final class DataMapperAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected BaseModel $model;

    protected array $modelArray;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->model = new BaseModel();

        \phpOMS\Log\FileLogger::getInstance()->verbose = true;

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_base` (
                `test_base_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_base_string` varchar(254) NOT NULL,
                `test_base_compress` BLOB NOT NULL,
                `test_base_pstring` varchar(254) NOT NULL,
                `test_base_int` int(11) NOT NULL,
                `test_base_bool` tinyint(1) DEFAULT NULL,
                `test_base_null` int(11) DEFAULT NULL,
                `test_base_float` decimal(5, 4) DEFAULT NULL,
                `test_base_belongs_to_one` int(11) DEFAULT NULL,
                `test_base_belongs_top_one` int(11) DEFAULT NULL,
                `test_base_owns_one_self` int(11) DEFAULT NULL,
                `test_base_owns_onep_self` int(11) DEFAULT NULL,
                `test_base_json` varchar(254) DEFAULT NULL,
                `test_base_json_serializable` varchar(254) DEFAULT NULL,
                `test_base_serializable` varchar(254) DEFAULT NULL,
                `test_base_datetime` datetime DEFAULT NULL,
                `test_base_datetime_null` datetime DEFAULT NULL,
                PRIMARY KEY (`test_base_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_conditional` (
                `test_conditional_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_conditional_title` varchar(254) NOT NULL,
                `test_conditional_base` int(11) NOT NULL,
                `test_conditional_language` varchar(254) NOT NULL,
                PRIMARY KEY (`test_conditional_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_belongs_to_one` (
                `test_belongs_to_one_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_belongs_to_one_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_belongs_to_one_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_owns_one` (
                `test_owns_one_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_owns_one_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_owns_one_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_has_many_direct` (
                `test_has_many_direct_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_direct_string` varchar(254) NOT NULL,
                `test_has_many_direct_to` int(11) NOT NULL,
                PRIMARY KEY (`test_has_many_direct_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_has_many_rel` (
                `test_has_many_rel_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_rel_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_has_many_rel_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_has_many_rel_relations` (
                `test_has_many_rel_relations_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_rel_relations_src` int(11) NOT NULL,
                `test_has_many_rel_relations_dest` int(11) NOT NULL,
                PRIMARY KEY (`test_has_many_rel_relations_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        // private
        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_has_many_directp` (
                `test_has_many_directp_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_directp_string` varchar(254) NOT NULL,
                `test_has_many_directp_to` int(11) NOT NULL,
                PRIMARY KEY (`test_has_many_directp_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_has_many_relp` (
                `test_has_many_relp_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_relp_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_has_many_relp_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_has_many_rel_relationsp` (
                `test_has_many_rel_relationsp_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_has_many_rel_relationsp_src` int(11) NOT NULL,
                `test_has_many_rel_relationsp_dest` int(11) NOT NULL,
                PRIMARY KEY (`test_has_many_rel_relationsp_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_belongs_to_onep` (
                `test_belongs_to_onep_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_belongs_to_onep_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_belongs_to_onep_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `test_owns_onep` (
                `test_owns_onep_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_owns_onep_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_owns_onep_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();
    }

    protected function tearDown() : void
    {
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_conditional')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_base')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_belongs_to_one')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_owns_one')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_has_many_direct')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_has_many_rel')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_has_many_rel_relations')->execute();

        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_has_many_directp')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_has_many_relp')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_has_many_rel_relationsp')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_belongs_to_onep')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE test_owns_onep')->execute();

        \phpOMS\Log\FileLogger::getInstance()->verbose = false;
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datamapper successfully creates a database entry of a model')]
    public function testCreate() : void
    {
        self::assertGreaterThan(0, BaseModelMapper::create()->execute($this->model));
        self::assertGreaterThan(0, $this->model->id);
    }

    public function testCreateNullModel() : void
    {
        $nullModel1 = new NullBaseModel();
        self::assertNull(BaseModelMapper::create()->execute($nullModel1));

        $nullModel2 = new NullBaseModel(77);
        self::assertEquals(77, BaseModelMapper::create()->execute($nullModel2));
    }

    public function testCreateAlreadyCreatedModel() : void
    {
        self::assertGreaterThan(0, $id = BaseModelMapper::create()->execute($this->model));
        self::assertGreaterThan(0, $this->model->id);
        self::assertEquals($id, BaseModelMapper::create()->execute($this->model));
        self::assertEquals($id, $this->model->id);
    }

    public function testCreateHasManyRelation() : void
    {
        $id1 = BaseModelMapper::create()->execute($this->model);

        $count1 = \count($this->model->hasManyRelations);

        $hasMany = new ManyToManyRelModel();
        $id2     = ManyToManyRelModelMapper::create()->execute($hasMany);

        BaseModelMapper::writer()->createRelationTable('hasManyRelations', [$id2], $id1);

        $base = BaseModelMapper::get()->with('hasManyRelations')->where('id', $id1)->execute();
        self::assertCount($count1 + 1, $base->hasManyRelations);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datamapper successfully returns a database entry as model')]
    public function testRead() : void
    {
        $id = BaseModelMapper::create()->execute($this->model);

        /** @var BaseModel $modelR */
        $modelR = BaseModelMapper::get()
            ->with('belongsToOne')
            ->with('ownsOneSelf')
            ->with('hasManyDirect')
            ->with('hasMnayRelations')
            ->with('conditional')
            ->where('id', $id)
            ->execute();

        self::assertEquals($this->model->id, $modelR->id);
        self::assertEquals($this->model->string, $modelR->string);
        self::assertEquals($this->model->compress, $modelR->compress);
        self::assertEquals($this->model->getPString(), $modelR->getPString());
        self::assertEquals($this->model->int, $modelR->int);
        self::assertEquals($this->model->bool, $modelR->bool);
        self::assertEquals($this->model->float, $modelR->float);
        self::assertEquals($this->model->null, $modelR->null);
        self::assertEquals($this->model->datetime->format('Y-m-d'), $modelR->datetime->format('Y-m-d'));
        self::assertNull($modelR->datetime_null);

        self::assertEquals($this->model->json, $modelR->json);
        self::assertEquals([1, 2, 3], $modelR->jsonSerializable);
        self::assertEquals('123', $modelR->serializable->value);

        self::assertCount(2, $modelR->hasManyDirect);
        self::assertCount(2, $modelR->hasManyRelations);
        self::assertEquals(\reset($this->model->hasManyDirect)->string, \reset($modelR->hasManyDirect)->string);
        self::assertEquals(\reset($this->model->hasManyRelations)->string, \reset($modelR->hasManyRelations)->string);
        self::assertEquals($this->model->ownsOneSelf->string, $modelR->ownsOneSelf->string);
        self::assertEquals($this->model->belongsToOne->string, $modelR->belongsToOne->string);
    }

    public function testGetRaw() : void
    {
        $id = BaseModelMapper::create()->execute($this->model);

        /** @var BaseModel $modelR */
        $modelR = BaseModelMapper::getRaw()
            ->with('belongsToOne')
            ->with('ownsOneSelf')
            ->with('hasManyDirect')
            ->with('hasMnayRelations')
            ->with('conditional')
            ->where('id', $id)
            ->execute();

        self::assertTrue(\is_array($modelR));
    }

    public function testGetAll() : void
    {
        BaseModelMapper::create()->execute($this->model);
        self::assertCount(1, BaseModelMapper::getAll()->executeGetArray());
    }

    public function testGetYield() : void
    {
        BaseModelMapper::create()->execute($this->model);

        foreach (BaseModelMapper::yield()->execute() as $model) {
            self::assertGreaterThan(0, $model->id);
        }
    }

    public function testGetFor() : void
    {
        $id  = BaseModelMapper::create()->execute($this->model);
        $for = ManyToManyDirectModelMapper::getAll()->where('to', $id)->executeGetArray();

        self::assertEquals(
            \reset($this->model->hasManyDirect)->string,
            $for[1]->string
        );
    }

    public function testGetBy() : void
    {
        $model1         = new BaseModel();
        $model1->string = '123';

        $model2         = new BaseModel();
        $model2->string = '456';

        $id1 = BaseModelMapper::create()->execute($model1);
        $id2 = BaseModelMapper::create()->execute($model2);

        $by = BaseModelMapper::get()->where('string', '456')->execute();
        self::assertEquals($model2->id, $by->id);
    }

    public function testGetNewest() : void
    {
        $model1           = new BaseModel();
        $model1->datetime = new \DateTime('now');
        $id1              = BaseModelMapper::create()->execute($model1);

        \sleep(1);
        $model2           = new BaseModel();
        $model2->datetime = new \DateTime('now');
        $id2              = BaseModelMapper::create()->execute($model2);

        $newest = BaseModelMapper::getAll()->sort('id', OrderType::DESC)->limit(1)->executeGetArray();
        self::assertEquals($id2, \reset($newest)->id);
    }

    public function testGetNullModel() : void
    {
        self::assertEquals(NullBaseModel::class, \get_class(BaseModelMapper::get()->where('id', 99)->execute()));
    }

    public function testCount() : void
    {
        BaseModelMapper::create()->execute($this->model);
        self::assertEquals(1, BaseModelMapper::count()->executeCount());
    }

    public function testSum() : void
    {
        BaseModelMapper::create()->execute($this->model);
        self::assertEquals(11, BaseModelMapper::sum()->columns(['test_base_int'])->execute());
    }

    public function testExists() : void
    {
        $id = BaseModelMapper::create()->execute($this->model);
        self::assertTrue(BaseModelMApper::exists()->where('id', $id)->execute());
        self::assertFalse(BaseModelMApper::exists()->where('id', $id + 1)->execute());
    }

    public function testHas() : void
    {
        $id = BaseModelMapper::create()->execute($this->model);
        self::assertTrue(BaseModelMApper::has()->with('hasManyRelations')->where('id', $id)->execute());
        self::assertTrue(BaseModelMApper::has()->with('hasManyDirect')->where('id', $id)->execute());
    }

    public function testRandom() : void
    {
        $id = BaseModelMapper::create()->execute($this->model);
        self::assertEquals($id, BaseModelMApper::getRandom()->limit(1)->execute()->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testFind() : void
    {
        $model1 = clone $this->model;
        $model2 = clone $this->model;
        $model3 = clone $this->model;

        $model1->string = 'abc';
        $model2->string = 'hallo sir';
        $model3->string = 'seasiren';

        BaseModelMapper::create()->execute($model1);
        BaseModelMapper::create()->execute($model2);
        BaseModelMapper::create()->execute($model3);

        $found = BaseModelMapper::getAll()->where('string', '%sir%' , 'LIKE')->executeGetArray();
        self::assertCount(2, $found);
        self::assertEquals($model2->string, \reset($found)->string);
        self::assertEquals($model3->string, \end($found)->string);
    }

    public function testFind2() : void
    {
        $model1 = clone $this->model;
        $model2 = clone $this->model;
        $model3 = clone $this->model;

        $model1->string = 'abc';
        $model2->string = 'abcdef';
        $model3->string = 'zyx';

        BaseModelMapper::create()->execute($model1);
        BaseModelMapper::create()->execute($model2);
        BaseModelMapper::create()->execute($model3);

        $list = BaseModelMapper::find(
            search: 'abc',
            mapper: BaseModelMapper::getAll(),
            searchFields: ['string']
        );

        self::assertEquals(2, \count($list['data']));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testWithConditional() : void
    {
        $model1 = clone $this->model;
        $model2 = clone $this->model;
        $model3 = clone $this->model;

        $model1->string = 'abc';
        $model2->string = 'hallo sir';
        $model3->string = 'seasiren';

        $id1 = BaseModelMapper::create()->execute($model1);
        $id2 = BaseModelMapper::create()->execute($model2);
        $id3 = BaseModelMapper::create()->execute($model3);

        $cond1           = new Conditional();
        $cond1->language = 'de';
        $cond1->title    = 'cond1_de';
        $cond1->base     = $id1;
        ConditionalMapper::create()->execute($cond1);

        $cond2           = new Conditional();
        $cond2->language = 'en';
        $cond2->title    = 'cond1_en';
        $cond2->base     = $id1;
        ConditionalMapper::create()->execute($cond2);

        $cond3           = new Conditional();
        $cond3->language = 'de';
        $cond3->title    = 'cond2_de';
        $cond3->base     = $id2;
        ConditionalMapper::create()->execute($cond3);

        $found = BaseModelMapper::getAll()->with('conditional')->where('conditional/language', 'de')->executeGetArray();
        self::assertCount(2, $found);
        self::assertEquals($model1->string, \reset($found)->string);
        self::assertEquals($model2->string, \end($found)->string);
        self::assertEquals('cond1_de', \reset($found)->conditional);
        self::assertEquals('cond2_de', \end($found)->conditional);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datamapper successfully updates a database entry from a model')]
    public function testUpdate() : void
    {
        $id     = BaseModelMapper::create()->execute($this->model);
        $modelR = BaseModelMapper::get()->with('hasManyDirect')->with('hasManyRelations')->where('id', $id)->execute();

        $modelR->string        = 'Update';
        $modelR->int           = 321;
        $modelR->bool          = true;
        $modelR->float         = 3.15;
        $modelR->null          = null;
        $modelR->datetime      = new \DateTime('now');
        $modelR->datetime_null = null;

        $id2     = BaseModelMapper::update()->with('hasManyDirect')->with('hasManyRelations')->execute($modelR);
        $modelR2 = BaseModelMapper::get()->where('id', $id2)->execute();

        self::assertEquals($modelR->string, $modelR2->string);
        self::assertEquals($modelR->int, $modelR2->int);
        self::assertEquals($modelR->bool, $modelR2->bool);
        self::assertEquals($modelR->float, $modelR2->float);
        self::assertEquals($modelR->null, $modelR2->null);
        self::assertEquals($modelR->datetime->format('Y-m-d'), $modelR2->datetime->format('Y-m-d'));
        self::assertNull($modelR2->datetime_null);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datamapper successfully deletes a database entry from a model')]
    public function testDelete() : void
    {
        $id = BaseModelMapper::create()->execute($this->model);
        BaseModelMapper::delete()->with('belongsToOne')->with('ownsOneSelf')->with('hasManyRelations')->execute($this->model);
        $modelR = BaseModelMapper::get()->where('id', $id)->execute();

        self::assertInstanceOf('phpOMS\tests\DataStorage\Database\TestModel\NullBaseModel', $modelR);
    }

    public function testDeleteHasManyRelation() : void
    {
        $id1 = BaseModelMapper::create()->execute($this->model);

        $count1 = \count($this->model->hasManyRelations);

        /** @var ManyToManyRel $rel */
        $rel = \reset($this->model->hasManyRelations);

        BaseModelMapper::remover()->deleteRelationTable('hasManyRelations', [$rel->id], $id1);

        $base = BaseModelMapper::get()->with('hasManyRelations')->where('id', $id1)->execute();

        self::assertCount($count1 - 1, $base->hasManyRelations);
    }

    public function testReader() : void
    {
        self::assertInstanceOf('phpOMS\DataStorage\Database\Mapper\ReadMapper', BaseModelMapper::reader());
    }

    public function testWriter() : void
    {
        self::assertInstanceOf('phpOMS\DataStorage\Database\Mapper\WriteMapper', BaseModelMapper::writer());
    }

    public function testUpdater() : void
    {
        self::assertInstanceOf('phpOMS\DataStorage\Database\Mapper\UpdateMapper', BaseModelMapper::updater());
    }

    public function testRemover() : void
    {
        self::assertInstanceOf('phpOMS\DataStorage\Database\Mapper\DeleteMapper', BaseModelMapper::remover());
    }
}
