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

namespace phpOMS\tests\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Utils\TestUtils;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Schema\Grammar\MysqlGrammarTest: Mysql sql schema grammar
 *
 * @internal
 */
class MysqlGrammarTest extends \PHPUnit\Framework\TestCase
{
    protected MysqlConnection $con;

    protected function setUp() : void
    {
        $this->con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
    }

    /**
     * @testdox The grammar has the expected default values after initialization
     * @covers phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Schema\Grammar\Grammar', new MysqlGrammar());
        self::assertEquals('`', TestUtils::getMember(new MysqlGrammar(), 'systemIdentifier'));
    }

    /**
     * @testdox The the grammar correctly creates and returns a database table
     * @covers phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar<extended>
     * @group framework
     */
    public function testSchemaInputOutput() : void
    {
        $definitions = \json_decode(\file_get_contents(__DIR__ . '/testSchema.json'), true);
        foreach ($definitions as $definition) {
            SchemaBuilder::createFromSchema($definition, $this->con)->execute();
        }

        $table  = new SchemaBuilder($this->con);
        $tables = $table->prefix($this->con->prefix)->selectTables()->execute()->fetchAll(\PDO::FETCH_COLUMN);
        self::assertContains($this->con->prefix . 'test', $tables);
        self::assertContains($this->con->prefix . 'test_foreign', $tables);

        $field  = new SchemaBuilder($this->con);
        $fields = $field->prefix($this->con->prefix)->selectFields('test')->execute()->fetchAll();

        foreach ($definitions['test']['fields'] as $key => $field) {
            self::assertTrue(
                ArrayUtils::inArrayRecursive($key, $fields),
                'Couldn\'t find "' . $key . '" in array'
            );
        }
    }

    /**
     * @testdox The grammar correctly deletes a table
     * @covers phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar<extended>
     * @group framework
     */
    public function testDelete() : void
    {
        $table  = new SchemaBuilder($this->con);
        $tables = $table->prefix($this->con->prefix)->selectTables()->execute()->fetchAll(\PDO::FETCH_COLUMN);

        $delete  = new SchemaBuilder($this->con);
        $delete->prefix($this->con->prefix)->dropTable('test')->execute();
        $delete->prefix($this->con->prefix)->dropTable('test_foreign')->execute();

        $tables = $table->prefix($this->con->prefix)->selectTables()->execute()->fetchAll();
        self::assertNotContains($this->con->prefix . 'test', $tables);
        self::assertNotContains($this->con->prefix . 'test_foreign', $tables);
    }
}
