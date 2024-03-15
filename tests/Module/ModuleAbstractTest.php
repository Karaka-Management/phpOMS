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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Event\EventManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\tests\DataStorage\Database\TestModel\BaseModel;
use phpOMS\tests\DataStorage\Database\TestModel\BaseModelMapper;
use phpOMS\tests\DataStorage\Database\TestModel\ManyToManyRelModel;
use phpOMS\tests\DataStorage\Database\TestModel\ManyToManyRelModelMapper;

/**
 * @testdox phpOMS\tests\Module\ModuleAbstractTest: Abstract module
 *
 * @internal
 */
final class ModuleAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $module = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->module = new class() extends ModuleAbstract
        {
            public const PATH = __DIR__ . '/Test';

            public const VERSION = '1.2.3';

            public const NAME = 'Test';

            public const ID = 2;

            public static array $dependencies = [1, 2];

            public function __construct()
            {
                $this->app                = new class() extends ApplicationAbstract {};
                $this->app->eventManager  = new EventManager();
                $this->app->moduleManager = new ModuleManager($this->app, __DIR__);
            }

            public function fillJson(HttpRequest $request, HttpResponse $response, string $status, string $title, string $message, array $data) : void
            {
                $this->fillJsonResponse($request, $response, $status, $title, $message, $data);
            }

            public function fillJsonRaw(HttpRequest $request, HttpResponse $response, array $data) : void
            {
                $this->fillJsonRawResponse($request, $response, $data);
            }

            public function create() : void
            {
                $model                   = new BaseModel();
                $model->hasManyRelations = [];
                $this->createModel(1, $model, BaseModelMapper::class, '', '127.0.0.1');
            }

            public function createMultiple() : void
            {
                $models = [];

                $models[]                    = new BaseModel();
                $models[0]->hasManyRelations = [];

                $models[]                    = new BaseModel();
                $models[1]->hasManyRelations = [];

                $this->createModels(1, $models, BaseModelMapper::class, '', '127.0.0.1');
            }

            public function createRelationModel() : void
            {
                $model = new ManyToManyRelModel();
                ManyToManyRelModelMapper::create()->execute($model);
            }

            public function createRelationDB() : void
            {
                $model1 = BaseModelMapper::get()->where('id', 1)->execute();
                $model2 = ManyToManyRelModelMapper::get()->where('id', 1)->execute();

                $this->createModelRelation(1, $model1->id, $model2->id, BaseModelMapper::class, 'hasManyRelations', '', '127.0.0.1');
            }

            public function deleteRelationDB() : void
            {
                $model1 = BaseModelMapper::get()->where('id', 1)->execute();
                $model2 = ManyToManyRelModelMapper::get()->where('id', 1)->execute();

                $this->deleteModelRelation(1, $model1->id, $model2->id, BaseModelMapper::class, 'hasManyRelations', '', '127.0.0.1');
            }

            public function creates() : void
            {
                $model1 = new BaseModel();
                $model2 = new BaseModel();
                $this->createModels(1, [$model1, $model2], BaseModelMapper::class, '', '127.0.0.1');
            }

            public function update() : void
            {
                $old = new BaseModel();
                BaseModelMapper::create()->execute($old);

                $new         = clone $old;
                $new->string = 'Updated';

                $this->updateModel(1, $old, $new, BaseModelMapper::class, '', '127.0.0.1');
            }

            public function delete() : void
            {
                $model = BaseModelMapper::get()->where('id', 1)->execute();
                $this->deleteModel(1, $model, BaseModelMapper::class, '', '127.0.0.1');
            }

            public function createWithCallable() : string
            {
                \ob_start();
                $this->createModel(1, null, function() : void { echo 1; }, '', '127.0.0.1');
                return \ob_get_clean();
            }

            public function createsWithCallable() : string
            {
                \ob_start();
                $this->createModels(1, [null, null], function() : void { echo 1; }, '', '127.0.0.1');
                return \ob_get_clean();
            }

            public function updateWithCallable() : string
            {
                \ob_start();
                $this->updateModel(1, null, null, function() : void { echo 1; }, '', '127.0.0.1');
                return \ob_get_clean();
            }

            public function deleteWithCallable() : string
            {
                \ob_start();
                $this->deleteModel(1, null, function() : void { echo 1; }, '', '127.0.0.1');
                return \ob_get_clean();
            }
        };
    }

    /**
     * @testdox The constant values of the abstract module are overwritten by the extension
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testConstants() : void
    {
        self::assertEquals(2, $this->module::ID);
        self::assertEquals('1.2.3', $this->module::VERSION);
    }

    /**
     * @testdox The name of the module can be returned
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testName() : void
    {
        self::assertEquals('Test', $this->module->getName());
    }

    /**
     * @testdox The dependencies of the module can be returned
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testDependencies() : void
    {
        self::assertEquals([1, 2], $this->module->getDependencies());
    }

    /**
     * @testdox The providings of the module can be returned
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testProviding() : void
    {
        self::assertEquals([], $this->module->getProviding());
    }

    /**
     * @testdox A module can receive information and functionality from another module
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testReceiving() : void
    {
        $this->module->addReceiving('Test2');
        self::assertTrue(\in_array('Test2', $this->module->getReceiving()));
    }

    /**
     * @testdox A module can load its own localization/language dataset
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testLocalization() : void
    {
        self::assertEquals(['Test' => ['Key' => 'Value']], $this->module::getLocalization('en', 'Mytheme'));
    }

    /**
     * @testdox A invalid language or theme returns in an empty localization/language dataset
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testInvalidLocalization() : void
    {
        self::assertEquals([], $this->module::getLocalization('invalid', 'invalid'));
    }

    /**
     * @testdox The module can automatically generate a json response based on provided data for the frontend
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testFillJson() : void
    {
        $request  = new HttpRequest();
        $response = new HttpResponse();

        $this->module->fillJson($request, $response, 'OK', 'Test Title', 'Test Message!', [1, 'test string', 'bool' => true]);

        self::assertEquals(
            [
                'status'   => 'OK',
                'title'    => 'Test Title',
                'message'  => 'Test Message!',
                'response' => [1, 'test string', 'bool' => true],
            ],
            $response->getData('')
        );
    }

    /**
     * @testdox The module can automatically generate a json response based on provided data
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testFillJsonRaw() : void
    {
        $request  = new HttpRequest();
        $response = new HttpResponse();

        $this->module->fillJsonRaw($request, $response, [1, 'test string', 'bool' => true]);

        self::assertEquals(
            [1, 'test string', 'bool' => true],
            $response->getData('')
        );
    }

    /**
     * Create test database schema
     */
    private function dbSetup() : void
    {
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

    /**
     * Teardown test database
     */
    private function dbTeardown() : void
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

    /**
     * @testdox A model can be created
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testModelCreate() : void
    {
        $this->dbSetup();

        $this->module->create();
        self::assertCount(1, BaseModelMapper::getAll()->execute());

        $this->dbTeardown();
    }

    /**
     * @testdox Multiple models can be generated
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testModelsCreate() : void
    {
        $this->dbSetup();

        $this->module->createMultiple();
        self::assertCount(2, BaseModelMapper::getAll()->execute());

        $this->dbTeardown();
    }

    /**
     * @testdox A model can be updated
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testModelUpdate() : void
    {
        $this->dbSetup();

        $this->module->update();
        $updated = BaseModelMapper::get()->where('id', 1)->execute();

        self::assertEquals('Updated', $updated->string);

        $this->dbTeardown();
    }

    /**
     * @testdox A model can be deleted
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testModelDelete() : void
    {
        $this->dbSetup();

        $this->module->create();
        self::assertCount(1, BaseModelMapper::getAll()->execute());
        $this->module->delete();
        self::assertCount(0, BaseModelMapper::getAll()->execute());

        $this->dbTeardown();
    }

    /**
     * @testdox A model relation can be created
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testModelRelation() : void
    {
        $this->dbSetup();

        $this->module->create();
        $this->module->createRelationModel();
        $this->module->createRelationDB();

        $model = BaseModelMapper::get()->with('hasManyRelations')->where('id', 1)->execute();
        self::assertCount(1, $model->hasManyRelations);

        $this->module->deleteRelationDB();

        $model = BaseModelMapper::get()->with('hasManyRelations')->where('id', 1)->execute();

        // count = 2 because the moduel automatically initializes 2 hasMany relationships in the __construct()
        // This actually means that the delete was successful, otherwise the hasManyRelations would have been overwritten with 1 relation (see above before the delete)
        self::assertCount(2, $model->hasManyRelations);
        $this->dbTeardown();
    }

    /**
     * @testdox The model CRUD functions can be called with a closure
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testModelFunctionsWithClosure() : void
    {
        $output = $this->module->createWithCallable();
        self::assertEquals('1', $output);

        $output = $this->module->createsWithCallable();
        self::assertEquals('11', $output);

        $output = $this->module->updateWithCallable();
        self::assertEquals('1', $output);

        $output = $this->module->deleteWithCallable();
        self::assertEquals('1', $output);
    }
}
